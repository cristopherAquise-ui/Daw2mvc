<?php
require_once __DIR__ . '/../dao/CarritoDAO.php';
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../dao/PedidoDAO.php';
require_once __DIR__ . '/../dao/OfertaDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class CarritoController
{
    public function index()
    {
        $items   = CarritoDAO::obtenerContenido();
        $totales = CarritoDAO::totales($items);

        $vista = __DIR__ . '/../views/carrito/index.php';
        include __DIR__ . '/../views/main.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=carta');
            exit;
        }

        $producto_id = isset($_POST['producto_id']) ? (int) $_POST['producto_id'] : 0;
        $extras      = isset($_POST['extras']) && is_array($_POST['extras']) ? $_POST['extras'] : [];
        $quitados    = isset($_POST['quitados']) && is_array($_POST['quitados']) ? $_POST['quitados'] : [];

        $producto = $producto_id > 0 ? ProductoDAO::getById($producto_id) : null;

        if (!$producto) {
            $_SESSION['aviso'] = 'El producto no existe o ya no está disponible.';
            header('Location: ' . url . '?controller=carta');
            exit;
        }

        $ofertas = OfertaDAO::getActivasPorProducto($producto_id);
        $mejor   = Oferta::mejorOfertaProducto($producto, $ofertas);

        $oferta_id = null;
        $descuento = 0.0;

        if ($mejor['descuento'] > 0 && $mejor['oferta'] !== null) {
            $oferta_id = $mejor['oferta']->getId();
            $descuento = $mejor['descuento'];
        }

        $ok = CarritoDAO::agregarProducto($producto_id, $extras, $quitados, $oferta_id, $descuento);

        if ($ok) {
            $_SESSION['aviso'] = 'Producto añadido al carrito.';
        } else {
            $_SESSION['aviso'] = 'No se ha podido añadir el producto.';
        }

        $volver = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($volver, url) !== 0) {
            $volver = url . '?controller=carta';
        }

        header('Location: ' . $volver);
        exit;
    }

    public function cantidad()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=carrito');
            exit;
        }

        $id    = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $op    = $_POST['op'] ?? '';
        $delta = $op === 'sumar' ? 1 : -1;

        if ($id > 0) {
            CarritoDAO::cambiarCantidad($id, $delta);
        }

        header('Location: ' . url . '?controller=carrito');
        exit;
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=carrito');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id > 0) {
            CarritoDAO::eliminarItem($id);
            $_SESSION['aviso'] = 'Producto eliminado del carrito.';
        }

        header('Location: ' . url . '?controller=carrito');
        exit;
    }

    public function pago()
    {
        $usuario = $this->usuarioActual();
        $items   = CarritoDAO::obtenerContenido();

        if (count($items) === 0) {
            $_SESSION['aviso'] = 'Tu carrito está vacío.';
            header('Location: ' . url . '?controller=carrito');
            exit;
        }

        $totales = CarritoDAO::totales($items);

        $codigo          = trim((string) ($_GET['codigo'] ?? ''));
        $descuentoCodigo = 0.0;
        $mensajeCodigo   = '';
        $codigoValido    = false;

        if ($codigo !== '') {
            $resultado = Oferta::descuentoCodigo($codigo, $totales['total']);
            $codigoValido    = (bool) $resultado['valido'];
            $descuentoCodigo = (float) $resultado['descuento'];
            $mensajeCodigo   = (string) $resultado['mensaje'];
        }

        $totalConDescuento = max(0, $totales['total'] - $descuentoCodigo);

        $metodosPago = [
            'simulado'      => 'Pago simulado (recomendado para la demo)',
            'tarjeta'       => 'Tarjeta',
            'efectivo'      => 'Efectivo al recibir',
            'paypal'        => 'PayPal',
            'transferencia' => 'Transferencia bancaria',
        ];

        $vista = __DIR__ . '/../views/carrito/pago.php';
        include __DIR__ . '/../views/main.php';
    }

    public function confirmar()
    {
        $usuario = $this->usuarioActual();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=carrito&action=pago');
            exit;
        }

        $datos = [
            'metodo_pago'       => (string) ($_POST['metodo_pago'] ?? 'simulado'),
            'codigo'            => trim((string) ($_POST['codigo'] ?? '')),
            'direccion_entrega' => trim((string) ($_POST['direccion_entrega'] ?? '')),
            'telefono_contacto' => trim((string) ($_POST['telefono_contacto'] ?? '')),
            'notas'             => trim((string) ($_POST['notas'] ?? '')),
        ];

        try {
            $pedido_id = PedidoDAO::confirmar($usuario->getId(), $datos);
        } catch (Throwable $e) {
            $_SESSION['aviso'] = $e->getMessage();
            header('Location: ' . url . '?controller=carrito&action=pago&codigo=' . urlencode($datos['codigo']));
            exit;
        }

        header('Location: ' . url . '?controller=carrito&action=confirmacion&id=' . $pedido_id);
        exit;
    }

    public function confirmacion()
    {
        $usuario = $this->usuarioActual();
        $id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0 && !empty($_SESSION['ultimo_pedido_id'])) {
            $id = (int) $_SESSION['ultimo_pedido_id'];
        }

        $pedido = $id > 0 ? PedidoDAO::getById($id) : null;

        if (!$pedido) {
            $_SESSION['aviso'] = 'No hay ningún pedido que mostrar.';
            header('Location: ' . url . '?controller=inicio');
            exit;
        }

        if (!$usuario->esAdmin() && (int) $pedido->getUsuarioId() !== (int) $usuario->getId()) {
            http_response_code(403);
            $codigoError  = 403;
            $mensajeError = 'No tienes acceso a este pedido.';
            $vista        = __DIR__ . '/../views/error.php';
            include __DIR__ . '/../views/main.php';
            return;
        }

        $detalles = PedidoDAO::getDetalleCompleto($pedido->getId());

        $vista = __DIR__ . '/../views/carrito/confirmacion.php';
        include __DIR__ . '/../views/main.php';
    }

    private function usuarioActual()
    {
        if (empty($_SESSION['usuario'])) {
            $_SESSION['aviso'] = 'Inicia sesión para continuar.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        $usuario = UsuarioDAO::getById((int) $_SESSION['usuario']['id']);

        if (!$usuario || !$usuario->estaActivo()) {
            $_SESSION = [];
            session_destroy();
            $_SESSION['aviso'] = 'Tu cuenta ya no está disponible.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        $_SESSION['usuario'] = [
            'id'     => (int) $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'rol'    => $usuario->getRol(),
        ];
        return $usuario;
    }
}

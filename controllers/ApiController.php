<?php
require_once __DIR__ . '/../config/DataBase.php';
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../dao/CategoriaDAO.php';
require_once __DIR__ . '/../dao/IngredienteDAO.php';
require_once __DIR__ . '/../dao/OfertaDAO.php';
require_once __DIR__ . '/../dao/PedidoDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class ApiController
{
    private static $bodyCache = null;

    public function index()
    {
        $this->error('Indica una acción de la API.', 404);
    }

    public function sesion()
    {
        $usuario = $this->usuarioSesion();

        if (!$usuario) {
            $this->error('No autenticado.', 401);
        }

        $actual = UsuarioDAO::getById((int) $usuario['id']);

        if (!$actual) {
            $this->error('No autenticado.', 401);
        }

        $this->ok([
            'usuario' => UsuarioDAO::toArray($actual),
        ]);
    }

    public function resumen()
    {
        $this->requireAdmin();

        $conexion = DataBase::connect();

        $productos = (int) $conexion->query("SELECT COUNT(*) FROM productos WHERE activo = 1")->fetch_row()[0];
        $pedidos   = (int) $conexion->query("SELECT COUNT(*) FROM pedidos")->fetch_row()[0];
        $usuarios  = (int) $conexion->query("SELECT COUNT(*) FROM usuarios WHERE activo = 1")->fetch_row()[0];
        $ofertas   = (int) $conexion->query("SELECT COUNT(*) FROM ofertas WHERE activa = 1")->fetch_row()[0];
        $ventas    = (float) $conexion->query("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado <> 'cancelado'")->fetch_row()[0];
        $pendientes = (int) $conexion->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetch_row()[0];

        $conexion->close();

        $this->ok([
            'productos'  => $productos,
            'pedidos'    => $pedidos,
            'usuarios'   => $usuarios,
            'ofertas'    => $ofertas,
            'ventas'     => $ventas,
            'pendientes' => $pendientes,
        ]);
    }

    public function productos()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(ProductoDAO::getAllJson($_GET));
        }


        if ($metodo === 'POST') {
            $datos = $this->body();
            $this->validarProducto($datos);

            $id = ProductoDAO::insert($datos);
            $this->ok(['id' => $id], 201);
        }

        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? ProductoDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Producto no encontrado.', 404);
            }

            $datos = array_merge(ProductoDAO::toArray($actual), $this->body());
            $this->validarProducto($datos);

            ProductoDAO::update($id, $datos);
            $this->ok(ProductoDAO::toArray(ProductoDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();

            if ($id <= 0 || !ProductoDAO::delete($id)) {
                $this->error('No se ha podido desactivar el producto.', 404);
            }

            $this->ok(['id' => $id, 'activo' => 0]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function categorias()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(CategoriaDAO::getAllJson());
        }


        if ($metodo === 'POST') {
            $datos = $this->body();
            if (empty($datos['nombre'])) {
                $this->error('El nombre de la categoría es obligatorio.', 422);
            }
            $id = CategoriaDAO::insert($datos);
            $this->ok(['id' => $id], 201);
        }

        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? CategoriaDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Categoría no encontrada.', 404);
            }

            $datos = array_merge(CategoriaDAO::toArray($actual), $this->body());
            if (empty($datos['nombre'])) {
                $this->error('El nombre de la categoría es obligatorio.', 422);
            }

            CategoriaDAO::update($id, $datos);
            $this->ok(CategoriaDAO::toArray(CategoriaDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();
            if ($id <= 0 || !CategoriaDAO::delete($id)) {
                $this->error('No se ha podido desactivar la categoría.', 404);
            }
            $this->ok(['id' => $id, 'activa' => 0]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function ingredientes()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(IngredienteDAO::getAllJson($_GET));
        }


        if ($metodo === 'POST') {
            $datos = $this->body();
            if (empty($datos['nombre'])) {
                $this->error('El nombre del ingrediente es obligatorio.', 422);
            }
            $id = IngredienteDAO::insert($datos);
            $this->ok(['id' => $id], 201);
        }

        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? IngredienteDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Ingrediente no encontrado.', 404);
            }

            $datos = array_merge(IngredienteDAO::toArray($actual), $this->body());
            if (empty($datos['nombre'])) {
                $this->error('El nombre del ingrediente es obligatorio.', 422);
            }

            IngredienteDAO::update($id, $datos);
            $this->ok(IngredienteDAO::toArray(IngredienteDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();
            if ($id <= 0 || !IngredienteDAO::delete($id)) {
                $this->error('No se ha podido desactivar el ingrediente.', 404);
            }
            $this->ok(['id' => $id, 'activo' => 0]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function ofertas()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(OfertaDAO::getAllJson($_GET));
        }


        if ($metodo === 'POST') {
            $datos = $this->body();
            if (empty($datos['nombre'])) {
                $this->error('El nombre de la oferta es obligatorio.', 422);
            }
            $id = OfertaDAO::insert($datos);
            $this->ok(['id' => $id], 201);
        }

        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? OfertaDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Oferta no encontrada.', 404);
            }

            $datos = array_merge(OfertaDAO::toArray($actual), $this->body());
            if (empty($datos['nombre'])) {
                $this->error('El nombre de la oferta es obligatorio.', 422);
            }

            OfertaDAO::update($id, $datos);
            $this->ok(OfertaDAO::toArray(OfertaDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();
            if ($id <= 0 || !OfertaDAO::delete($id)) {
                $this->error('No se ha podido desactivar la oferta.', 404);
            }
            $this->ok(['id' => $id, 'activa' => 0]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function pedidos()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(PedidoDAO::getAllJson($_GET));
        }


        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? PedidoDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Pedido no encontrado.', 404);
            }

            $datos = $this->body();

            $estados = ['pendiente', 'confirmado', 'preparando', 'enviado', 'entregado', 'cancelado'];
            $estadosPago = ['pendiente', 'pagado', 'fallido', 'reembolsado'];

            $estado = in_array($datos['estado'] ?? '', $estados, true) ? $datos['estado'] : $actual->getEstado();
            $estadoPago = in_array($datos['estado_pago'] ?? '', $estadosPago, true) ? $datos['estado_pago'] : $actual->getEstadoPago();

            PedidoDAO::updateEstado($id, $estado, $estadoPago);

            // Ajuste manual del descuento o del total final.
            if (array_key_exists('descuento', $datos) || array_key_exists('total', $datos)) {
                $subtotal  = (float) $actual->getSubtotal();
                $descuento = array_key_exists('descuento', $datos) ? (float) $datos['descuento'] : (float) $actual->getDescuento();
                $total     = array_key_exists('total', $datos) ? (float) $datos['total'] : max(0, $subtotal - $descuento);

                $baseActual = (float) $actual->getBaseImponible();
                $ivaActual  = (float) $actual->getIva();
                $tipoIva    = $baseActual > 0 ? ($ivaActual / $baseActual) : 0.10;
                $base       = $total / (1 + $tipoIva);
                $iva        = $total - $base;

                PedidoDAO::updateTotales($id, $subtotal, $descuento, $base, $iva, $total);
            }

            $this->ok(PedidoDAO::toArray(PedidoDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $this->error('Los pedidos no se borran: cambia su estado a "cancelado".', 405);
        }

        $this->error('Método no permitido.', 405);
    }

    public function pedidoDetalle()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $pedido_id = isset($_GET['pedido_id']) ? (int) $_GET['pedido_id'] : 0;

            if ($pedido_id <= 0) {
                $this->error('Indica pedido_id.', 422);
            }

            $this->ok($this->detalleJson($pedido_id));
        }


        if ($metodo === 'POST') {
            $datos = $this->body();
            $pedido_id = (int) ($datos['pedido_id'] ?? 0);

            if ($pedido_id <= 0) {
                $this->error('Indica pedido_id.', 422);
            }

            $producto = !empty($datos['producto_id']) ? ProductoDAO::getById((int) $datos['producto_id']) : null;

            $precio = isset($datos['precio_unitario']) && $datos['precio_unitario'] !== ''
                ? (float) $datos['precio_unitario']
                : ($producto ? (float) $producto->getPrecio() : 0.0);

            $iva = isset($datos['iva_porcentaje']) && $datos['iva_porcentaje'] !== ''
                ? (float) $datos['iva_porcentaje']
                : ($producto ? (float) $producto->getIvaPorcentaje() : 10.0);

            $cantidad = max(1, (int) ($datos['cantidad'] ?? 1));
            $nombre   = (string) ($datos['nombre_producto'] ?? ($producto ? $producto->getNombre() : 'Producto'));

            $linea_id = PedidoDAO::insertDetalle([
                'pedido_id'       => $pedido_id,
                'producto_id'     => $producto ? $producto->getId() : null,
                'oferta_id'       => null,
                'nombre_producto' => $nombre,
                'precio_unitario' => $precio,
                'cantidad'        => $cantidad,
                'descuento'       => 0,
                'subtotal'        => $precio * $cantidad,
                'iva_porcentaje'  => $iva,
            ]);

            $totales = PedidoDAO::recalcular($pedido_id);
            $this->ok(['id' => $linea_id, 'totales' => $totales], 201);
        }

        if ($metodo === 'PUT') {
            $id = $this->id();
            $linea = $id > 0 ? PedidoDAO::getDetalleById($id) : null;

            if (!$linea) {
                $this->error('Línea de pedido no encontrada.', 404);
            }

            $datos = $this->body();
            $cantidad = max(1, (int) ($datos['cantidad'] ?? $linea->getCantidad()));
            $precio   = (float) ($datos['precio_unitario'] ?? $linea->getPrecioUnitario());
            $descuento = (float) ($datos['descuento'] ?? $linea->getDescuento());
            $iva      = (float) ($datos['iva_porcentaje'] ?? $linea->getIvaPorcentaje());
            $subtotal = max(0, ($precio * $cantidad) - $descuento);

            PedidoDAO::updateDetalle($id, $cantidad, $precio, $descuento, $subtotal, $iva);
            $totales = PedidoDAO::recalcular($linea->getPedidoId());

            $this->ok(['totales' => $totales]);
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();
            $linea = $id > 0 ? PedidoDAO::getDetalleById($id) : null;

            if (!$linea) {
                $this->error('Línea de pedido no encontrada.', 404);
            }

            $pedido_id = $linea->getPedidoId();
            PedidoDAO::deleteDetalle($id);
            $totales = PedidoDAO::recalcular($pedido_id);

            $this->ok(['totales' => $totales]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function pedidoTotal()
    {
        $this->requireAdmin();

        if ($this->method() !== 'PUT') {
            $this->error('Método no permitido.', 405);
        }


        $id = $this->id();

        if ($id <= 0 || !PedidoDAO::getById($id)) {
            $this->error('Pedido no encontrado.', 404);
        }

        $this->ok(PedidoDAO::recalcular($id));
    }

    public function usuarios()
    {
        $this->requireAdmin();
        $metodo = $this->method();

        if ($metodo === 'GET') {
            $this->ok(UsuarioDAO::getAllJson($_GET));
        }


        if ($metodo === 'PUT') {
            $id = $this->id();
            $actual = $id > 0 ? UsuarioDAO::getById($id) : null;

            if (!$actual) {
                $this->error('Usuario no encontrado.', 404);
            }

            $datos = $this->body();
            $rol = in_array($datos['rol'] ?? '', ['usuario', 'admin'], true) ? $datos['rol'] : $actual->getRol();
            $activo = array_key_exists('activo', $datos) ? (int) $datos['activo'] : (int) $actual->getActivo();
            $activo = $activo === 1 ? 1 : 0;

            if ($id === (int) $this->usuarioId() && ($activo === 0 || $rol !== 'admin')) {
                $this->error('No puedes desactivar tu propia cuenta ni quitarte el rol de administrador.', 409);
            }

            UsuarioDAO::setRol($id, $rol);
            UsuarioDAO::setActivo($id, $activo);

            $this->ok(UsuarioDAO::toArray(UsuarioDAO::getById($id)));
        }

        if ($metodo === 'DELETE') {
            $id = $this->id();

            if ($id === (int) $this->usuarioId()) {
                $this->error('No puedes desactivar tu propia cuenta.', 409);
            }

            if ($id <= 0 || !UsuarioDAO::setActivo($id, 0)) {
                $this->error('No se ha podido desactivar el usuario.', 404);
            }

            $this->ok(['id' => $id, 'activo' => 0]);
        }

        $this->error('Método no permitido.', 405);
    }

    public function usuarioPedidos()
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $this->error('Indica el id del usuario.', 422);
        }

        $pedidos = PedidoDAO::getByUsuario($id);
        $this->ok(array_map([PedidoDAO::class, 'toArray'], $pedidos));
    }

    private function detalleJson($pedido_id): array
    {
        $lineas = PedidoDAO::getDetalleCompleto($pedido_id);
        $datos = [];

        foreach ($lineas as $fila) {
            $linea = $fila['linea'];

            $datos[] = [
                'id'              => (int) $linea->getId(),
                'pedido_id'       => (int) $linea->getPedidoId(),
                'producto_id'     => $linea->getProductoId() !== null ? (int) $linea->getProductoId() : null,
                'oferta_id'       => $linea->getOfertaId() !== null ? (int) $linea->getOfertaId() : null,
                'nombre_producto' => $linea->getNombreProducto(),
                'precio_unitario' => (float) $linea->getPrecioUnitario(),
                'cantidad'        => (int) $linea->getCantidad(),
                'descuento'       => (float) $linea->getDescuento(),
                'subtotal'        => (float) $linea->getSubtotal(),
                'iva_porcentaje'  => (float) $linea->getIvaPorcentaje(),
                'mods'            => $fila['mods'],
            ];
        }

        return $datos;
    }

    private function validarProducto(array $datos): void
    {
        if (empty($datos['nombre'])) {
            $this->error('El nombre del producto es obligatorio.', 422);
        }

        if (empty($datos['categoria_id'])) {
            $this->error('La categoría es obligatoria.', 422);
        }

        if (!isset($datos['precio']) || (float) $datos['precio'] < 0) {
            $this->error('El precio debe ser un número mayor o igual que cero.', 422);
        }
    }

    private function requireAdmin(): void
    {
        $usuario = $this->usuarioSesion();

        if (!$usuario) {
            $this->error('No autenticado.', 401);
        }

        if (($usuario['rol'] ?? '') !== 'admin') {
            $this->error('No tienes permisos de administrador.', 403);
        }
    }

    private function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    private function id(): int
    {
        if (isset($_GET['id'])) {
            return (int) $_GET['id'];
        }

        $body = $this->body();
        return (int) ($body['id'] ?? 0);
    }

    private function body(): array
    {
        if (self::$bodyCache === null) {
            self::$bodyCache = $this->leerBody();
        }

        return self::$bodyCache;
    }

    /**
     * Lee el cuerpo JSON de una petición (POST/PUT/DELETE).
     */
    private function leerBody(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $datos = json_decode($raw, true);
        return is_array($datos) ? $datos : [];
    }

    private function ok($datos = null, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true, 'data' => $datos], JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function error(string $mensaje, int $codigo = 400): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => $mensaje], JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function usuarioSesion(): ?array
    {
        return $_SESSION['usuario'] ?? null;
    }

    private function usuarioId(): int
    {
        $usuario = $this->usuarioSesion();
        return $usuario ? (int) $usuario['id'] : 0;
    }
}

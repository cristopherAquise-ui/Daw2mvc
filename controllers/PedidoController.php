<?php
require_once __DIR__ . '/../dao/PedidoDAO.php';
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class PedidoController
{
    public function historial()
    {
        $usuario = $this->usuarioActual();
        $pedidos = PedidoDAO::getByUsuario($usuario->getId());

        $detalles = [];
        foreach ($pedidos as $pedido) {
            $detalles[$pedido->getId()] = PedidoDAO::getDetalleCompleto($pedido->getId());
        }

        $vista = __DIR__ . '/../views/pedidos/historial.php';
        include __DIR__ . '/../views/main.php';
    }

    public function detalle()
    {
        $usuario = $this->usuarioActual();
        $id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $pedido  = $id > 0 ? PedidoDAO::getById($id) : null;

        if (!$pedido) {
            http_response_code(404);
            $codigoError  = 404;
            $mensajeError = 'El pedido no existe.';
            $vista        = __DIR__ . '/../views/error.php';
            include __DIR__ . '/../views/main.php';
            return;
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
        $cliente  = UsuarioDAO::getById($pedido->getUsuarioId());

        $vista = __DIR__ . '/../views/pedidos/detalle.php';
        include __DIR__ . '/../views/main.php';
    }

    public function ultimo()
    {
        $usuario = $this->usuarioActual();
        $pedido  = PedidoDAO::getUltimoByUsuario($usuario->getId());

        if (!$pedido) {
            $_SESSION['aviso'] = 'Todavía no has realizado ningún pedido.';
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        $detalles = PedidoDAO::getDetalleCompleto($pedido->getId());
        $cliente  = UsuarioDAO::getById($pedido->getUsuarioId());

        $vista = __DIR__ . '/../views/pedidos/detalle.php';
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

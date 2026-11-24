<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';

class AdminController
{
    public function index()
    {
        if (empty($_SESSION['usuario'])) {
            $_SESSION['aviso'] = 'Inicia sesión para continuar.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        $id      = isset($_SESSION['usuario']['id']) ? (int) $_SESSION['usuario']['id'] : 0;
        $usuario = $id > 0 ? UsuarioDAO::getById($id) : null;

        if (!$usuario || !$usuario->esAdmin()) {
            http_response_code(403);
            exit('No tienes permisos de administrador.');
        }

        $_SESSION['usuario'] = [
            'id'     => (int) $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'rol'    => $usuario->getRol(),
        ];

        $vista = __DIR__ . '/../views/admin/panel.php';
        include __DIR__ . '/../views/main.php';
    }
}

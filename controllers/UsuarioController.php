<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../dao/PedidoDAO.php';
require_once __DIR__ . '/../dao/CarritoDAO.php';

class UsuarioController
{
    public function login()
    {
        if (!empty($_SESSION['usuario'])) {
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        $vista = __DIR__ . '/../views/usuario/login.php';
        include __DIR__ . '/../views/main.php';
    }

    public function autenticar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['aviso'] = 'Escribe tu email y tu contraseña.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        $usuario = UsuarioDAO::getByEmail($email);

        if (!$usuario || !password_verify($password, $usuario->getPassword())) {
            $_SESSION['aviso'] = 'El email o la contraseña no son correctos.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        if (!$usuario->estaActivo()) {
            $_SESSION['aviso'] = 'Tu cuenta está desactivada. Contacta con el restaurante.';
            header('Location: ' . url . '?controller=usuario&action=login');
            exit;
        }

        // Se guardan los datos básicos en la sesión y se renueva el id de sesión.
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id'     => (int) $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'rol'    => $usuario->getRol(),
        ];

        CarritoDAO::vincularUsuario($usuario->getId());
        UsuarioDAO::registrarAcceso($usuario->getId());

        header('Location: ' . url . '?controller=usuario&action=panel');
        exit;
    }

    public function registro()
    {
        if (!empty($_SESSION['usuario'])) {
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        $vista = __DIR__ . '/../views/usuario/registro.php';
        include __DIR__ . '/../views/main.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        $nombre    = trim((string) ($_POST['nombre'] ?? ''));
        $apellidos = trim((string) ($_POST['apellidos'] ?? ''));
        $email     = trim((string) ($_POST['email'] ?? ''));
        $password  = (string) ($_POST['password'] ?? '');
        $telefono  = trim((string) ($_POST['telefono'] ?? ''));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));
        $ciudad    = trim((string) ($_POST['ciudad'] ?? ''));
        $cp        = trim((string) ($_POST['codigo_postal'] ?? ''));
        $fechaNac  = trim((string) ($_POST['fecha_nacimiento'] ?? ''));

        if ($nombre === '' || $email === '' || $password === '') {
            $_SESSION['aviso'] = 'El nombre, el email y la contraseña son obligatorios.';
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['aviso'] = 'El email no tiene un formato válido.';
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['aviso'] = 'La contraseña debe tener al menos 8 caracteres.';
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        if (UsuarioDAO::emailExiste($email)) {
            $_SESSION['aviso'] = 'Ya existe una cuenta con ese email.';
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        $id = UsuarioDAO::insert([
            'nombre'           => $nombre,
            'apellidos'        => $apellidos,
            'email'            => $email,
            'password'         => $password,
            'telefono'         => $telefono,
            'direccion'        => $direccion,
            'ciudad'           => $ciudad,
            'codigo_postal'    => $cp,
            'fecha_nacimiento' => $fechaNac,
            'rol'              => 'usuario',
            'activo'           => 1,
        ]);

        if (!$id) {
            $_SESSION['aviso'] = 'No se ha podido crear la cuenta. Inténtalo de nuevo.';
            header('Location: ' . url . '?controller=usuario&action=registro');
            exit;
        }

        session_regenerate_id(true);
        $nuevo = UsuarioDAO::getById($id);
        $_SESSION['usuario'] = [
            'id'     => (int) $nuevo->getId(),
            'nombre' => $nuevo->getNombre(),
            'rol'    => $nuevo->getRol(),
        ];

        CarritoDAO::vincularUsuario($id);
        $_SESSION['aviso'] = 'Cuenta creada correctamente. ¡Bienvenido a Star Bites!';

        header('Location: ' . url . '?controller=usuario&action=panel');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        header('Location: ' . url . '?controller=inicio');
        exit;
    }

    public function panel()
    {
        $usuario = $this->usuarioActual();

        $pedidos  = PedidoDAO::getByUsuario($usuario->getId());
        $detalles = [];
        foreach ($pedidos as $pedido) {
            $detalles[$pedido->getId()] = PedidoDAO::getDetalleCompleto($pedido->getId());
        }

        $resumen = PedidoDAO::getResumenUsuario($usuario->getId());
        $ultimo  = PedidoDAO::getUltimoByUsuario($usuario->getId());

        $vista = __DIR__ . '/../views/usuario/panel.php';
        include __DIR__ . '/../views/main.php';
    }

    public function actualizar()
    {
        $usuario = $this->usuarioActual();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        $nombre    = trim((string) ($_POST['nombre'] ?? ''));
        $apellidos = trim((string) ($_POST['apellidos'] ?? ''));
        $email     = trim((string) ($_POST['email'] ?? ''));
        $telefono  = trim((string) ($_POST['telefono'] ?? ''));
        $direccion = trim((string) ($_POST['direccion'] ?? ''));
        $ciudad    = trim((string) ($_POST['ciudad'] ?? ''));
        $cp        = trim((string) ($_POST['codigo_postal'] ?? ''));
        $fechaNac  = trim((string) ($_POST['fecha_nacimiento'] ?? ''));

        if ($nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['aviso'] = 'El nombre y un email válido son obligatorios.';
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        if (UsuarioDAO::emailExiste($email, $usuario->getId())) {
            $_SESSION['aviso'] = 'Ese email ya está en uso por otra cuenta.';
            header('Location: ' . url . '?controller=usuario&action=panel');
            exit;
        }

        UsuarioDAO::update($usuario->getId(), [
            'nombre'           => $nombre,
            'apellidos'        => $apellidos,
            'email'            => $email,
            'telefono'         => $telefono,
            'direccion'        => $direccion,
            'ciudad'           => $ciudad,
            'codigo_postal'    => $cp,
            'fecha_nacimiento' => $fechaNac,
            'rol'              => $usuario->getRol(),
            'activo'           => $usuario->getActivo(),
        ]);

        // Se refresca el usuario guardado en la sesión.
        $actual = UsuarioDAO::getById($usuario->getId());
        $_SESSION['usuario'] = [
            'id'     => (int) $actual->getId(),
            'nombre' => $actual->getNombre(),
            'rol'    => $actual->getRol(),
        ];
        $_SESSION['aviso'] = 'Datos actualizados correctamente.';

        header('Location: ' . url . '?controller=usuario&action=panel');
        exit;
    }

    private function usuarioActual()
    {
        if (empty($_SESSION['usuario'])) {
            $_SESSION['aviso'] = 'Inicia sesión para acceder a tu cuenta.';
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

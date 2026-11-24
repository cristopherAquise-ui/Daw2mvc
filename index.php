<?php
include_once("config/parameters.php");
include_once("helpers/formato.php");

// La sesión guarda el usuario, el carrito y los avisos entre páginas.
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

function showError($codigo, $mensaje)
{
    http_response_code($codigo);

    $codigoError  = $codigo;
    $mensajeError = $mensaje;
    $vista        = __DIR__ . '/views/error.php';

    include __DIR__ . '/views/main.php';
    exit;
}

if (!isset($_GET['controller']) || $_GET['controller'] === '') {
    header("Location: " . url . "?controller=" . default_controller);
    exit;
}

$controllerParam = $_GET['controller'];

// ucfirst pone la primera letra en mayúscula: ?controller=usuario -> UsuarioController
$nombre_controller = ucfirst($controllerParam) . "Controller";
$controllerFile    = __DIR__ . "/controllers/" . $nombre_controller . ".php";

if (!file_exists($controllerFile)) {
    showError(404, "No existe el controller " . $nombre_controller);
}

include_once $controllerFile;

if (!class_exists($nombre_controller)) {
    showError(500, "El fichero existe pero no contiene la clase " . $nombre_controller);
}

$controller = new $nombre_controller();

$action = default_action;
if (isset($_GET['action']) && $_GET['action'] !== '') {
    $action = $_GET['action'];
}

if (!is_callable([$controller, $action])) {
    showError(404, "No existe la action \"" . $action . "\" en " . $nombre_controller);
}

if ($controllerParam === 'api') {
    header("Content-Type: application/json; charset=utf-8");
}

$controller->$action();
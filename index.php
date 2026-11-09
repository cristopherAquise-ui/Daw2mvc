<?php
include_once("config/parameters.php");

function showError($codigo, $mensaje)
{
    http_response_code($codigo);
    $codigoError = $codigo;
    $mensajeError = $mensaje;
    $vista = "views/error.php";
    include_once "views/main.php";
    exit;
}

if (!isset($_GET['controller'])) {
    header("Location: " . url . "?controller=" . default_controller);
    exit;
}

$controladorUrl = $_GET['controller'];
$nombreControlador = $controladorUrl . "Controller";
$archivoControlador = "controllers/" . $nombreControlador . ".php";

if (!file_exists($archivoControlador)) {
    showError(404, "No existe el controller " . $nombreControlador);
}

include_once $archivoControlador;

if (!class_exists($nombreControlador)) {
    showError(500, "El fichero existe pero no contiene la clase " . $nombreControlador);
}

$controlador = new $nombreControlador();

$accion = default_action;
if (isset($_GET['action']) && $_GET['action'] !== '') {
    $accion = $_GET['action'];
}

if (!is_callable([$controlador, $accion])) {
    showError(404, "No existe la action \"" . $accion . "\" en " . $nombreControlador);
}

$controlador->$accion();

<?php
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script    = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$carpeta   = rtrim(str_replace('\\', '/', dirname($script)), '/');

if ($carpeta === '.' || $carpeta === '/') {
    $carpeta = '';
}

define('url', $protocolo . '://' . $host . $carpeta . '/');
define('default_controller', 'inicio');
define('default_action', 'index');
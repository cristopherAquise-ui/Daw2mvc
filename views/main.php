<?php
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
include_once 'views/header.php';
include_once $vista;
include_once 'views/footer.php';
?>

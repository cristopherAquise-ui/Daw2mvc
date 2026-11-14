<?php
include_once("models/CarritoDAO.php");

class carritoController
{
    public function index()
    {
        $items = CarritoDAO::obtenerContenido();
        $totalCarrito = 0;
        foreach ($items as $item) {
            $totalCarrito += (float)$item['subtotal'];
        }

        $vista = "views/carrito/index.php";
        include_once 'views/main.php';
    }

  public function add()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
        $producto_id = (int)$_POST['producto_id'];
        $extras = isset($_POST['extras']) && is_array($_POST['extras']) ? $_POST['extras'] : [];
        CarritoDAO::agregarProducto($producto_id, $extras);
        $_SESSION['mensaje_carrito'] = "Plato añadido al carrito correctamente.";
    }

    // Regresa a la página anterior (detalle del producto o carta)
    $paginaAnterior = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url . "?controller=carta";
    header("Location: " . $paginaAnterior);
    exit;
}

    public function cantidad()
    {
        if (isset($_GET['id'], $_GET['op'])) {
            $id = (int)$_GET['id'];
            $delta = ($_GET['op'] === 'sumar') ? 1 : -1;
            CarritoDAO::cambiarCantidad($id, $delta);
        }

        header("Location: " . url . "?controller=carrito");
        exit;
    }

    public function eliminar()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            CarritoDAO::eliminarItem($id);
        }

        header("Location: " . url . "?controller=carrito");
        exit;
    }
}
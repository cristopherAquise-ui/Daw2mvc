<?php
include_once("models/ProductoDAO.php");
include_once("models/IngredienteDAO.php");

class productoController
{
    public function index()
    {
        if (isset($_GET['categoria_id']) && !empty($_GET['categoria_id'])) {
            $categoria_id = (int)$_GET['categoria_id'];
            $productos = ProductoDAO::getByCategoria($categoria_id);
        } else {
            $productos = ProductoDAO::getAll();
        }

        $view = "views/productos/listado.php";
        include_once 'views/main.php';
    }
    public function detalle()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $producto = ProductoDAO::getById($id);
            $extras = IngredienteDAO::getExtrasByProducto($id);
                        $view = "views/productos/detalle.php";
            include_once 'views/main.php';
        } else {
            header("Location: " . url . "?controller=producto");
        }
    }
}
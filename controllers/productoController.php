<?php
include_once("models/ProductoDAO.php");

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
}
<?php
include_once("models/ProductoDAO.php");

class cartaController
{
    public function index()
    {
        $productos = ProductoDAO::getAll();   
        $view = "views/carta.php";
        include_once 'views/main.php';
    }
}
?>
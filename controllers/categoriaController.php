<?php
include_once("models/CategoriaDAO.php");

class categoriaController
{
    public function index()
    {
        $categorias = CategoriaDAO::getAll();
        $vista = "views/categorias/listado.php";
        include_once 'views/main.php';
    }
}

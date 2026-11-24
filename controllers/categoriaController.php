<?php
require_once __DIR__ . '/../dao/CategoriaDAO.php';

class CategoriaController
{
    public function index()
    {
        $categorias = CategoriaDAO::getActivas();

        $vista = __DIR__ . '/../views/categorias/listado.php';
        include __DIR__ . '/../views/main.php';
    }
}

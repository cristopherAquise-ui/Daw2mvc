<?php
require_once __DIR__ . '/../dao/ProductoDAO.php';

class InicioController
{
    public function index()
    {
        $productos  = ProductoDAO::getAll();
        $destacados = ProductoDAO::getDestacados(2);

        $vista = __DIR__ . '/../views/inicio.php';
        include __DIR__ . '/../views/main.php';
    }
}

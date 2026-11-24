<?php
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../dao/CategoriaDAO.php';
require_once __DIR__ . '/../dao/OfertaDAO.php';

class CartaController
{
    public function index()
    {
        $productos  = ProductoDAO::getAll();
        $categorias = CategoriaDAO::getActivas();

        // Mejor oferta aplicable a cada producto, para mostrarla en su tarjeta.
        $ofertasPorProducto = [];
        foreach ($productos as $producto) {
            $ofertas = OfertaDAO::getActivasPorProducto($producto->getId());
            $mejor   = Oferta::mejorOfertaProducto($producto, $ofertas);

            if ($mejor['oferta'] !== null) {
                $ofertasPorProducto[$producto->getId()] = $mejor;
            }
        }

        $vista = __DIR__ . '/../views/carta.php';
        include __DIR__ . '/../views/main.php';
    }
}

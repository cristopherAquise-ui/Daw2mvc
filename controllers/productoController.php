<?php
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../dao/IngredienteDAO.php';
require_once __DIR__ . '/../dao/CategoriaDAO.php';
require_once __DIR__ . '/../dao/OfertaDAO.php';

class ProductoController
{
    public function index()
    {
        if (isset($_GET['categoria_id']) && $_GET['categoria_id'] !== '') {
            $productos = ProductoDAO::getByCategoria((int) $_GET['categoria_id']);
        } else {
            $productos = ProductoDAO::getAll();
        }

        $vista = __DIR__ . '/../views/productos/listado.php';
        include __DIR__ . '/../views/main.php';
    }

    public function detalle()
    {
        $id       = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $producto = $id > 0 ? ProductoDAO::getById($id) : null;

        if (!$producto) {
            header('Location: ' . url . '?controller=carta');
            exit;
        }

        $base      = IngredienteDAO::getBaseByProducto($id);
        $extras    = IngredienteDAO::getExtrasByProducto($id);
        $categoria = CategoriaDAO::getById($producto->getCategoriaId());
        $ofertas   = OfertaDAO::getActivasPorProducto($id);

        $vista = __DIR__ . '/../views/productos/detalle.php';
        include __DIR__ . '/../views/main.php';
    }
}

<?php
require_once __DIR__ . '/../dao/OfertaDAO.php';
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../dao/CarritoDAO.php';

class OfertaController
{
    public function index()
    {
        $ofertas = OfertaDAO::getActivas();
        $productosPorOferta = [];

        foreach ($ofertas as $oferta) {
            $productos = [];

            if ($oferta->getAmbito() === 'producto') {
                $productos = OfertaDAO::getProductos($oferta->getId());
            } elseif ($oferta->getAmbito() === 'categoria' && $oferta->getCategoriaId()) {
                $productos = ProductoDAO::getByCategoria($oferta->getCategoriaId());
            }

            $productosPorOferta[$oferta->getId()] = $productos;
        }

        $vista = __DIR__ . '/../views/ofertas/index.php';
        include __DIR__ . '/../views/main.php';
    }

    public function agregar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url . '?controller=oferta');
            exit;
        }

        $oferta_id   = isset($_POST['oferta_id']) ? (int) $_POST['oferta_id'] : 0;
        $producto_id = isset($_POST['producto_id']) ? (int) $_POST['producto_id'] : 0;
        $cantidad    = isset($_POST['cantidad']) ? max(1, (int) $_POST['cantidad']) : 1;

        $oferta = $oferta_id > 0 ? OfertaDAO::getById($oferta_id) : null;

        if (!$oferta) {
            $_SESSION['aviso'] = 'La oferta no existe o ya no está disponible.';
            header('Location: ' . url . '?controller=oferta');
            exit;
        }

        if ($oferta->getTipo() === 'codigo') {
            $_SESSION['aviso'] = 'Esta oferta se aplica introduciendo el código en el pago.';
            header('Location: ' . url . '?controller=oferta');
            exit;
        }

        if (in_array($oferta->getTipo(), ['2x1', 'combo', 'descuento'], true) && $producto_id <= 0) {
            $_SESSION['aviso'] = 'Selecciona un producto para aplicar la oferta.';
            header('Location: ' . url . '?controller=oferta');
            exit;
        }

        $ok = CarritoDAO::agregarOferta($oferta, $producto_id ?: null, $cantidad);

        if ($ok) {
            $_SESSION['aviso'] = 'Oferta añadida al carrito.';
        } else {
            $_SESSION['aviso'] = 'No se ha podido añadir la oferta. Revisa el producto seleccionado.';
        }

        header('Location: ' . url . '?controller=carrito');
        exit;
    }
}

<?php
$categoriaActual = isset($_GET['categoria']) ? (string) $_GET['categoria'] : 'todos';
$ofertasPorProducto = $ofertasPorProducto ?? [];
?>

<!-- menu de categorias -->
<div class="menu-categorias d-flex gap-4 pb-2 mb-5">
    <a href="<?= url ?>?controller=carta&categoria=todos"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual === 'todos') ? 'active' : '' ?>">
        TODOS
    </a>

    <?php foreach ($categorias as $categoria): ?>
        <?php $clave = (string) $categoria->getId(); ?>
        <a href="<?= url ?>?controller=carta&categoria=<?= $clave ?>"
            class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual === $clave) ? 'active' : '' ?>">
            <?= texto($categoria->getNombre()) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php foreach ($categorias as $categoria): ?>
    <?php
    $clave = (string) $categoria->getId();
    if ($categoriaActual !== 'todos' && $categoriaActual !== $clave) {
        continue;
    }

    $productosCategoria = array_filter($productos, function ($producto) use ($categoria) {
        return (int) $producto->getCategoriaId() === (int) $categoria->getId();
    });

    if (count($productosCategoria) === 0) {
        continue;
    }
    ?>

    <h2 class="fw-bold mt-5 mb-4"><?= texto($categoria->getNombre()) ?></h2>

    <!-- platos -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 gx-4 gy-5 mb-5">
        <?php foreach ($productosCategoria as $producto): ?>
            <?php $mejor = $ofertasPorProducto[$producto->getId()] ?? null; ?>
            <div class="col">
                <div class="tarjeta-producto h-100">

                    <div class="foto-producto">
                        <img src="<?= url ?>public/img/productos/<?= texto($producto->getImagen()) ?>"
                            alt="<?= texto($producto->getNombre()) ?>"
                            class="foto-ajustada card-img-top">
                    </div>

                    <div class="detalle-producto px-3 pt-3 pb-4 d-flex flex-column">
                        <div>
                            <div class="caja-decoracion">
                                <div class="decoracion"></div>
                            </div>

                            <h3 class="titulo-producto mt-2 mb-0 h5"><?= texto($producto->getNombre()) ?></h3>

                            <?php if ($mejor !== null): ?>
                                <span class="oferta-badge mt-2 mb-0">
                                    <?= texto(Oferta::etiquetaTexto($mejor['oferta'])) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                            <span class="precio-producto fw-bold">
                                <?php if ($mejor !== null && $mejor['descuento'] > 0): ?>
                                    <span class="text-decoration-line-through text-secondary small d-block">
                                        <?= precio($producto->getPrecio()) ?>
                                    </span>
                                <?php endif; ?>
                                <?= precio($mejor !== null && $mejor['descuento'] > 0 ? $mejor['precio_final'] : $producto->getPrecio()) ?>
                            </span>

                            <div class="d-inline-flex align-items-center gap-2">
                                <a href="<?= url ?>?controller=producto&action=detalle&id=<?= (int) $producto->getId() ?>"
                                    class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                    title="Ver detalle">
                                    <img src="<?= url ?>public/svg/icono/editar-negro.svg" alt="Ver detalle" class="boton-editar">
                                </a>

                                <form action="<?= url ?>?controller=carrito&action=add" method="POST" class="d-inline m-0">
                                    <input type="hidden" name="producto_id" value="<?= (int) $producto->getId() ?>">
                                    <button type="submit"
                                        class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                        title="Añadir al carrito">
                                        +
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<?php
$categoriaActual = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';
?>

<!-- menu de categorias -->
<div class="menu-categorias d-flex gap-4 pb-2 mb-5">
    <a href="?controller=carta&categoria=todos"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'todos') ? 'active' : '' ?>">
        TODOS
    </a>
    <a href="?controller=carta&categoria=entrantes"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'entrantes') ? 'active' : '' ?>">
        ENTRANTES
    </a>
    <a href="?controller=carta&categoria=primeros"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'primeros') ? 'active' : '' ?>">
        PRIMEROS PLATOS
    </a>
    <a href="?controller=carta&categoria=segundos"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'segundos') ? 'active' : '' ?>">
        SEGUNDOS PLATOS
    </a>
    <a href="?controller=carta&categoria=guarniciones"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'guarniciones') ? 'active' : '' ?>">
        GUARNICIONES
    </a>
    <a href="?controller=carta&categoria=postres"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'postres') ? 'active' : '' ?>">
        POSTRES
    </a>
    <a href="?controller=carta&categoria=bebidas"
        class="enlace-categoria text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'bebidas') ? 'active' : '' ?>">
        BEBIDAS
    </a>
</div>

<?php
$listaCategorias = [
    'entrantes' => ['id' => 1, 'titulo' => 'Entrantes'],
    'primeros' => ['id' => 2, 'titulo' => 'Primeros Platos'],
    'segundos' => ['id' => 3, 'titulo' => 'Segundos Platos'],
    'guarniciones' => ['id' => 4, 'titulo' => 'Guarniciones'],
    'postres' => ['id' => 5, 'titulo' => 'Postres'],
    'bebidas' => ['id' => 6, 'titulo' => 'Bebidas']
];

foreach ($listaCategorias as $clave => $datosCategoria):
    if ($categoriaActual == 'todos' || $categoriaActual == $clave):
?>
        <h2 class="fw-bold mt-5 mb-4"><?= $datosCategoria['titulo'] ?></h2>

        <!-- platos -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 gx-4 gy-5 mb-5">
            <?php
            foreach ($productos as $producto):

                // compara el id del producto con el de la categoria
                if ($producto->getCategoriaId() == $datosCategoria['id']):
            ?>
                    <div class="col">
                        <div class="tarjeta-producto h-100">

                            <div class="foto-producto">
                                <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                                    class="foto-ajustada card-img-top">
                            </div>

                            <div class="detalle-producto px-3 pt-3 pb-4 d-flex flex-column">
                                <div>
                                    <div class="caja-decoracion">
                                        <div class="decoracion"></div>
                                    </div>

                                    <h5 class="titulo-producto mt-2 mb-0">
                                        <?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                    <span class="precio-producto fw-bold"><?= number_format((float) $producto->getPrecio(), 2, ',', '.') ?>
                                        €</span>
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <!-- Botón 1: Ver detalle y extras -->
                                        <a href="<?= url ?>?controller=producto&action=detalle&id=<?= $producto->getId() ?>"
                                            class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                            title="Ver detalle">
                                            <img src="<?= url ?>public/svg/icono/editar-negro.svg" alt="Ver detalle" class="boton-editar">
                                        </a>

                                        <!-- Botón 2: Añadir directamente al carrito -->
                                        <form action="<?= url ?>?controller=carrito&action=add" method="POST" class="d-inline m-0">
                                            <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">
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
            <?php
                endif;
            endforeach;
            ?>
        </div>

<?php
    endif;
endforeach;
?>
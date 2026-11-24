<div class="container my-5">
    <div class="pagina-cabecera">
        <span class="etiqueta-categoria">CARTA</span>
        <h1 class="pagina-titulo h2 mt-2 mb-1">Productos</h1>
        <p class="pagina-subtitulo">Selecciona un plato para ver sus ingredientes y extras.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
        <?php foreach ($productos as $producto): ?>
            <div class="col">
                <div class="tarjeta-producto h-100">
                    <div class="foto-producto">
                        <img src="<?= url ?>public/img/productos/<?= texto($producto->getImagen()) ?>"
                             alt="<?= texto($producto->getNombre()) ?>" class="foto-ajustada card-img-top">
                    </div>
                    <div class="detalle-producto px-3 pt-3 pb-4 d-flex flex-column">
                        <h2 class="titulo-producto h5"><?= texto($producto->getNombre()) ?></h2>
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                            <span class="precio-producto fw-bold"><?= precio($producto->getPrecio()) ?></span>
                            <a href="<?= url ?>?controller=producto&action=detalle&id=<?= (int) $producto->getId() ?>"
                               class="btn boton-amarillo">VER</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

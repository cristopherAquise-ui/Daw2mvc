<div class="container my-5">
    <div class="pagina-cabecera">
        <span class="etiqueta-categoria">CARTA</span>
        <h1 class="pagina-titulo h2 mt-2 mb-1">Categorías</h1>
        <p class="pagina-subtitulo">Elige una categoría para ver sus platos.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
        <?php foreach ($categorias as $categoria): ?>
            <div class="col">
                <a href="<?= url ?>?controller=carta&categoria=<?= (int) $categoria->getId() ?>"
                   class="text-decoration-none">
                    <div class="tarjeta-panel h-100">
                        <h2 class="h5 text-white mb-2"><?= texto($categoria->getNombre()) ?></h2>
                        <p class="text-secondary mb-3"><?= texto($categoria->getDescripcion()) ?></p>
                        <span class="enlace-amarillo">Ver productos →</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

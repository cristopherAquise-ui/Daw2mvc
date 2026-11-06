<h1 class="text-warning mb-4">Nuestra Carta Galáctica</h1>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (empty($productos)) { ?>
        <p class="text-muted">No se encontraron productos en esta sección.</p>
    <?php } else { ?>
        <?php foreach ($productos as $producto) { ?>
            <div class="col">
                <div class="card card-galactica h-100 p-2">
                    <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                         class="card-img-top rounded" 
                         alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning fw-bold"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="card-text text-secondary flex-grow-1"><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 fw-bold text-light"><?= number_format((float)$producto->getPrecio(), 2, ',', '.') ?> €</span>
                            <a href="#" class="btn btn-starbites btn-sm">Añadir</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
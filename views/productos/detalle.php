<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
             class="img-fluid rounded shadow" 
             alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="col-md-6">
        <h2 class="text-warning fw-bold"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="fs-5 text-light"><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
        <h3 class="fw-bold mb-4"><?= number_format((float)$producto->getPrecio(), 2, ',', '.') ?> €</h3>

        <!-- Form carrito -->
        <form action="<?= url ?>?controller=carrito&action=add" method="POST">
            <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">
            <?php if (!empty($extras)) { ?>
                <div class="card bg-dark border-secondary mb-4">
                    <div class="card-header bg-secondary text-white fw-bold">
                        Personaliza tu plato
                    </div>
                    <div class="card-body">
                        <?php foreach ($extras as $extra) { ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="extras[]" value="<?= $extra->getId() ?>" id="extra_<?= $extra->getId() ?>">
                                <label class="form-check-label d-flex justify-content-between text-light w-100" for="extra_<?= $extra->getId() ?>">
                                    <span><?= htmlspecialchars($extra->getNombre(), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="text-warning">+<?= number_format((float)$extra->getPrecioExtra(), 2, ',', '.') ?> €</span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <button type="submit" class="btn btn-starbites btn-lg w-100">Añadir al Carrito</button>
        </form>
    </div>
</div>
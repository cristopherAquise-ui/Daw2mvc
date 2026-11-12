<!-- detalle del producto -->
<div class="container mt-4 mb-5">
    <!-- volver -->
    <div class="mb-4">
        <a href="<?= url ?>" class="boton-volver">
            <img src="<?= url ?>public/svg/icono/icono-atras.svg" alt="Volver" class="icono-volver-img me-1">
            Volver a la Carta
        </a>
    </div>

    <div class="row gx-5">
        <!-- foto -->
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>"
                class="img-fluid rounded-4 w-100"
                alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="col-md-6 text-white d-flex flex-column">

            <!-- categoria y titulo -->
            <div>
                <span class="etiqueta-categoria"><?= htmlspecialchars($categoria->getNombre(), ENT_QUOTES, 'UTF-8') ?></span>
                <h2 class="titulo-plato mt-3"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="mt-2 fs-6 text-light"><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
            </div>

            <hr class="linea-plato">

            <!-- form -->
            <form action="<?= url ?>?controller=carrito&action=add" method="POST" class="d-flex flex-column flex-grow-1">
                <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">

                <!-- extras -->
                <?php if (!empty($extras)) { ?>
                    <h4 class="mb-4 fw-bold">Personaliza tu plato</h4>
                    <div class="lista-opciones flex-grow-1">
                        <?php foreach ($extras as $extra) { ?>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <label class="d-flex align-items-center mb-0 w-100" style="cursor:pointer;" for="extra_<?= $extra->getId() ?>">
                                    <input class="form-check-input me-3 checkbox-extra"
                                        type="checkbox"
                                        name="extras[]"
                                        value="<?= $extra->getId() ?>"
                                        id="extra_<?= $extra->getId() ?>"
                                        data-precio="<?= (float)$extra->getPrecioExtra() ?>">
                                    <span class="fw-bold flex-grow-1"><?= htmlspecialchars($extra->getNombre(), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="text-secondary text-end texto-pequeno">
                                        <?php if ((float)$extra->getPrecioExtra() == 0): ?>
                                            Gratis
                                        <?php else: ?>
                                            +€<?= number_format((float)$extra->getPrecioExtra(), 2, '.', '') ?>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="flex-grow-1"></div>
                <?php } ?>

                <!-- precio y boton -->
                <div class="caja-compra mt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="d-flex flex-column text-center text-sm-start w-100">
                        <small class="text-uppercase text-white fw-bold texto-pequeno">PRECIO TOTAL</small>
                        <span id="precio-total-display" class="precio-total fw-bold fs-3">€<?= number_format((float)$producto->getPrecio(), 2, '.', '') ?></span>
                    </div>
                    <button type="submit" class="btn boton-comprar text-nowrap">AÑADIR AL CARRITO</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const precioBase = <?= (float)$producto->getPrecio() ?>;
        const checkboxes = document.querySelectorAll('.checkbox-extra');
        const displayTotal = document.getElementById('precio-total-display');

        function actualizarTotal() {
            let total = precioBase;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.precio) || 0;
                }
            });
            displayTotal.textContent = '€' + total.toFixed(2);
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', actualizarTotal);
        });
    });
</script>
<!-- detalle del producto -->
<div class="container mt-4 mb-5">
    <div class="mb-4">
        <a href="<?= url ?>?controller=carta" class="boton-volver">
            <img src="<?= url ?>public/svg/icono/icono-atras.svg" alt="Volver" class="icono-volver-img me-1">
            Volver a la Carta
        </a>
    </div>

    <div class="row gx-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <img src="<?= url ?>public/img/productos/<?= texto($producto->getImagen()) ?>"
                 class="img-fluid rounded-4 w-100"
                 alt="<?= texto($producto->getNombre()) ?>">
        </div>

        <div class="col-md-6 text-white d-flex flex-column">
            <div>
                <span class="etiqueta-categoria"><?= texto($categoria ? $categoria->getNombre() : 'Carta') ?></span>
                <h1 class="titulo-plato mt-3 h2"><?= texto($producto->getNombre()) ?></h1>
                <p class="mt-2 fs-6 text-light"><?= texto($producto->getDescripcion()) ?></p>

                <?php if (!empty($ofertas)): ?>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php foreach ($ofertas as $oferta): ?>
                            <span class="oferta-badge mb-0"><?= texto(Oferta::etiquetaTexto($oferta)) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <hr class="linea-plato">

            <form action="<?= url ?>?controller=carrito&action=add" method="POST" class="d-flex flex-column flex-grow-1">
                <input type="hidden" name="producto_id" value="<?= (int) $producto->getId() ?>">

                <?php if (!empty($base)): ?>
                    <h2 class="mb-3 fw-bold h5">Ingredientes incluidos</h2>
                    <p class="form-texto mb-3">Marca los que quieras quitar del plato.</p>

                    <div class="lista-opciones mb-4">
                        <?php foreach ($base as $ingrediente): ?>
                            <div class="d-flex justify-content-between mb-2 align-items-center">
                                <label class="d-flex align-items-center mb-0 w-100"
                                       style="cursor:pointer;" for="base_<?= (int) $ingrediente->getId() ?>">
                                    <input class="form-check-input me-3" type="checkbox"
                                           name="quitados[]" value="<?= (int) $ingrediente->getId() ?>"
                                           id="base_<?= (int) $ingrediente->getId() ?>"
                                           <?= (int) $ingrediente->getObligatorio() === 1 ? 'disabled' : '' ?>>
                                    <span class="flex-grow-1">
                                        <?= texto($ingrediente->getNombre()) ?>
                                        <?php if ((int) $ingrediente->getObligatorio() === 1): ?>
                                            <small class="text-secondary">(obligatorio)</small>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($extras)): ?>
                    <h2 class="mb-3 fw-bold h5">Personaliza tu plato</h2>
                    <div class="lista-opciones flex-grow-1">
                        <?php foreach ($extras as $extra): ?>
                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <label class="d-flex align-items-center mb-0 w-100"
                                       style="cursor:pointer;" for="extra_<?= (int) $extra->getId() ?>">
                                    <input class="form-check-input me-3 checkbox-extra"
                                           type="checkbox"
                                           name="extras[]"
                                           value="<?= (int) $extra->getId() ?>"
                                           id="extra_<?= (int) $extra->getId() ?>"
                                           data-precio="<?= (float) $extra->getPrecioExtra() ?>">
                                    <span class="fw-bold flex-grow-1"><?= texto($extra->getNombre()) ?></span>
                                    <span class="text-secondary text-end texto-pequeno">
                                        <?php if ((float) $extra->getPrecioExtra() == 0): ?>
                                            Gratis
                                        <?php else: ?>
                                            +<?= precio($extra->getPrecioExtra()) ?>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="flex-grow-1"></div>
                <?php endif; ?>

                <div class="caja-compra mt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="d-flex flex-column text-center text-sm-start w-100">
                        <small class="text-uppercase text-white fw-bold texto-pequeno">PRECIO TOTAL</small>
                        <span id="precio-total-display" class="precio-total fw-bold fs-3"><?= precio($producto->getPrecio()) ?></span>
                    </div>
                    <button type="submit" class="btn boton-comprar text-nowrap">AÑADIR AL CARRITO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const precioBase = <?= (float) $producto->getPrecio() ?>;
        const checkboxes = document.querySelectorAll('.checkbox-extra');
        const displayTotal = document.getElementById('precio-total-display');

        function actualizarTotal() {
            let total = precioBase;
            checkboxes.forEach(function (cb) {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.precio) || 0;
                }
            });
            displayTotal.textContent = total.toFixed(2).replace('.', ',') + ' €';
        }

        checkboxes.forEach(function (cb) {
            cb.addEventListener('change', actualizarTotal);
        });
    });
</script>

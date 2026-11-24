<div class="container my-5">
    <div class="pagina-cabecera text-center">
        <span class="etiqueta-categoria">OFERTAS</span>
        <h1 class="pagina-titulo display-5 mt-2">Ofertas de otra galaxia</h1>
        <p class="pagina-subtitulo">Descuentos, combos y códigos secretos para tu pedido.</p>
    </div>

    <?php if (empty($ofertas)): ?>
        <div class="tarjeta-panel text-center py-5">
            <p class="text-secondary mb-0">Ahora mismo no hay ofertas activas. Vuelve pronto.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($ofertas as $oferta): ?>
                <?php
                $tipo = $oferta->getTipo();
                $productos = $productosPorOferta[$oferta->getId()] ?? [];
                ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="oferta-card oferta-card--<?= texto($tipo) ?>">
                        <span class="oferta-badge"><?= texto(Oferta::etiquetaTexto($oferta)) ?></span>
                        <h2 class="oferta-nombre"><?= texto($oferta->getNombre()) ?></h2>
                        <p class="oferta-descripcion"><?= texto($oferta->getDescripcion()) ?></p>

                        <?php if ($tipo === 'codigo'): ?>
                            <div class="oferta-codigo"><?= texto($oferta->getCodigo()) ?></div>
                            <p class="text-secondary small mb-3">
                                Úsalo en el pago. Pedido mínimo: <?= precio($oferta->getImporteMinimo()) ?>.
                            </p>
                            <a href="<?= url ?>?controller=carta" class="btn boton-amarillo mt-auto">IR A LA CARTA</a>
                        <?php else: ?>
                            <p class="oferta-vigencia">
                                Válida hasta <?= $oferta->getFechaFin() ? fecha($oferta->getFechaFin()) : 'nuevo aviso' ?>.
                            </p>

                            <?php if (!empty($productos)): ?>
                                <form action="<?= url ?>?controller=oferta&action=agregar" method="POST" class="mt-auto">
                                    <input type="hidden" name="oferta_id" value="<?= (int) $oferta->getId() ?>">

                                    <label class="form-label text-light texto-pequeno mb-1" for="producto_<?= (int) $oferta->getId() ?>">
                                        Elige el producto
                                    </label>
                                    <select class="form-select campo-contacto mb-2"
                                            id="producto_<?= (int) $oferta->getId() ?>"
                                            name="producto_id" required>
                                        <?php foreach ($productos as $producto): ?>
                                            <option value="<?= (int) $producto->getId() ?>">
                                                <?= texto($producto->getNombre()) ?> — <?= precio($producto->getPrecio()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div class="d-flex gap-2">
                                        <input type="number" class="form-control campo-contacto" name="cantidad"
                                               value="1" min="1" max="20" style="max-width:90px;" aria-label="Cantidad">
                                        <button type="submit" class="btn boton-amarillo flex-grow-1">AÑADIR OFERTA</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <p class="text-secondary small mb-3">Oferta aplicable sobre los productos de la categoría.</p>
                                <a href="<?= url ?>?controller=carta" class="btn boton-amarillo mt-auto">VER PRODUCTOS</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

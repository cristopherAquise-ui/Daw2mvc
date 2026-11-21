<div class="container my-4">
    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <h3 class="fw-bold text-white mb-3">Tu carrito está vacío</h3>
            <p class="text-secondary mb-4">Explora nuestra carta galáctica y añade tus platos favoritos.</p>
            <a href="<?= url ?>?controller=carta" class="btn btn-proceder-pago">IR A LA CARTA</a>
        </div>
    <?php else: ?>
        <?php $impuestos = $totalCarrito * 0.10; ?>

        <div class="row justify-content-between g-5">
            <!-- COLUMNA IZQUIERDA: TU PEDIDO -->
            <div class="col-lg-7">
                <h2 class="fw-bold text-white mb-4">Tu Pedido</h2>

                <?php foreach ($items as $item): ?>
                    <div class="tarjeta-carrito d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-3">
                        <!-- Imagen + Nombre + Precio -->
                        <div class="d-flex align-items-center gap-3">
                            <?php if (!empty($item['imagen'])): ?>
                                <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($item['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($item['nombre_producto'], ENT_QUOTES, 'UTF-8') ?>"
                                     class="img-carrito">
                            <?php endif; ?>

                            <div>
                                <h5 class="fw-bold text-white mb-1">
                                    <?= htmlspecialchars($item['nombre_producto'], ENT_QUOTES, 'UTF-8') ?>
                                </h5>

                                <?php if (!empty($item['extras'])): ?>
                                    <div class="small text-secondary mb-1">
                                        <?php foreach ($item['extras'] as $extra): ?>
                                            <span class="d-block">+ <?= htmlspecialchars($extra['nombre_ingrediente'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="precio-carrito">
                                    €<?= number_format((float)$item['subtotal'], 2, '.', '') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Controles de cantidad (- / +) y Papelera -->
                        <div class="d-flex align-items-center justify-content-end gap-4 ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?= url ?>?controller=carrito&action=cantidad&id=<?= $item['id'] ?>&op=restar"
                                   class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                   title="Restar unidad">
                                    -
                                </a>

                                <span class="cantidad-carrito"><?= (int)$item['cantidad'] ?></span>

                                <a href="<?= url ?>?controller=carrito&action=cantidad&id=<?= $item['id'] ?>&op=sumar"
                                   class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                   title="Sumar unidad">
                                    +
                                </a>
                            </div>

                            <a href="<?= url ?>?controller=carrito&action=eliminar&id=<?= $item['id'] ?>"
                               class="btn-papelera text-decoration-none"
                               title="Eliminar producto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <a href="<?= url ?>?controller=carta" class="enlace-mas-productos mt-2">
                    + Añadir más productos
                </a>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN -->
            <div class="col-lg-4">
                <div class="mt-lg-2">
                    <h5 class="fw-bold text-white mb-3">Resumen</h5>
                    <hr class="linea-resumen">

                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary">Subtotal</span>
                        <span class="fw-bold text-white">€<?= number_format($totalCarrito, 2, '.', '') ?></span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 small">
                        <span class="text-secondary">Impuestos Galácticos (10%)</span>
                        <span class="fw-bold text-white">€<?= number_format($impuestos, 2, '.', '') ?></span>
                    </div>

                    <hr class="linea-resumen">

                    <div class="d-flex justify-content-between align-items-center my-4">
                        <span class="fw-bold text-white fs-5">TOTAL</span>
                        <span class="precio-carrito fs-4">€<?= number_format($totalCarrito, 2, '.', '') ?></span>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?= url ?>?controller=pedido&action=checkout" class="btn btn-proceder-pago">
                            PROCEDER AL PAGO
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
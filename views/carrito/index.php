<div class="container my-4">
    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <h1 class="fw-bold text-white mb-3 h3">Tu carrito está vacío</h1>
            <p class="text-secondary mb-4">Explora nuestra carta galáctica y añade tus platos favoritos.</p>
            <a href="<?= url ?>?controller=carta" class="btn boton-amarillo">IR A LA CARTA</a>
        </div>
    <?php else: ?>
        <div class="row justify-content-between g-5">
            <!-- COLUMNA IZQUIERDA: TU PEDIDO -->
            <div class="col-lg-7">
                <h1 class="fw-bold text-white mb-4 h3">Tu Pedido</h1>

                <?php foreach ($items as $item): ?>
                    <div class="tarjeta-carrito d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <?php if (!empty($item['imagen'])): ?>
                                <img src="<?= url ?>public/img/productos/<?= texto($item['imagen']) ?>"
                                     alt="<?= texto($item['nombre_producto']) ?>" class="img-carrito">
                            <?php endif; ?>

                            <div>
                                <h2 class="fw-bold text-white mb-1 h5"><?= texto($item['nombre_producto']) ?></h2>

                                <?php if (!empty($item['extras'])): ?>
                                    <div class="small text-secondary mb-1">
                                        <?php foreach ($item['extras'] as $extra): ?>
                                            <span class="d-block">+ <?= texto($extra['nombre_ingrediente']) ?>
                                                <?php if ((float) $extra['precio_extra'] > 0): ?>
                                                    (<?= precio($extra['precio_extra']) ?>)
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['quitados'])): ?>
                                    <div class="small text-secondary mb-1">
                                        <?php foreach ($item['quitados'] as $quitado): ?>
                                            <span class="d-block text-decoration-line-through">Sin <?= texto($quitado['nombre_ingrediente']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="precio-carrito"><?= precio($item['subtotal']) ?></div>

                                <?php if ((float) $item['descuento'] > 0): ?>
                                    <div class="small text-warning">Descuento aplicado: -<?= precio($item['descuento']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end gap-4 ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <form action="<?= url ?>?controller=carrito&action=cantidad" method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <input type="hidden" name="op" value="restar">
                                    <button type="submit" class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                            title="Restar unidad">-</button>
                                </form>

                                <span class="cantidad-carrito"><?= (int) $item['cantidad'] ?></span>

                                <form action="<?= url ?>?controller=carrito&action=cantidad" method="POST" class="m-0">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <input type="hidden" name="op" value="sumar">
                                    <button type="submit" class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas"
                                            title="Sumar unidad">+</button>
                                </form>
                            </div>

                            <form action="<?= url ?>?controller=carrito&action=eliminar" method="POST" class="m-0">
                                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                <button type="submit" class="btn-papelera text-decoration-none border-0 bg-transparent" title="Eliminar producto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </form>
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
                    <h2 class="fw-bold text-white mb-3 h5">Resumen</h2>
                    <hr class="linea-resumen">

                    <div class="resumen-fila">
                        <span>Subtotal</span>
                        <span class="text-white fw-bold"><?= precio($totales['subtotal']) ?></span>
                    </div>

                    <?php if ($totales['descuento'] > 0): ?>
                        <div class="resumen-fila">
                            <span>Descuentos</span>
                            <span class="text-white fw-bold">-<?= precio($totales['descuento']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="resumen-fila">
                        <span>Base imponible</span>
                        <span class="text-white fw-bold"><?= precio($totales['base_imponible']) ?></span>
                    </div>

                    <div class="resumen-fila">
                        <span>IVA incluido</span>
                        <span class="text-white fw-bold"><?= precio($totales['iva']) ?></span>
                    </div>

                    <hr class="linea-resumen">

                    <div class="resumen-fila resumen-fila--total">
                        <span>TOTAL</span>
                        <span class="precio-carrito"><?= precio($totales['total']) ?></span>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?= url ?>?controller=carrito&action=pago" class="btn btn-proceder-pago">
                            PROCEDER AL PAGO
                        </a>
                    </div>

                    <p class="form-texto text-center mt-3 mb-0">Los precios incluyen IVA.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

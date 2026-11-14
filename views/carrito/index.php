<div class="container mt-4 mb-5">
    <div class="mb-4">
        <a href="<?= url ?>?controller=carta" class="boton-volver">
            <img src="<?= url ?>public/svg/icono/icono-atras.svg" alt="Volver" class="icono-volver-img me-1">
            Seguir pidiendo
        </a>
    </div>

    <h2 class="titulo-plato mb-4">Tu Pedido Galáctico</h2>

    <?php if (empty($items)): ?>
        <div class="text-center py-5 border border-secondary rounded-4">
            <p class="fs-5 text-light mb-4">Aún no has añadido ningún plato a tu carrito.</p>
            <a href="<?= url ?>?controller=carta" class="btn boton-comprar">EXPLORAR LA CARTA</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Lista de platos -->
            <div class="col-lg-8">
                <?php foreach ($items as $item): ?>
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 mb-3 border border-secondary rounded-4 bg-dark bg-opacity-50 gap-3">
                        <div class="d-flex align-items-center gap-3 w-100">
                            <?php if (!empty($item['imagen'])): ?>
                                <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($item['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($item['nombre_producto'], ENT_QUOTES, 'UTF-8') ?>"
                                     class="rounded-3" style="width: 85px; height: 85px; object-fit: cover;">
                            <?php endif; ?>

                            <div>
                                <h5 class="fw-bold mb-1 text-white"><?= htmlspecialchars($item['nombre_producto'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <small class="text-secondary d-block">Base: €<?= number_format((float)$item['precio_unitario'], 2, '.', '') ?></small>

                                <?php if (!empty($item['extras'])): ?>
                                    <ul class="list-unstyled mb-0 mt-1 small text-warning">
                                        <?php foreach ($item['extras'] as $extra): ?>
                                            <li>+ <?= htmlspecialchars($extra['nombre_ingrediente'], ENT_QUOTES, 'UTF-8') ?>
                                                (<?= (float)$extra['precio_extra'] > 0 ? '+€' . number_format((float)$extra['precio_extra'], 2, '.', '') : 'Gratis' ?>)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Controles de cantidad, subtotal y eliminar -->
                        <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 w-100 w-sm-auto">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?= url ?>?controller=carrito&action=cantidad&id=<?= $item['id'] ?>&op=restar" class="btn btn-outline-warning fw-bold">-</a>
                                <span class="btn btn-dark disabled text-white fw-bold px-3"><?= (int)$item['cantidad'] ?></span>
                                <a href="<?= url ?>?controller=carrito&action=cantidad&id=<?= $item['id'] ?>&op=sumar" class="btn btn-outline-warning fw-bold">+</a>
                            </div>

                            <span class="fw-bold fs-5 text-white text-nowrap">€<?= number_format((float)$item['subtotal'], 2, '.', '') ?></span>

                            <a href="<?= url ?>?controller=carrito&action=eliminar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar plato">
                                &times;
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumen total -->
            <div class="col-lg-4">
                <div class="caja-compra p-4 rounded-4 border border-secondary">
                    <h4 class="fw-bold mb-3 text-white">Resumen</h4>
                    <div class="d-flex justify-content-between mb-2 text-light">
                        <span>Subtotal (IVA incluido)</span>
                        <span>€<?= number_format($totalCarrito, 2, '.', '') ?></span>
                    </div>
                    <hr class="linea-plato">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold text-uppercase">Total</span>
                        <span class="precio-total fw-bold fs-3">€<?= number_format($totalCarrito, 2, '.', '') ?></span>
                    </div>
                    <a href="<?= url ?>?controller=pedido&action=checkout" class="btn boton-comprar w-100 text-center">TRAMITAR PEDIDO</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
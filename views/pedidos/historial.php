<div class="container my-5">
    <div class="pagina-cabecera d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <span class="etiqueta-categoria">HISTORIAL</span>
            <h1 class="pagina-titulo h2 mt-2 mb-1">Mis pedidos</h1>
            <p class="pagina-subtitulo">Todos los pedidos realizados con tu cuenta.</p>
        </div>
        <a href="<?= url ?>?controller=usuario&action=panel" class="btn boton-volver">VOLVER A MI CUENTA</a>
    </div>

    <?php if (empty($pedidos)): ?>
        <div class="tarjeta-panel text-center py-5">
            <p class="text-secondary mb-4">Todavía no has realizado ningún pedido.</p>
            <a href="<?= url ?>?controller=carta" class="btn boton-amarillo">EXPLORAR LA CARTA</a>
        </div>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <?php $lineas = $detalles[$pedido->getId()] ?? []; ?>
            <div class="pedido-caja">
                <div class="pedido-cabecera mb-2">
                    <h2 class="h5">Pedido #<?= (int) $pedido->getId() ?></h2>
                    <span class="estado-pedido estado-pedido--<?= texto($pedido->getEstado()) ?>">
                        <?= texto(estadoPedido($pedido->getEstado())) ?>
                    </span>
                    <span class="text-secondary small"><?= fechaHora($pedido->getFechaPedido()) ?></span>
                    <span class="ms-auto fw-bold text-white"><?= precio($pedido->getTotal()) ?></span>
                </div>

                <div class="mb-3">
                    <?php foreach (array_slice($lineas, 0, 3) as $fila): ?>
                        <div class="pedido-linea small">
                            <span><?= (int) $fila['linea']->getCantidad() ?> × <?= texto($fila['linea']->getNombreProducto()) ?></span>
                            <span><?= precio($fila['linea']->getSubtotal()) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <?php if (count($lineas) > 3): ?>
                        <p class="text-secondary small mt-2 mb-0">Y <?= count($lineas) - 3 ?> línea(s) más.</p>
                    <?php endif; ?>
                </div>

                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="<?= url ?>?controller=pedido&action=detalle&id=<?= (int) $pedido->getId() ?>"
                       class="enlace-amarillo">Ver detalle completo</a>
                    <span class="text-secondary small">Pago: <?= texto(ucfirst((string) $pedido->getMetodoPago())) ?> · <?= texto($pedido->getEstadoPago()) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

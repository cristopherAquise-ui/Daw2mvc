<div class="container my-5">
    <div class="mb-4">
        <a href="<?= url ?>?controller=pedido&action=historial" class="boton-volver">
            <img src="<?= url ?>public/svg/icono/icono-atras.svg" alt="Volver" class="icono-volver-img me-1">
            Volver al historial
        </a>
    </div>

    <div class="pagina-cabecera d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <span class="etiqueta-categoria">PEDIDO</span>
            <h1 class="pagina-titulo h2 mt-2 mb-1">Pedido #<?= (int) $pedido->getId() ?></h1>
            <p class="pagina-subtitulo"><?= fechaHora($pedido->getFechaPedido()) ?></p>
        </div>
        <span class="estado-pedido estado-pedido--<?= texto($pedido->getEstado()) ?>">
            <?= texto(estadoPedido($pedido->getEstado())) ?>
        </span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="tarjeta-panel mb-4">
                <h2 class="h5 mb-3">Líneas del pedido</h2>

                <?php foreach ($detalles as $fila): ?>
                    <?php $linea = $fila['linea']; ?>
                    <div class="pedido-linea">
                        <div>
                            <strong class="text-white">
                                <?= (int) $linea->getCantidad() ?> × <?= texto($linea->getNombreProducto()) ?>
                            </strong>
                            <div class="text-secondary small">
                                <?= precio($linea->getPrecioUnitario()) ?> / unidad · IVA <?= (float) $linea->getIvaPorcentaje() ?>%
                            </div>

                            <?php if (!empty($fila['mods'])): ?>
                                <ul class="pedido-mods">
                                    <?php foreach ($fila['mods'] as $mod): ?>
                                        <li class="<?= $mod['accion'] === 'quitado' ? 'quitado' : ($mod['accion'] === 'añadido' ? 'anadido' : '') ?>">
                                            <?= $mod['accion'] === 'quitado' ? 'Sin ' : ($mod['accion'] === 'añadido' ? '+ ' : '') ?>
                                            <?= texto($mod['nombre_ingrediente']) ?>
                                            <?php if ((float) $mod['precio_extra'] > 0 && $mod['accion'] === 'añadido'): ?>
                                                (+<?= precio($mod['precio_extra']) ?>)
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <span class="text-white"><?= precio($linea->getSubtotal()) ?></span>
                            <?php if ((float) $linea->getDescuento() > 0): ?>
                                <div class="text-secondary small">Dto. -<?= precio($linea->getDescuento()) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="resumen-fila mt-3">
                    <span>Subtotal</span>
                    <span><?= precio($pedido->getSubtotal()) ?></span>
                </div>
                <?php if ((float) $pedido->getDescuento() > 0): ?>
                    <div class="resumen-fila">
                        <span>Descuento</span>
                        <span>-<?= precio($pedido->getDescuento()) ?></span>
                    </div>
                <?php endif; ?>
                <div class="resumen-fila">
                    <span>Base imponible</span>
                    <span><?= precio($pedido->getBaseImponible()) ?></span>
                </div>
                <div class="resumen-fila">
                    <span>IVA incluido</span>
                    <span><?= precio($pedido->getIva()) ?></span>
                </div>
                <div class="resumen-fila resumen-fila--total">
                    <span>TOTAL</span>
                    <span class="precio-carrito"><?= precio($pedido->getTotal()) ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="tarjeta-panel mb-4">
                <h2 class="h6 mb-3">Entrega</h2>
                <p class="text-secondary mb-1"><strong class="text-white">Cliente:</strong> <?= texto($cliente ? $cliente->getNombreCompleto() : '—') ?></p>
                <p class="text-secondary mb-1"><strong class="text-white">Dirección:</strong> <?= dato($pedido->getDireccionEntrega()) ?></p>
                <p class="text-secondary mb-1"><strong class="text-white">Teléfono:</strong> <?= dato($pedido->getTelefonoContacto()) ?></p>
                <p class="text-secondary mb-0"><strong class="text-white">Notas:</strong> <?= dato($pedido->getNotas()) ?></p>
            </div>

            <div class="tarjeta-panel">
                <h2 class="h6 mb-3">Pago</h2>
                <p class="text-secondary mb-1"><strong class="text-white">Método:</strong> <?= texto(ucfirst((string) $pedido->getMetodoPago())) ?></p>
                <p class="text-secondary mb-1"><strong class="text-white">Estado:</strong> <?= texto($pedido->getEstadoPago()) ?></p>
                <p class="text-secondary mb-0"><strong class="text-white">Actualizado:</strong> <?= fechaHora($pedido->getFechaActualizacion()) ?></p>
            </div>
        </div>
    </div>
</div>

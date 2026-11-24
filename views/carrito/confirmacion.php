<div class="container my-5">
    <div class="pagina-cabecera text-center">
        <ol class="pasos">
            <li class="paso"><span class="paso__numero">1</span> Carrito</li>
            <li class="paso"><span class="paso__numero">2</span> Pago</li>
            <li class="paso activo"><span class="paso__numero">3</span> Confirmación</li>
        </ol>

        <div class="display-5 mb-2" aria-hidden="true">🚀</div>
        <h1 class="pagina-titulo h2">¡Pedido confirmado!</h1>
        <p class="pagina-subtitulo">
            Gracias, <?= texto($usuario->getNombre()) ?>. Tu pedido
            <strong class="text-white">#<?= (int) $pedido->getId() ?></strong> ya está en órbita.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="tarjeta-panel mb-4">
                <div class="pedido-cabecera mb-3">
                    <h2 class="h5">Pedido #<?= (int) $pedido->getId() ?></h2>
                    <span class="estado-pedido estado-pedido--<?= texto($pedido->getEstado()) ?>">
                        <?= texto(estadoPedido($pedido->getEstado())) ?>
                    </span>
                    <span class="text-secondary small ms-auto"><?= fechaHora($pedido->getFechaPedido()) ?></span>
                </div>

                <?php foreach ($detalles as $fila): ?>
                    <?php $linea = $fila['linea']; ?>
                    <div class="pedido-linea">
                        <div>
                            <strong class="text-white"><?= (int) $linea->getCantidad() ?> × <?= texto($linea->getNombreProducto()) ?></strong>
                            <?php if (!empty($fila['mods'])): ?>
                                <ul class="pedido-mods">
                                    <?php foreach ($fila['mods'] as $mod): ?>
                                        <li class="<?= $mod['accion'] === 'quitado' ? 'quitado' : ($mod['accion'] === 'añadido' ? 'anadido' : '') ?>">
                                            <?= $mod['accion'] === 'quitado' ? 'Sin ' : ($mod['accion'] === 'añadido' ? '+ ' : '') ?>
                                            <?= texto($mod['nombre_ingrediente']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <span class="text-white"><?= precio($linea->getSubtotal()) ?></span>
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

            <div class="tarjeta-panel mb-4">
                <h2 class="h6 mb-3">Entrega</h2>
                <p class="text-secondary mb-1"><?= dato($pedido->getDireccionEntrega()) ?></p>
                <p class="text-secondary mb-1">Teléfono: <?= dato($pedido->getTelefonoContacto()) ?></p>
                <p class="text-secondary mb-0">Pago: <?= texto(ucfirst((string) $pedido->getMetodoPago())) ?> (<?= texto($pedido->getEstadoPago()) ?>)</p>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                <a href="<?= url ?>?controller=pedido&action=detalle&id=<?= (int) $pedido->getId() ?>"
                   class="btn boton-amarillo">VER DETALLE DEL PEDIDO</a>
                <a href="<?= url ?>?controller=carta" class="btn boton-volver">SEGUIR PIDIENDO</a>
            </div>
        </div>
    </div>
</div>

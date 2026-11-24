<div class="container my-5">
    <div class="pagina-cabecera text-center">
        <h1 class="pagina-titulo h2 mb-3">Finalizar pedido</h1>

        <ol class="pasos">
            <li class="paso"><span class="paso__numero">1</span> Carrito</li>
            <li class="paso activo"><span class="paso__numero">2</span> Pago</li>
            <li class="paso"><span class="paso__numero">3</span> Confirmación</li>
        </ol>
    </div>

    <form action="<?= url ?>?controller=carrito&action=confirmar" method="POST" novalidate>
        <input type="hidden" name="codigo" value="<?= $codigoValido ? texto($codigo) : '' ?>">

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="tarjeta-formulario mb-4">
                    <h2 class="h5 mb-3">Datos de entrega</h2>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="telefono_contacto" class="form-label text-light texto-pequeno mb-1">Teléfono *</label>
                            <input type="tel" class="form-control campo-contacto" id="telefono_contacto"
                                   name="telefono_contacto" value="<?= texto($usuario->getTelefono()) ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="direccion_entrega" class="form-label text-light texto-pequeno mb-1">Dirección *</label>
                            <input type="text" class="form-control campo-contacto" id="direccion_entrega"
                                   name="direccion_entrega" value="<?= texto($usuario->getDireccion()) ?>" required>
                        </div>

                        <div class="col-12">
                            <label for="notas" class="form-label text-light texto-pequeno mb-1">Notas para el restaurante</label>
                            <textarea class="form-control campo-contacto" id="notas" name="notas" rows="3"
                                      placeholder="Alergias, indicaciones de entrega..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="tarjeta-formulario">
                    <h2 class="h5 mb-3">Método de pago</h2>

                    <?php foreach ($metodosPago as $clave => $etiqueta): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metodo_pago"
                                   id="metodo_<?= texto($clave) ?>" value="<?= texto($clave) ?>"
                                   <?= $clave === 'simulado' ? 'checked' : '' ?>>
                            <label class="form-check-label text-light" for="metodo_<?= texto($clave) ?>">
                                <?= texto($etiqueta) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <p class="form-texto mb-0">
                        El pago es simulado: no se cobra ninguna tarjeta real. Los pedidos en efectivo o
                        transferencia quedan pendientes de pago.
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="tarjeta-resumen mb-4">
                    <h2 class="h5 mb-3">Tu pedido</h2>

                    <?php foreach ($items as $item): ?>
                        <div class="pedido-linea">
                            <span class="text-light">
                                <?= (int) $item['cantidad'] ?> × <?= texto($item['nombre_producto']) ?>
                            </span>
                            <span class="text-white"><?= precio($item['subtotal']) ?></span>
                        </div>
                    <?php endforeach; ?>

                    <div class="resumen-fila mt-3">
                        <span>Subtotal</span>
                        <span><?= precio($totales['subtotal']) ?></span>
                    </div>

                    <?php if ($totales['descuento'] > 0): ?>
                        <div class="resumen-fila">
                            <span>Descuentos aplicados</span>
                            <span>-<?= precio($totales['descuento']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="resumen-fila">
                        <span>Base imponible</span>
                        <span><?= precio($totales['base_imponible']) ?></span>
                    </div>

                    <div class="resumen-fila">
                        <span>IVA incluido</span>
                        <span><?= precio($totales['iva']) ?></span>
                    </div>

                    <?php if ($descuentoCodigo > 0): ?>
                        <div class="resumen-fila">
                            <span>Código <?= texto($codigo) ?></span>
                            <span>-<?= precio($descuentoCodigo) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="resumen-fila resumen-fila--total">
                        <span>TOTAL</span>
                        <span class="precio-carrito"><?= precio($totalConDescuento) ?></span>
                    </div>
                </div>

                <div class="tarjeta-resumen mb-4">
                    <h2 class="h6 mb-2">Código promocional</h2>

                    <?php if ($mensajeCodigo !== ''): ?>
                        <p class="<?= $codigoValido ? 'text-warning' : 'text-danger' ?> small mb-2">
                            <?= texto($mensajeCodigo) ?>
                        </p>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <input type="text" class="form-control campo-contacto" name="codigo"
                               form="form-codigo" value="<?= texto($codigo) ?>" placeholder="Ej. GALAXIA10"
                               aria-label="Código promocional">
                        <button type="submit" form="form-codigo" class="btn boton-amarillo">APLICAR</button>
                    </div>

                    <p class="form-texto mt-2 mb-0">Se comprobará al confirmar el pedido.</p>
                </div>

                <button type="submit" class="btn boton-amarillo w-100">CONFIRMAR Y PAGAR</button>
                <a href="<?= url ?>?controller=carrito" class="enlace-amarillo d-block text-center mt-3">
                    Volver al carrito
                </a>
            </div>
        </div>
    </form>

    <form id="form-codigo" action="<?= url ?>?controller=carrito&action=pago" method="GET" class="d-none">
        <input type="hidden" name="controller" value="carrito">
        <input type="hidden" name="action" value="pago">
    </form>
</div>

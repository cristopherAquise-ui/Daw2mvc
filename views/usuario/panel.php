<?php
$nombreCompleto = $usuario->getNombreCompleto();
$iniciales = strtoupper(substr($usuario->getNombre() ?? 'U', 0, 1) . substr($usuario->getApellidos() ?? '', 0, 1));
?>
<div class="container my-5">
    <div class="pagina-cabecera d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <span class="etiqueta-categoria">MI CUENTA</span>
            <h1 class="pagina-titulo h2 mt-2 mb-1">Hola, <?= texto($usuario->getNombre()) ?></h1>
            <p class="pagina-subtitulo">Desde aquí puedes revisar tus pedidos y actualizar tus datos.</p>
        </div>
        <a href="<?= url ?>?controller=pedido&action=historial" class="btn boton-amarillo">VER HISTORIAL</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="tarjeta-panel mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:56px;height:56px;background:#f6d645;color:#000;font-weight:800;">
                        <?= texto($iniciales) ?>
                    </div>
                    <div>
                        <h2 class="h5 mb-0"><?= texto($nombreCompleto) ?></h2>
                        <small class="text-secondary"><?= texto($usuario->getEmail()) ?></small>
                    </div>
                </div>

                <div class="resumen-fila">
                    <span>Pedidos realizados</span>
                    <strong class="text-white"><?= (int) $resumen['num'] ?></strong>
                </div>
                <div class="resumen-fila">
                    <span>Total gastado</span>
                    <strong class="text-white"><?= precio($resumen['gastado']) ?></strong>
                </div>
                <div class="resumen-fila">
                    <span>Último estado</span>
                    <strong class="text-white"><?= $resumen['ultimo'] ? texto(estadoPedido($resumen['ultimo'])) : '—' ?></strong>
                </div>
                <div class="resumen-fila">
                    <span>Rol</span>
                    <strong class="text-white"><?= $usuario->esAdmin() ? 'Administrador' : 'Cliente' ?></strong>
                </div>
            </div>

            <?php if ($ultimo): ?>
                <div class="tarjeta-panel">
                    <h2 class="h5 mb-3">Último pedido</h2>
                    <div class="pedido-cabecera mb-2">
                        <span class="fw-bold text-white">#<?= (int) $ultimo->getId() ?></span>
                        <span class="estado-pedido estado-pedido--<?= texto($ultimo->getEstado()) ?>">
                            <?= texto(estadoPedido($ultimo->getEstado())) ?>
                        </span>
                    </div>
                    <p class="text-secondary small mb-2"><?= fechaHora($ultimo->getFechaPedido()) ?></p>
                    <div class="resumen-fila resumen-fila--total">
                        <span>Total</span>
                        <span class="precio-carrito"><?= precio($ultimo->getTotal()) ?></span>
                    </div>
                    <a href="<?= url ?>?controller=pedido&action=detalle&id=<?= (int) $ultimo->getId() ?>"
                       class="btn boton-amarillo w-100 mt-3">VER PEDIDO</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-8">
            <div class="tarjeta-panel mb-4">
                <h2 class="h5 mb-3">Mis datos</h2>

                <form action="<?= url ?>?controller=usuario&action=actualizar" method="POST" novalidate>

                    <div class="admin-form-grid">
                        <div>
                            <label for="nombre" class="form-label text-light texto-pequeno mb-1">Nombre *</label>
                            <input type="text" class="form-control campo-contacto" id="nombre" name="nombre"
                                   value="<?= texto($usuario->getNombre()) ?>" required>
                        </div>
                        <div>
                            <label for="apellidos" class="form-label text-light texto-pequeno mb-1">Apellidos</label>
                            <input type="text" class="form-control campo-contacto" id="apellidos" name="apellidos"
                                   value="<?= texto($usuario->getApellidos()) ?>">
                        </div>
                        <div>
                            <label for="email" class="form-label text-light texto-pequeno mb-1">Email *</label>
                            <input type="email" class="form-control campo-contacto" id="email" name="email"
                                   value="<?= texto($usuario->getEmail()) ?>" required>
                        </div>
                        <div>
                            <label for="telefono" class="form-label text-light texto-pequeno mb-1">Teléfono</label>
                            <input type="tel" class="form-control campo-contacto" id="telefono" name="telefono"
                                   value="<?= texto($usuario->getTelefono()) ?>">
                        </div>
                        <div class="ancho-completo">
                            <label for="direccion" class="form-label text-light texto-pequeno mb-1">Dirección</label>
                            <input type="text" class="form-control campo-contacto" id="direccion" name="direccion"
                                   value="<?= texto($usuario->getDireccion()) ?>">
                        </div>
                        <div>
                            <label for="ciudad" class="form-label text-light texto-pequeno mb-1">Ciudad</label>
                            <input type="text" class="form-control campo-contacto" id="ciudad" name="ciudad"
                                   value="<?= texto($usuario->getCiudad()) ?>">
                        </div>
                        <div>
                            <label for="codigo_postal" class="form-label text-light texto-pequeno mb-1">Código postal</label>
                            <input type="text" class="form-control campo-contacto" id="codigo_postal" name="codigo_postal"
                                   value="<?= texto($usuario->getCodigoPostal()) ?>">
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="form-label text-light texto-pequeno mb-1">Fecha de nacimiento</label>
                            <input type="date" class="form-control campo-contacto" id="fecha_nacimiento" name="fecha_nacimiento"
                                   value="<?= texto($usuario->getFechaNacimiento()) ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn boton-amarillo mt-4">GUARDAR CAMBIOS</button>
                </form>
            </div>

            <div class="tarjeta-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Pedidos recientes</h2>
                    <a href="<?= url ?>?controller=pedido&action=historial" class="enlace-amarillo small">Ver todos</a>
                </div>

                <?php if (empty($pedidos)): ?>
                    <p class="text-secondary mb-0">Todavía no has realizado ningún pedido.</p>
                <?php else: ?>
                    <?php foreach (array_slice($pedidos, 0, 4) as $pedido): ?>
                        <?php $lineas = $detalles[$pedido->getId()] ?? []; ?>
                        <div class="pedido-caja">
                            <div class="pedido-cabecera mb-2">
                                <h3 class="h6">Pedido #<?= (int) $pedido->getId() ?></h3>
                                <span class="estado-pedido estado-pedido--<?= texto($pedido->getEstado()) ?>">
                                    <?= texto(estadoPedido($pedido->getEstado())) ?>
                                </span>
                                <span class="text-secondary small ms-auto"><?= fechaHora($pedido->getFechaPedido()) ?></span>
                            </div>

                            <?php foreach (array_slice($lineas, 0, 2) as $fila): ?>
                                <div class="pedido-linea small">
                                    <span><?= (int) $fila['linea']->getCantidad() ?> × <?= texto($fila['linea']->getNombreProducto()) ?></span>
                                    <span><?= precio($fila['linea']->getSubtotal()) ?></span>
                                </div>
                            <?php endforeach; ?>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <strong class="text-white"><?= precio($pedido->getTotal()) ?></strong>
                                <a href="<?= url ?>?controller=pedido&action=detalle&id=<?= (int) $pedido->getId() ?>"
                                   class="enlace-amarillo small">Ver detalle</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

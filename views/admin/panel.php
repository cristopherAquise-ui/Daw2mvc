<div class="container-fluid my-4 px-3 px-lg-4">
    <div class="pagina-cabecera d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
        <div>
            <span class="etiqueta-categoria">ADMINISTRACIÓN</span>
            <h1 class="pagina-titulo h2 mt-2 mb-1">Panel de control</h1>
            <p class="pagina-subtitulo">
                Gestiona la carta, las ofertas, los pedidos y los usuarios de Star Bites.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url ?>?controller=carta" class="btn boton-volver">VER WEB</a>
            <a href="<?= url ?>?controller=usuario&action=logout" class="btn boton-volver">CERRAR SESIÓN</a>
        </div>
    </div>

    <div id="admin-aviso" class="alert alert-warning d-none" role="status"></div>

    <nav class="admin-tabs mb-4" aria-label="Secciones del panel">
        <button type="button" class="admin-tab activo" data-vista="resumen">Resumen</button>
        <button type="button" class="admin-tab" data-vista="productos">Productos</button>
        <button type="button" class="admin-tab" data-vista="categorias">Categorías</button>
        <button type="button" class="admin-tab" data-vista="ingredientes">Ingredientes</button>
        <button type="button" class="admin-tab" data-vista="ofertas">Ofertas</button>
        <button type="button" class="admin-tab" data-vista="pedidos">Pedidos</button>
        <button type="button" class="admin-tab" data-vista="usuarios">Usuarios</button>
    </nav>

    <!-- RESUMEN -->
    <section id="vista-resumen" class="admin-vista">
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="admin-kpi">
                    <span class="admin-kpi__label">Productos activos</span>
                    <strong class="admin-kpi__valor" id="kpi-productos">—</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-kpi">
                    <span class="admin-kpi__label">Pedidos</span>
                    <strong class="admin-kpi__valor" id="kpi-pedidos">—</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-kpi">
                    <span class="admin-kpi__label">Usuarios activos</span>
                    <strong class="admin-kpi__valor" id="kpi-usuarios">—</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-kpi">
                    <span class="admin-kpi__label">Ventas</span>
                    <strong class="admin-kpi__valor" id="kpi-ventas">—</strong>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6 col-lg-3">
                <div class="admin-kpi admin-kpi--secundario">
                    <span class="admin-kpi__label">Ofertas activas</span>
                    <strong class="admin-kpi__valor" id="kpi-ofertas">—</strong>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="admin-kpi admin-kpi--secundario">
                    <span class="admin-kpi__label">Pedidos pendientes</span>
                    <strong class="admin-kpi__valor" id="kpi-pendientes">—</strong>
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex align-items-center">
                <p class="text-secondary mb-0">
                    Los datos se actualizan mediante la API asíncrona, sin recargar la página.
                    Usa las pestañas para gestionar cada sección.
                </p>
            </div>
        </div>
    </section>

    <!-- PRODUCTOS -->
    <section id="vista-productos" class="admin-vista" hidden>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 text-white mb-0">Productos</h2>
            <button type="button" class="btn boton-amarillo" data-nuevo="producto">NUEVO PRODUCTO</button>
        </div>

        <form id="filtro-productos" class="admin-filtros">
            <div>
                <label for="filtro-producto-nombre">Nombre</label>
                <input type="search" id="filtro-producto-nombre" name="nombre" placeholder="Buscar...">
            </div>
            <div>
                <label for="filtro-producto-categoria">Categoría</label>
                <select id="filtro-producto-categoria" name="categoria_id">
                    <option value="">Todas</option>
                </select>
            </div>
            <div>
                <label for="filtro-producto-activo">Estado</label>
                <select id="filtro-producto-activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div>
                <label for="filtro-producto-order">Ordenar por</label>
                <select id="filtro-producto-order" name="order">
                    <option value="id">ID</option>
                    <option value="nombre">Nombre</option>
                    <option value="precio">Precio</option>
                    <option value="categoria_id">Categoría</option>
                </select>
            </div>
            <div>
                <label for="filtro-producto-dir">Dirección</label>
                <select id="filtro-producto-dir" name="dir">
                    <option value="ASC">Ascendente</option>
                    <option value="DESC">Descendente</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button type="submit" class="btn boton-amarillo w-100">FILTRAR</button>
            </div>
        </form>

        <div id="panel-producto" class="tarjeta-panel mb-4" hidden>
            <h3 class="h6 mb-3" id="titulo-form-producto">Nuevo producto</h3>
            <form id="form-producto">
                <input type="hidden" name="id" value="">
                <div class="admin-form-grid">
                    <div>
                        <label for="producto-nombre">Nombre *</label>
                        <input type="text" id="producto-nombre" name="nombre" required>
                    </div>
                    <div>
                        <label for="producto-categoria">Categoría *</label>
                        <select id="producto-categoria" name="categoria_id" required></select>
                    </div>
                    <div class="ancho-completo">
                        <label for="producto-descripcion">Descripción</label>
                        <textarea id="producto-descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <div>
                        <label for="producto-precio">Precio (€) *</label>
                        <input type="number" id="producto-precio" name="precio" step="0.01" min="0" required>
                    </div>
                    <div>
                        <label for="producto-iva">IVA (%)</label>
                        <input type="number" id="producto-iva" name="iva_porcentaje" step="0.01" min="0" value="10">
                    </div>
                    <div>
                        <label for="producto-imagen">Imagen</label>
                        <input type="text" id="producto-imagen" name="imagen" placeholder="entrantes/archivo.jpg">
                    </div>
                    <div>
                        <label for="producto-orden">Orden</label>
                        <input type="number" id="producto-orden" name="orden" min="0" value="0">
                    </div>
                    <div>
                        <label for="producto-activo">Activo</label>
                        <select id="producto-activo" name="activo">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div>
                        <label for="producto-destacado">Destacado</label>
                        <select id="producto-destacado" name="destacado">
                            <option value="0">No</option>
                            <option value="1">Sí</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn boton-amarillo">GUARDAR</button>
                    <button type="button" class="btn boton-volver" data-cancelar="producto">CANCELAR</button>
                </div>
            </form>
        </div>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th>
                        <th>IVA</th><th>Activo</th><th>Destacado</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-productos"></tbody>
            </table>
        </div>
    </section>

    <!-- CATEGORÍAS -->
    <section id="vista-categorias" class="admin-vista" hidden>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 text-white mb-0">Categorías</h2>
            <button type="button" class="btn boton-amarillo" data-nuevo="categoria">NUEVA CATEGORÍA</button>
        </div>

        <div id="panel-categoria" class="tarjeta-panel mb-4" hidden>
            <h3 class="h6 mb-3" id="titulo-form-categoria">Nueva categoría</h3>
            <form id="form-categoria">
                <input type="hidden" name="id" value="">
                <div class="admin-form-grid">
                    <div>
                        <label for="categoria-nombre">Nombre *</label>
                        <input type="text" id="categoria-nombre" name="nombre" required>
                    </div>
                    <div>
                        <label for="categoria-orden">Orden</label>
                        <input type="number" id="categoria-orden" name="orden" min="0" value="0">
                    </div>
                    <div class="ancho-completo">
                        <label for="categoria-descripcion">Descripción</label>
                        <textarea id="categoria-descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <div>
                        <label for="categoria-imagen">Imagen</label>
                        <input type="text" id="categoria-imagen" name="imagen">
                    </div>
                    <div>
                        <label for="categoria-activa">Activa</label>
                        <select id="categoria-activa" name="activa">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn boton-amarillo">GUARDAR</button>
                    <button type="button" class="btn boton-volver" data-cancelar="categoria">CANCELAR</button>
                </div>
            </form>
        </div>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Orden</th><th>Activa</th><th>Acciones</th></tr>
                </thead>
                <tbody id="tabla-categorias"></tbody>
            </table>
        </div>
    </section>

    <!-- INGREDIENTES -->
    <section id="vista-ingredientes" class="admin-vista" hidden>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 text-white mb-0">Ingredientes</h2>
            <button type="button" class="btn boton-amarillo" data-nuevo="ingrediente">NUEVO INGREDIENTE</button>
        </div>

        <form id="filtro-ingredientes" class="admin-filtros">
            <div>
                <label for="filtro-ingrediente-nombre">Nombre</label>
                <input type="search" id="filtro-ingrediente-nombre" name="nombre" placeholder="Buscar...">
            </div>
            <div>
                <label for="filtro-ingrediente-activo">Estado</label>
                <select id="filtro-ingrediente-activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button type="submit" class="btn boton-amarillo w-100">FILTRAR</button>
            </div>
        </form>

        <div id="panel-ingrediente" class="tarjeta-panel mb-4" hidden>
            <h3 class="h6 mb-3" id="titulo-form-ingrediente">Nuevo ingrediente</h3>
            <form id="form-ingrediente">
                <input type="hidden" name="id" value="">
                <div class="admin-form-grid">
                    <div>
                        <label for="ingrediente-nombre">Nombre *</label>
                        <input type="text" id="ingrediente-nombre" name="nombre" required>
                    </div>
                    <div>
                        <label for="ingrediente-precio">Precio extra (€)</label>
                        <input type="number" id="ingrediente-precio" name="precio_extra" step="0.01" min="0" value="0">
                    </div>
                    <div>
                        <label for="ingrediente-activo">Activo</label>
                        <select id="ingrediente-activo" name="activo">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn boton-amarillo">GUARDAR</button>
                    <button type="button" class="btn boton-volver" data-cancelar="ingrediente">CANCELAR</button>
                </div>
            </form>
        </div>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead><tr><th>ID</th><th>Nombre</th><th>Precio extra</th><th>Activo</th><th>Acciones</th></tr></thead>
                <tbody id="tabla-ingredientes"></tbody>
            </table>
        </div>
    </section>

    <!-- OFERTAS -->
    <section id="vista-ofertas" class="admin-vista" hidden>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 text-white mb-0">Ofertas</h2>
            <button type="button" class="btn boton-amarillo" data-nuevo="oferta">NUEVA OFERTA</button>
        </div>

        <form id="filtro-ofertas" class="admin-filtros">
            <div>
                <label for="filtro-oferta-nombre">Nombre</label>
                <input type="search" id="filtro-oferta-nombre" name="nombre" placeholder="Buscar...">
            </div>
            <div>
                <label for="filtro-oferta-tipo">Tipo</label>
                <select id="filtro-oferta-tipo" name="tipo">
                    <option value="">Todos</option>
                    <option value="descuento">Descuento</option>
                    <option value="2x1">2x1</option>
                    <option value="combo">Combo</option>
                    <option value="codigo">Código</option>
                </select>
            </div>
            <div>
                <label for="filtro-oferta-activa">Estado</label>
                <select id="filtro-oferta-activa" name="activa">
                    <option value="">Todas</option>
                    <option value="1">Activas</option>
                    <option value="0">Inactivas</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button type="submit" class="btn boton-amarillo w-100">FILTRAR</button>
            </div>
        </form>

        <div id="panel-oferta" class="tarjeta-panel mb-4" hidden>
            <h3 class="h6 mb-3" id="titulo-form-oferta">Nueva oferta</h3>
            <form id="form-oferta">
                <input type="hidden" name="id" value="">
                <div class="admin-form-grid">
                    <div>
                        <label for="oferta-nombre">Nombre *</label>
                        <input type="text" id="oferta-nombre" name="nombre" required>
                    </div>
                    <div>
                        <label for="oferta-tipo">Tipo</label>
                        <select id="oferta-tipo" name="tipo">
                            <option value="descuento">Descuento</option>
                            <option value="2x1">2x1</option>
                            <option value="combo">Combo</option>
                            <option value="codigo">Código</option>
                        </select>
                    </div>
                    <div class="ancho-completo">
                        <label for="oferta-descripcion">Descripción</label>
                        <textarea id="oferta-descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <div>
                        <label for="oferta-ambito">Ámbito</label>
                        <select id="oferta-ambito" name="ambito">
                            <option value="producto">Producto</option>
                            <option value="categoria">Categoría</option>
                            <option value="pedido">Pedido</option>
                        </select>
                    </div>
                    <div>
                        <label for="oferta-categoria">Categoría (si aplica)</label>
                        <select id="oferta-categoria" name="categoria_id">
                            <option value="">—</option>
                        </select>
                    </div>
                    <div>
                        <label for="oferta-valor">Valor (% o precio combo)</label>
                        <input type="number" id="oferta-valor" name="valor" step="0.01" min="0">
                    </div>
                    <div>
                        <label for="oferta-codigo">Código</label>
                        <input type="text" id="oferta-codigo" name="codigo" placeholder="GALAXIA10">
                    </div>
                    <div>
                        <label for="oferta-cantidad">Cantidad mínima</label>
                        <input type="number" id="oferta-cantidad" name="cantidad_minima" min="1" value="1">
                    </div>
                    <div>
                        <label for="oferta-importe">Importe mínimo (€)</label>
                        <input type="number" id="oferta-importe" name="importe_minimo" step="0.01" min="0" value="0">
                    </div>
                    <div>
                        <label for="oferta-inicio">Fecha de inicio</label>
                        <input type="datetime-local" id="oferta-inicio" name="fecha_inicio">
                    </div>
                    <div>
                        <label for="oferta-fin">Fecha de fin</label>
                        <input type="datetime-local" id="oferta-fin" name="fecha_fin">
                    </div>
                    <div>
                        <label for="oferta-activa">Activa</label>
                        <select id="oferta-activa" name="activa">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn boton-amarillo">GUARDAR</button>
                    <button type="button" class="btn boton-volver" data-cancelar="oferta">CANCELAR</button>
                </div>
            </form>
        </div>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Ámbito</th><th>Valor</th><th>Código</th><th>Activa</th><th>Acciones</th></tr>
                </thead>
                <tbody id="tabla-ofertas"></tbody>
            </table>
        </div>
    </section>

    <!-- PEDIDOS -->
    <section id="vista-pedidos" class="admin-vista" hidden>
        <h2 class="h4 text-white mb-3">Pedidos</h2>

        <form id="filtro-pedidos" class="admin-filtros">
            <div>
                <label for="filtro-pedido-estado">Estado</label>
                <select id="filtro-pedido-estado" name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="preparando">Preparando</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label for="filtro-pedido-order">Ordenar por</label>
                <select id="filtro-pedido-order" name="order">
                    <option value="id">ID</option>
                    <option value="fecha_pedido">Fecha</option>
                    <option value="total">Total</option>
                </select>
            </div>
            <div>
                <label for="filtro-pedido-dir">Dirección</label>
                <select id="filtro-pedido-dir" name="dir">
                    <option value="DESC">Descendente</option>
                    <option value="ASC">Ascendente</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button type="submit" class="btn boton-amarillo w-100">FILTRAR</button>
            </div>
        </form>

        <div id="panel-pedido-detalle" class="tarjeta-panel mb-4" hidden>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h6 mb-0" id="titulo-detalle-pedido">Detalle del pedido</h3>
                <button type="button" class="btn boton-volver" data-cancelar="pedido">CERRAR</button>
            </div>
            <div id="detalle-pedido-contenido"></div>
        </div>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Estado</th><th>Pago</th><th>Total</th><th>Acciones</th></tr>
                </thead>
                <tbody id="tabla-pedidos"></tbody>
            </table>
        </div>
    </section>

    <!-- USUARIOS -->
    <section id="vista-usuarios" class="admin-vista" hidden>
        <h2 class="h4 text-white mb-3">Usuarios</h2>

        <form id="filtro-usuarios" class="admin-filtros">
            <div>
                <label for="filtro-usuario-nombre">Nombre o email</label>
                <input type="search" id="filtro-usuario-nombre" name="nombre" placeholder="Buscar...">
            </div>
            <div>
                <label for="filtro-usuario-rol">Rol</label>
                <select id="filtro-usuario-rol" name="rol">
                    <option value="">Todos</option>
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div>
                <label for="filtro-usuario-activo">Estado</label>
                <select id="filtro-usuario-activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button type="submit" class="btn boton-amarillo w-100">FILTRAR</button>
            </div>
        </form>

        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th>Alta</th><th>Acciones</th></tr>
                </thead>
                <tbody id="tabla-usuarios"></tbody>
            </table>
        </div>
    </section>
</div>

<script>
    window.STARBITES = {
        url: <?= json_encode(url, JSON_UNESCAPED_SLASHES) ?>,
        usuario: <?= json_encode($usuario->getNombreCompleto(), JSON_UNESCAPED_UNICODE) ?>
    };
</script>
<script src="<?= url ?>public/js/api.js"></script>
<script src="<?= url ?>public/js/admin.js"></script>

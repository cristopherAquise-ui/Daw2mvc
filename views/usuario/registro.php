<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="tarjeta-formulario">
                <div class="pagina-cabecera">
                    <h1 class="pagina-titulo h3 mb-2">Crear cuenta</h1>
                    <p class="pagina-subtitulo">Únete a Star Bites y pide desde cualquier galaxia.</p>
                </div>

                <form action="<?= url ?>?controller=usuario&action=guardar" method="POST" novalidate>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label text-light texto-pequeno mb-1">Nombre *</label>
                            <input type="text" class="form-control campo-contacto" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-6">
                            <label for="apellidos" class="form-label text-light texto-pequeno mb-1">Apellidos</label>
                            <input type="text" class="form-control campo-contacto" id="apellidos" name="apellidos">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label text-light texto-pequeno mb-1">Email *</label>
                            <input type="email" class="form-control campo-contacto" id="email" name="email"
                                   autocomplete="email" required>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label text-light texto-pequeno mb-1">Contraseña *</label>
                            <input type="password" class="form-control campo-contacto" id="password" name="password"
                                   autocomplete="new-password" minlength="8" required>
                            <div class="form-texto mt-1">Mínimo 8 caracteres.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label text-light texto-pequeno mb-1">Teléfono</label>
                            <input type="tel" class="form-control campo-contacto" id="telefono" name="telefono">
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_nacimiento" class="form-label text-light texto-pequeno mb-1">Fecha de nacimiento</label>
                            <input type="date" class="form-control campo-contacto" id="fecha_nacimiento" name="fecha_nacimiento">
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label text-light texto-pequeno mb-1">Dirección</label>
                            <input type="text" class="form-control campo-contacto" id="direccion" name="direccion">
                        </div>

                        <div class="col-md-8">
                            <label for="ciudad" class="form-label text-light texto-pequeno mb-1">Ciudad</label>
                            <input type="text" class="form-control campo-contacto" id="ciudad" name="ciudad">
                        </div>

                        <div class="col-md-4">
                            <label for="codigo_postal" class="form-label text-light texto-pequeno mb-1">Código postal</label>
                            <input type="text" class="form-control campo-contacto" id="codigo_postal" name="codigo_postal">
                        </div>
                    </div>

                    <button type="submit" class="btn boton-amarillo w-100 mt-4">CREAR CUENTA</button>
                </form>

                <p class="text-secondary text-center mt-4 mb-0">
                    ¿Ya tienes cuenta?
                    <a href="<?= url ?>?controller=usuario&action=login" class="enlace-amarillo">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>

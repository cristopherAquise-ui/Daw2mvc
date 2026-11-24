<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="tarjeta-formulario">
                <div class="pagina-cabecera">
                    <h1 class="pagina-titulo h3 mb-2">Iniciar sesión</h1>
                    <p class="pagina-subtitulo">Accede para gestionar tus pedidos y tus datos.</p>
                </div>

                <form action="<?= url ?>?controller=usuario&action=autenticar" method="POST" novalidate>

                    <div class="mb-3">
                        <label for="email" class="form-label text-light texto-pequeno mb-1">Email</label>
                        <input type="email" class="form-control campo-contacto" id="email" name="email"
                               autocomplete="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-light texto-pequeno mb-1">Contraseña</label>
                        <input type="password" class="form-control campo-contacto" id="password" name="password"
                               autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn boton-amarillo w-100">ENTRAR</button>
                </form>

                <p class="text-secondary text-center mt-4 mb-0">
                    ¿Todavía no tienes cuenta?
                    <a href="<?= url ?>?controller=usuario&action=registro" class="enlace-amarillo">Regístrate</a>
                </p>
            </div>

            <p class="form-texto text-center mt-3 mb-0">
                Demo: <strong>cliente@starbites.gal</strong> / <strong>Cliente123!</strong>
            </p>
        </div>
    </div>
</div>

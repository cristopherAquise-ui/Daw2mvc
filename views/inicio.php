<!-- HERO SECTION -->
<section class="row align-items-center min-vh-75 py-5">
    <div class="col-12 col-md-6 text-center text-md-start">
        <h1 class="display-4 fw-bold mb-4">
            Bienvenido al lado oscuro<br>
            Donde la comida es de <br>otro planeta
        </h1>
        <a href="<?= url ?>?controller=categoria" class="btn btn-starbites mt-3">RESERVAS / CARTA</a>
    </div>
    <!-- Añadimos una imagen a la derecha para llenar el vacío que mencionas en el PDF -->
    <div class="col-12 col-md-6 mt-5 mt-md-0 text-center">
        <img src="<?= url ?>public/img/productos/segundos/carre_cordero_eclipse.jpg" class="img-fluid rounded-4 shadow-lg" alt="Plato destacado">
    </div>
</section>

<!-- SECCIÓN NOSOTROS -->
<section class="row align-items-center py-5 mt-5 border-top border-secondary">
    <div class="col-md-6">
        <h2 class="fw-bold mb-4 text-warning">Conoce más de Nosotros</h2>
        <p class="fs-5 text-light opacity-75">
            En Star Bites, nacimos de la pasión por los sabores intensos y la cultura pop. Nuestro objetivo es transportarte a otra galaxia con cada bocado, utilizando ingredientes frescos de primera calidad y combinaciones audaces.
        </p>
        <p class="fs-5 text-light opacity-75">
            Desde nuestras famosas hamburguesas del lado oscuro hasta nuestros postres intergalácticos, cada plato está diseñado para contar una historia. Únete a la rebelión del sabor.
        </p>
    </div>
    <div class="col-md-6 text-center">
        <img src="<?= url ?>public/img/local-interior.jpg" class="img-fluid rounded-4" alt="Interior del restaurante"> <!-- Cambia por tu imagen -->
    </div>
</section>

<!-- SECCIÓN CONTACTO -->
<section class="py-5 mt-5">
    <h2 class="text-uppercase fw-bold mb-4">CONTACTO</h2>
    <div class="row bg-black p-5 rounded-4 border border-secondary align-items-center">
        <div class="col-md-5">
            <h3 class="fw-bold mb-3">Nuestras Coordenadas</h3>
            <p class="text-muted mb-4">¿Tienes alguna duda sobre la reserva o quieres organizar un evento imperial? Escríbenos.</p>
            <ul class="list-unstyled text-light">
                <li class="mb-2">📍 Sector Coruscant, Bloque 4</li>
                <li class="mb-2">📞 +34 900 113 800</li>
                <li class="mb-2">✉️ comunicaciones@starbites.gal</li>
            </ul>
        </div>
        <div class="col-md-7">
            <form>
                <div class="mb-3">
                    <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="Nombre">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control bg-dark text-light border-secondary" placeholder="Email">
                </div>
                <div class="mb-4">
                    <textarea class="form-control bg-dark text-light border-secondary" rows="4" placeholder="Mensaje"></textarea>
                </div>
                <button type="submit" class="btn btn-starbites">ENVIAR MENSAJE</button>
            </form>
        </div>
    </div>
</section>
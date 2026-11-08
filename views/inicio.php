<!-- parte 1 -->

<section class="hero-section d-flex align-items-center position-relative">
    <div class="hero-overlay"></div>
    
    <div class="container position-relative z-1">
        <div class="col-12 col-md-8 col-lg-8 text-center text-md-star">
            <h1 class="display-4 fw-bold mb-4 text-white">
                Bienvenido al lado oscuro <br>
                Donde la comida es de otro planeta
            </h1>
            <a href="<?= url ?>?controller=categoria" class="btn btn-starbites mt-3">RESERVAS</a>
        </div>
    </div>
</section>

<!-- PARTE 2 - PRODUCTOS POPULARES -->
<section class="py-5 mt-5">
    <div class="mb-5 border-bottom border-secondary pb-3">
        <h2 class="fw-bold text-white fs-3 mb-0">Productos Populares</h2>
    </div>
    <div class="row gx-5 justify-content-center">
        <?php if (!empty($productos)): ?>
            <?php foreach (array_slice($productos, 0, 2) as $producto): ?>
            <div class="col-12 col-md-6">
                <div class="product-card-sw">
                    <div class="product-thumb">
                        <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                             alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="product-details">
                        <h5 class="product-title"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 col-md-5">
                <div class="product-card-sw">
                    <div class="product-thumb">
                        <img src="<?= url ?>public/img/productos/segundos/carre_cordero_eclipse.jpg" alt="Confit Estelar de Pato">
                    </div>
                    <div class="product-details">
                        <h5 class="product-title">Confit Estelar de Pato y Cerezas Negras.</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-5">
                <div class="product-card-sw">
                    <div class="product-thumb">
                        <img src="<?= url ?>public/img/productos/segundos/carre_cordero_eclipse.jpg" alt="Esfera Sideral de Wagyu">
                    </div>
                    <div class="product-details">
                        <h5 class="product-title">Esfera Sideral de Wagyu, Trufa Negra y Foie Gras.</h5>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- boton READ MORE -->
    <div class="text-center mt-5">
        <a href="<?= url ?>?controller=categoria" class="btn btn-starbites btn-read-more">READ MORE</a>
    </div>
</section>

<!-- PARTE 3 CONOCE DE NOSOTROS -->
<section class="nosotros-section position-relative d-flex align-items-center py-5">
    <div class="container position-relative z-1">
        <div class="row align-items-center justify-content-between">
            
            <!-- columna logo izq -->
            <div class="col-12 col-md-5 mb-4 mb-md-0 text-center text-md-start">
                <img src="<?= url ?>public/svg/Logo-negro-svg.svg" alt="Star Bites Logo" class="img-fluid logo-nosotros"">
            </div>

            <!-- columna derecha texto -->
            <div class="col-12 col-md-7 col-lg-5 text-center">
                <h2 class="fw-bold mb-4 text-white display-6">
                    Conoce mas de <br>
                    <span class="text-amarillo">Nosotros</span>
                </h2>
                
                <p class="fs-5 text-white opacity-90 mb-4">
                    <span class="text-amarillo fw-bold">En Star Bites</span>, nacimos de la pasion por los sabores intensos y la cultura pop. Nuestro objetivo es transportarte a otra galaxia con cada bocado, utilizando ingredientes frescos de primera calidad y combinaciones audaces.
                </p>
                
                <p class="fs-5 text-white opacity-90">
                    Desde nuestras famosas hamburguesas del lado oscuro hasta nuestros postres intergalacticos, cada plato esta diseno para contar una historia. Unete a la rebelion del sabor.
                </p>
            </div>
            
        </div>
    </div>
</section>

<!-- PARTE 4 - SECCION CONTACTOS  -->
<section class="contact-section position-relative py-5 mt-5">
    <div class="contact-bg-image"></div>

    <div class="container position-relative z-1">
        <h2 class=" fw-bold mb-4 text-white">Contacto</h2>
        
        <!-- Contenedor principal -->
        <div class="contact-card p-4 p-md-5 rounded-4 shadow-lg">
            <div class="row align-items-center">
                
                <!-- Columna izq -->
                <div class="col-md-6 pe-md-5 mb-5 mb-md-0">
                    <h3 class="fw-bold mb-4 text-white">Nuestras Coordenadas</h3>
                    <p class="text-light opacity-75 mb-4 fs-6">
                        ¿Tienes alguna duda sobre tu reserva o quieres organizar un evento imperial? Escríbenos.
                    </p>
                    <ul class="list-unstyled text-light">
                        <li class="mb-3 d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/localizacion-svg.svg" alt="Ubicación" class="contact-icon me-3">
                            <span>Sector Corellia, Bloque 4, Coruscant</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/phone-svg.svg" alt="Teléfono" class="contact-icon me-3">
                            <span>+34 900 113 800</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/email-svg.svg" alt="Email" class="contact-icon me-3">
                            <span>comunicaciones@starbites.gal</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Columna Derecha Form -->
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-light fs-7 mb-1">Nombre</label>
                            <input type="text" class="form-control contact-input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light fs-7 mb-1">Email</label>
                            <input type="email" class="form-control contact-input">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light fs-7 mb-1">Mensaje</label>
                            <textarea class="form-control contact-input" rows="4"></textarea>
                        </div>
                        <div class="text-center mt-4">
                            <!-- Reutilizamos la clase del botón anterior para mantener el estilo píldora -->
                            <button type="submit" class="btn btn-starbites btn-read-more px-5">ENVIAR MENSAJE</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
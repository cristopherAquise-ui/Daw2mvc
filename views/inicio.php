<!-- portada -->

<section class="portada d-flex align-items-center position-relative">
    <div class="capa-oscura"></div>
    
    <div class="container position-relative z-1">
        <div class="col-12 col-md-8 col-lg-8 text-center texto-portada">
            <h1 class="display-4 fw-bold mb-4 text-white">
                Bienvenido al lado oscuro <br>
                Donde la comida es de otro planeta
            </h1>
            <a href="<?= url ?>?controller=categoria" class="boton-amarillo mt-3">RESERVAS</a>
        </div>
    </div>
</section>

<!-- productos populares -->
<section class="py-5 mt-5">
    <div class="mb-5 border-bottom border-secondary pb-3">
        <h2 class="fw-bold text-white fs-3 mb-0">Productos Populares</h2>
    </div>
    <div class="row gx-5 justify-content-center">
        <?php if (!empty($productos)): ?>
            <?php foreach (array_slice($productos, 0, 2) as $producto): ?>
            <div class="col-12 col-md-6">
                <div class="tarjeta-producto">
                    <div class="foto-producto">
                        <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                             alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="detalle-producto">
                        <h5 class="titulo-producto"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 col-md-5">
                <div class="tarjeta-producto">
                    <div class="foto-producto">
                        <img src="<?= url ?>public/img/productos/segundos/carre_cordero_eclipse.jpg" alt="Confit Estelar de Pato">
                    </div>
                    <div class="detalle-producto">
                        <h5 class="titulo-producto">Confit Estelar de Pato y Cerezas Negras.</h5>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-5">
                <div class="tarjeta-producto">
                    <div class="foto-producto">
                        <img src="<?= url ?>public/img/productos/segundos/carre_cordero_eclipse.jpg" alt="Esfera Sideral de Wagyu">
                    </div>
                    <div class="detalle-producto">
                        <h5 class="titulo-producto">Esfera Sideral de Wagyu, Trufa Negra y Foie Gras.</h5>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- boton ver mas -->
    <div class="text-center mt-5">
        <a href="<?= url ?>?controller=categoria" class="boton-amarillo boton-ver-mas">READ MORE</a>
    </div>
</section>

<!-- nosotros -->
<section class="seccion-nosotros position-relative d-flex align-items-center py-5">
    <div class="container position-relative z-1">
        <div class="row align-items-center justify-content-between">
            
            <!-- logo -->
            <div class="col-12 col-md-5 mb-4 mb-md-0 text-center text-md-start">
                <img src="<?= url ?>public/svg/Logo-negro-svg.svg" alt="Star Bites Logo" class="img-fluid logo-nosotros">
            </div>

            <!-- texto -->
            <div class="col-12 col-md-7 col-lg-5 text-center">
                <h2 class="fw-bold mb-4 text-white display-6">
                    Conoce mas de <br>
                    <span class="texto-amarillo">Nosotros</span>
                </h2>
                
                <p class="fs-5 text-white casi-opaco mb-4">
                    <span class="texto-amarillo fw-bold">En Star Bites</span>, nacimos de la pasion por los sabores intensos y la cultura pop. Nuestro objetivo es transportarte a otra galaxia con cada bocado, utilizando ingredientes frescos de primera calidad y combinaciones audaces.
                </p>
                
                <p class="fs-5 text-white casi-opaco">
                    Desde nuestras famosas hamburguesas del lado oscuro hasta nuestros postres intergalacticos, cada plato esta diseno para contar una historia. Unete a la rebelion del sabor.
                </p>
            </div>
            
        </div>
    </div>
</section>

<!-- contacto -->
<section id="contacto" class="seccion-contacto position-relative py-5 mt-5">
    <div class="fondo-contacto"></div>

    <div class="container position-relative z-1">
        <h2 class=" fw-bold mb-4 text-white">Contacto</h2>
        
        <!-- caja de contacto -->
        <div class="caja-contacto p-4 p-md-5 rounded-4 shadow-lg">
            <div class="row align-items-center">
                
                <!-- datos -->
                <div class="col-md-6 pe-md-5 mb-5 mb-md-0">
                    <h3 class="fw-bold mb-4 text-white">Nuestras Coordenadas</h3>
                    <p class="text-light opacity-75 mb-4 fs-6">
                        ¿Tienes alguna duda sobre tu reserva o quieres organizar un evento imperial? Escríbenos.
                    </p>
                    <ul class="list-unstyled text-light">
                        <li class="mb-3 d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/localizacion-svg.svg" alt="Ubicación" class="icono-contacto me-3">
                            <span>Sector Corellia, Bloque 4, Coruscant</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/phone-svg.svg" alt="Teléfono" class="icono-contacto me-3">
                            <span>+34 900 113 800</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <img src="<?= url ?>public/svg/icono/email-svg.svg" alt="Email" class="icono-contacto me-3">
                            <span>comunicaciones@starbites.gal</span>
                        </li>
                    </ul>
                </div>
                
                <!-- formulario -->
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-light texto-pequeno mb-1">Nombre</label>
                            <input type="text" class="form-control campo-contacto">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-light texto-pequeno mb-1">Email</label>
                            <input type="email" class="form-control campo-contacto">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-light texto-pequeno mb-1">Mensaje</label>
                            <textarea class="form-control campo-contacto" rows="4"></textarea>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="boton-amarillo boton-ver-mas px-5">ENVIAR MENSAJE</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Bites — Gastro Galáctico</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url ?>public/css/estilos.css">
</head>

<body class="bg-black text-light min-vh-100 d-flex flex-column">
    <header class="bg-black border-bottom sticky-top pt-3 pb-2 px-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- ICONOS IZQUIERDA -->
                <div class="col-6 col-md-4 d-flex justify-content-start gap-4 align-items-center order-2 order-md-1 mt-3 mt-md-0">
                    <a href="#" title="Facebook" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/facebook-ico.svg" alt="Facebook-ico" class="icono-header">
                    </a>
                    <a href="#" title="TikTok" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/tiktok-logo-white.svg" alt="Tiktok-ico" class="icono-header">
                    </a>
                    <a href="#" title="Instagram" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/instagram-ico.svg" alt="Instagram-ico" class="icono-header">
                    </a>
                </div>

                <!-- LOGO CENTRO -->
                <div class="col-12 col-md-4 text-center order-1 order-md-2">
                    <a href="?controller=inicio" class="text-decoration-none d-inline-block">
                        <img src="<?= url ?>public/svg/Logo-blanco-SVG.svg" alt="Star Bites Logo">
                    </a>
                </div>

                <!-- ICONOS DERECHA -->
                <div class="col-6 col-md-4 d-flex justify-content-end gap-4 align-items-center order-3 order-md-3 mt-3 mt-md-0">
                    <a href="?controller=usuario&action=login" title="Mi Cuenta" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/usuario-svg.svg" alt="Usuario" class="icono-header">
                    </a>
                    <a href="#" title="Buscar" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/lupa-svg.svg" alt="Buscar" class="icono-header">
                    </a>
                    <a href="?controller=carrito" class="enlace-carrito text-decoration-none position-relative" title="Carrito">
                        <img src="<?= url ?>public/svg/icono/Carrito-Compra-SVG.svg" alt="Carrito" class="icono-carrito icono-header">
                        <span class="contador-carrito position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            0
                        </span>
                    </a>
                </div>
            </div>

            <!-- menu -->
            <nav class="row mt-3">
                <div class="col-12 d-flex justify-content-center gap-4">
                    <?php
                    // controlador actual, si no hay ninguno es inicio
                    $controladorActual = isset($_GET['controller']) ? $_GET['controller'] : 'inicio';
                    ?>

                    <a class="nav-link text-uppercase fw-semibold text-light brillo-amarillo <?= ($controladorActual == 'inicio') ? 'active' : '' ?>"
                        href="?controller=inicio">
                        INICIO
                    </a>
                    <a class="nav-link text-uppercase fw-semibold text-light brillo-amarillo <?= ($controladorActual == 'carta') ? 'active' : '' ?>"
                        href="?controller=carta">
                        CARTA
                    </a>
                    <a class="nav-link text-uppercase fw-semibold text-light brillo-amarillo <?= ($controladorActual == 'contacto') ? 'active' : '' ?>"
                        href="#">
                        CONTACTO
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- contenido -->
    <main class="container my-5 flex-grow-1">
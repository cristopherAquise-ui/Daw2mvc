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
    <header class="bg-black border-bottom border-warning sticky-top py-3 px-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-4 d-none d-md-block"></div>
                
                <!-- Logo -->
                <div class="col-12 col-md-4 text-center">
                    <a href="?controller=inicio" class="text-decoration-none d-inline-block">
                        <img src="<?= url ?>public/svg/Logo-blanco-SVG.svg" alt="Star Bites Logo">
                    </a>
                </div>

                <!-- Iconos header -->
                <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end gap-4 mt-3 mt-md-0 align-items-center">
                    <a href="?controller=usuario&action=login" title="Mi Cuenta" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/usuario-svg.svg" alt="Usuario">
                    </a>
                    <a href="#" title="Buscar" class="text-decoration-none">
                        <img src="<?= url ?>public/svg/icono/lupa-svg.svg" alt="Buscar">
                    </a>
                    <a href="?controller=carrito" class="nav-carrito text-decoration-none position-relative" title="Carrito">
                        <img src="<?= url ?>public/svg/icono/Carrito-Compra-SVG.svg" alt="Carrito" class="icono-carrito">
                        <!-- Círculo carrito -->
                        <span class="badge-carrito position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            0
                        </span>
                    </a>
                </div>
            </div>

            <!-- Menú -->
            <nav class="row mt-3">
                <div class="col-12 d-flex justify-content-center gap-4">
                    <a class="nav-link text-uppercase fw-semibold text-light hover-warning" href="?controller=inicio">INICIO</a>
                    <a class="nav-link text-uppercase fw-semibold text-light hover-warning" href="?controller=categoria">CARTA</a>
                    <a class="nav-link text-uppercase fw-semibold text-light hover-warning" href="#">CONTACTO</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Contenedor principal -->
    <main class="container my-5 flex-grow-1">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 text-center">
            <div class="tarjeta-panel py-5">
                <div class="display-3 mb-2" aria-hidden="true">🛸</div>
                <h1 class="pagina-titulo h2 mb-3">Error <?= (int) ($codigoError ?? 404) ?></h1>
                <p class="text-secondary mb-4"><?= texto($mensajeError ?? 'Ha ocurrido un error inesperado.') ?></p>
                <a href="<?= url ?>?controller=inicio" class="btn boton-amarillo">VOLVER AL INICIO</a>
            </div>
        </div>
    </div>
</div>

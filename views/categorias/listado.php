<h1 class="text-warning mb-4">Nuestras Categorías</h1>

<div class="row row-cols-1 row-cols-md-2 g-4">
    <?php foreach ($categorias as $categoria) { ?>
        <div class="col">
            <a href="?controller=producto&categoria_id=<?= $categoria->getId() ?>" class="text-decoration-none">
                <div class="card card-galactica h-100 p-3">
                    <div class="card-body">
                        <h5 class="card-title text-warning fw-bold"><?= htmlspecialchars($categoria->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="card-text text-secondary"><?= htmlspecialchars($categoria->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>
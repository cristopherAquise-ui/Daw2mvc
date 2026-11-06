<!-- Menú de categorías (Simulado para frontend visual) -->
<div class="d-flex justify-content-center justify-content-md-start gap-4 mb-5 flex-wrap border-bottom border-secondary pb-3">
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-warning border-bottom border-warning pb-1">Entrantes</a>
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-light opacity-75 hover-warning">Primeros Platos</a>
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-light opacity-75 hover-warning">Segundos Platos</a>
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-light opacity-75 hover-warning">Guarniciones</a>
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-light opacity-75 hover-warning">Postres</a>
    <a href="#" class="text-decoration-none text-uppercase fw-bold text-light opacity-75 hover-warning">Bebidas</a>
</div>

<!-- Título de la sección -->
<h2 class="fw-bold mb-4">Entrantes</h2>

<!-- Cuadrícula de Platos -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($productos as $producto): ?>
    <div class="col">
        <!-- Aplicamos tu clase card-galactica -->
        <div class="card card-galactica h-100 p-2 text-center">
            <!-- Imagen del producto -->
            <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen()) ?>" 
                 class="card-img-top rounded-3" 
                 alt="<?= htmlspecialchars($producto->getNombre()) ?>"
                 style="object-fit: cover; aspect-ratio: 1/1;">
            
            <div class="card-body d-flex flex-column justify-content-between p-2 mt-2">
                <h6 class="card-title fw-bold mb-3"><?= htmlspecialchars($producto->getNombre()) ?></h6>
                
                <!-- Botón de añadir (lleva al detalle que hicimos antes) -->
                <div>
                    <a href="<?= url ?>?controller=producto&action=detalle&id=<?= $producto->getId() ?>" 
                       class="btn btn-starbites rounded-circle p-0 d-inline-flex align-items-center justify-content-center" 
                       style="width: 35px; height: 35px; font-size: 1.5rem; line-height: 1;">
                       +
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
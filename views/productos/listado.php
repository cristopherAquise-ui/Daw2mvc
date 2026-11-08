<!-- Cuadrícula de Platos -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
    <?php foreach ($productos as $producto): ?>
    <div class="col">
        <div class="product-card-sw h-100">
            
            <div class="product-thumb">
                <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                     alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                     class="product-img-fit card-img-top">
            </div>
            
            <div class="product-details p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="decal-contain">
                        <div class="decal"></div>
                    </div>
                    
                    <h5 class="product-title mt-2"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="product-price fw-bold"><?= number_format((float)$producto->getPrecio(), 2, ',', '.') ?> €</span>
                    <a href="<?= url ?>?controller=producto&action=detalle&id=<?= $producto->getId() ?>" 
                       class="btn btn-starbites rounded-circle p-0 d-inline-flex align-items-center justify-content-center btn-add-custom">
                        +
                    </a>
                </div>
            </div>

        </div>
    </div>
    <?php endforeach; ?>
</div>
<!-- platos -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
    <?php foreach ($productos as $producto): ?>
    <div class="col">
        <div class="tarjeta-producto h-100">
            
            <div class="foto-producto">
                <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                     alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                     class="foto-ajustada card-img-top">
            </div>
            
            <div class="detalle-producto p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="caja-decoracion">
                        <div class="decoracion"></div>
                    </div>
                    
                    <h5 class="titulo-producto mt-2"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="precio-producto fw-bold"><?= number_format((float)$producto->getPrecio(), 2, ',', '.') ?> €</span>
                    <a href="<?= url ?>?controller=producto&action=detalle&id=<?= $producto->getId() ?>" 
                       class="btn boton-amarillo rounded-circle p-0 d-inline-flex align-items-center justify-content-center boton-mas">
                        +
                    </a>
                </div>
            </div>

        </div>
    </div>
    <?php endforeach; ?>
</div>

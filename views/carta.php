<?php
$categoriaActual = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';
?>

<!-- menu arriba categorias -->
<div class="carta-subnav-container d-flex gap-4 pb-2 mb-5">
    <a href="?controller=carta&categoria=todos" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'todos') ? 'active' : '' ?>">
        TODOS
    </a>
    <a href="?controller=carta&categoria=entrantes" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'entrantes') ? 'active' : '' ?>">
        ENTRANTES
    </a>
    <a href="?controller=carta&categoria=primeros" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'primeros') ? 'active' : '' ?>">
        PRIMEROS PLATOS
    </a>
    <a href="?controller=carta&categoria=segundos" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'segundos') ? 'active' : '' ?>">
        SEGUNDOS PLATOS
    </a>
    <a href="?controller=carta&categoria=guarniciones" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'guarniciones') ? 'active' : '' ?>">
        GUARNICIONES
    </a>
    <a href="?controller=carta&categoria=postres" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'postres') ? 'active' : '' ?>">
        POSTRES
    </a>
    <a href="?controller=carta&categoria=bebidas" class="subnav-link text-decoration-none text-uppercase fw-semibold <?= ($categoriaActual == 'bebidas') ? 'active' : '' ?>">
        BEBIDAS
    </a>
</div>

<?php
$listaCategorias = [
    'entrantes'    => ['id' => 1, 'titulo' => 'Entrantes'],
    'primeros'     => ['id' => 2, 'titulo' => 'Primeros Platos'],
    'segundos'     => ['id' => 3, 'titulo' => 'Segundos Platos'],
    'guarniciones' => ['id' => 4, 'titulo' => 'Guarniciones'],
    'postres'      => ['id' => 5, 'titulo' => 'Postres'],
    'bebidas'      => ['id' => 6, 'titulo' => 'Bebidas']
];

foreach ($listaCategorias as $slug => $catInfo):
    if ($categoriaActual == 'todos' || $categoriaActual == $slug):
?>
        <h2 class="fw-bold mt-5 mb-4"><?= $catInfo['titulo'] ?></h2>

        <!-- contenedor de platos -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 gx-4 gy-5 mb-5">
            <?php 
            foreach ($productos as $producto): 
                
                // compara ID num que devuelve getCategoriaId() con el de la sección
                if ($producto->getCategoriaId() == $catInfo['id']):
            ?>
                <div class="col">
                    <div class="product-card-sw h-100">
                        
                        <div class="product-thumb">
                            <img src="<?= url ?>public/img/productos/<?= htmlspecialchars($producto->getImagen(), ENT_QUOTES, 'UTF-8') ?>" 
                                 alt="<?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?>"
                                 class="product-img-fit card-img-top">
                        </div>
                        
                        <div class="product-details px-3 pt-3 pb-4 d-flex flex-column">
                            <div>
                                <div class="decal-contain">
                                    <div class="decal"></div>
                                </div>
                                
                                <h5 class="product-title mt-2 mb-1"><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></h5>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-0">
                                <span class="product-price fw-bold"><?= number_format((float)$producto->getPrecio(), 2, ',', '.') ?> €</span>
                                <a href="<?= url ?>?controller=producto&action=detalle&id=<?= $producto->getId() ?>" 
                                   class="btn btn-starbites rounded-circle p-0 d-inline-flex align-items-center justify-content-center btn-add-custom">
                                    +
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php 
                endif; 
            endforeach; 
            ?>
        </div>

<?php
    endif;
endforeach; 
?>
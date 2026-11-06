USE daw2mvc;

-- 1. Desactivar temporalmente restricciones de clave ajena para refrescar tablas
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE productos;
TRUNCATE TABLE categorias;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Insertar las 6 Categorías oficiales
INSERT INTO categorias (id, nombre, descripcion, activa) VALUES 
(1, 'Entrantes / Aperitivos', 'Para comenzar el viaje sensorial galáctico.', true),
(2, 'Primeros platos', 'El núcleo de la gastronomía estelar.', true),
(3, 'Segundos platos', 'Creaciones contundentes de alta cocina galáctica.', true),
(4, 'Guarniciones / Acompañamientos', 'Complementos perfectos para acompañar tu experiencia.', true),
(5, 'Postres', 'Dulces meteoritos y finales estelares.', true),
(6, 'Bebidas', 'Elixires, cócteles y combinados cósmicos.', true);

-- 3. Insertar los 36 Productos exactos de la carta

-- Categoría 1: Entrantes / Aperitivos
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(1, 'Nebulosa de Atún Rojo y Yuzu', 'Tartar de atún rojo marinado en yuzu, acompañado de cítricos, hierbas frescas y perlas de sabor marino.', 18.50, 'nebulosa_atun_yuzu.jpg', true),
(1, 'Ceviche Orbital de Carabinero y Maracuyá', 'Carabinero fresco con leche de tigre de maracuyá, cilantro y matices cítricos.', 19.00, 'ceviche_orbital_carabinero.jpg', true),
(1, 'Esfera Sideral de Wagyu, Trufa Negra y Foie Gras', 'Wagyu de textura delicada combinado con foie gras y trufa negra, presentado en una elegante esfera gastronómica.', 22.00, 'esfera_sideral_wagyu.jpg', true),
(1, 'Mosaico Abisal de Pulpo y Erizo', 'Finas láminas de pulpo acompañadas de erizo de mar, aceite de hierbas y pequeños contrastes cítricos.', 17.50, 'mosaico_abisal_pulpo.jpg', true),
(1, 'Nido Galáctico de Huevo a 63° y Trufa Blanca', 'Huevo cocinado a baja temperatura sobre un nido crujiente, con setas y delicados aromas de trufa blanca.', 15.00, 'nido_galactico_huevo.jpg', true),
(1, 'Eclipse de Cigala y Rubí Cítrico', 'Cigala a la plancha sobre una crema de aguacate, acompañada de una reducción cítrica de color rubí.', 20.00, 'eclipse_cigala_rubi.jpg', true);

-- Categoría 2: Primeros platos
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(2, 'Ravioli de Medianoche con Bogavante y Emulsión de Azafrán', 'Raviolis rellenos de bogavante con una suave emulsión de azafrán y notas de mar.', 21.00, 'ravioli_medianoche_bogavante.jpg', true),
(2, 'Risotto Nebulosa de Remolacha y Langosta', 'Risotto cremoso de remolacha con langosta, queso parmesano y un delicado contraste de sabores marinos.', 23.50, 'risotto_nebulosa_langosta.jpg', true),
(2, 'Canelón Galáctico Txangurro y Bisque de Coral', 'Canelón relleno de centollo con una intensa salsa de bisque de marisco y coral.', 19.50, 'canelon_galactico_txangurro.jpg', true),
(2, 'Velouté Esmeralda de Guisantes y Plancton con Caviar Beluga', 'Crema fina de guisantes y plancton marino, terminada con caviar beluga y brotes frescos.', 24.00, 'veloute_esmeralda_caviar.jpg', true),
(2, 'Confit Estelar de Pato y Cerezas Negras', 'Pato confitado lentamente sobre una base cremosa, acompañado de cerezas negras y una reducción de su propio jugo.', 18.50, 'confit_estelar_pato.jpg', true),
(2, 'Gnocchi Áureos con Emulsión de Berros y Trufa Negra', 'Gnocchi dorados de patata con emulsión de berros, trufa negra y pequeños toques de queso curado.', 16.50, 'gnocchi_aureos_trufa.jpg', true);

-- Categoría 3: Segundos platos
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(3, 'Carré de Cordero en Eclipse de Ajo Negro y Remolacha', 'Carré de cordero asado con costra de hierbas, acompañado de crema de remolacha y salsa de ajo negro.', 26.00, 'carre_cordero_eclipse.jpg', true),
(3, 'Lomo de Bacalao Astral con Coral de Tinta y Azafrán', 'Lomo de bacalao confitado con una salsa de tinta de calamar, crema de azafrán y delicados brotes marinos.', 24.00, 'lomo_bacalao_astral.jpg', true),
(3, 'Lomo de Ciervo en Nebulosa de Zarzamora', 'Lomo de ciervo asado al punto, acompañado de una reducción de zarzamoras y crema suave de raíces.', 27.50, 'lomo_ciervo_nebulosa.jpg', true),
(3, 'Solomillo de Ternera en Órbita de Madeira y Café', 'Solomillo de ternera a la parrilla con salsa de Madeira y café tostado, acompañado de patatas fondant.', 28.00, 'solomillo_orbita_madeira.jpg', true),
(3, 'Presa Ibérica Lacada en Miso de Castañas y Manzana', 'Presa ibérica cocinada lentamente y lacada con miso de castañas, acompañada de manzana asada y jugo de carne.', 23.00, 'presa_iberica_miso.jpg', true),
(3, 'Lubina Salvaje en Aurora de Limón y Mantequilla', 'Lubina salvaje a la brasa con beurre blanc de limón, hinojo asado y verduras de temporada.', 25.50, 'lubina_salvaje_aurora.jpg', true);

-- Categoría 4: Guarniciones / Acompañamientos
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(4, 'Órbita Dorada de Patata Anna y Azafrán', 'Finas capas de patata horneadas con mantequilla avellanada, azafrán y una textura exterior crujiente.', 7.50, 'orbita_dorada_patata.jpg', true),
(4, 'Zanahorias Aurora Glaseadas con Miel de Azahar', 'Zanahorias baby asadas y glaseadas con miel de azahar, mantequilla y un toque de comino.', 6.50, 'zanahorias_aurora_miel.jpg', true),
(4, 'Espárragos Estelares a la Brasa con Holandesa de Limón', 'Espárragos verdes a la parrilla acompañados de una salsa holandesa ligera y ralladura de limón.', 8.00, 'esparragos_estelares_brasa.jpg', true),
(4, 'Puré Lunar de Apionabo y Vainilla', 'Crema sedosa de apionabo con mantequilla, vainilla natural y un delicado toque de pimienta blanca.', 7.00, 'pure_lunar_apionabo.jpg', true),
(4, 'Setas Nebulares con Ajo Negro y Tomillo', 'Selección de setas de temporada salteadas con ajo negro, tomillo fresco y aceite de oliva virgen extra.', 8.50, 'setas_nebulares_ajo.jpg', true),
(4, 'Meteoritos Crujientes de Polenta y Parmesano', 'Pequeños bocados de polenta dorada con parmesano, trufa y una textura crujiente por fuera y cremosa por dentro.', 7.50, 'meteoritos_polenta_parmesano.jpg', true);

-- Categoría 5: Postres
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(5, 'Eclipse de Chocolate Guanaja y Frambuesa', 'Esfera de chocolate negro rellena de mousse de chocolate y corazón de frambuesa, con una base crujiente de avellana.', 9.50, 'eclipse_chocolate_guanaja.jpg', true),
(5, 'Esfera Lunar de Yuzu y Chocolate Blanco', 'Mousse de chocolate blanco con núcleo cítrico de yuzu, acompañado de crumble de almendra y gel de limón.', 9.00, 'esfera_lunar_yuzu.jpg', true),
(5, 'Nebulosa de Pistacho, Frambuesa y Rosa', 'Cremoso de pistacho con frambuesas frescas, bizcocho ligero y delicados aromas de agua de rosas.', 8.50, 'nebulosa_pistacho_rosa.jpg', true),
(5, 'Anillo de Saturno de Manzana y Caramelo Salado', 'Manzana caramelizada sobre hojaldre crujiente, acompañada de helado de vainilla y salsa de caramelo salado.', 8.50, 'anillo_saturno_manzana.jpg', true),
(5, 'Crème Brûlée Estelar de Vainilla de Madagascar', 'Crema tradicional de vainilla de Madagascar con una fina capa de azúcar caramelizado y notas de café.', 7.50, 'creme_brulee_estelar.jpg', true),
(5, 'Jardín Cósmico de Coco, Mango y Lima', 'Mousse de coco con corazón de mango, gel de lima y crumble tropical de almendras.', 8.00, 'jardin_cosmico_coco.jpg', true);

-- Categoría 6: Bebidas
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(6, 'Órbita Azul de Ginebra y Flor de Mariposa', 'Cóctel de ginebra, flor de guisante azul, flor de saúco y lima, con un delicado acabado cítrico.', 11.00, 'orbita_azul_ginebra.jpg', true),
(6, 'Vía Láctea de Coco y Vainilla', 'Bebida cremosa sin alcohol elaborada con leche de coco, vainilla natural y un toque de piña.', 7.50, 'via_lactea_coco.jpg', true),
(6, 'Eclipse Rubí de Hibisco y Granada', 'Refrescante combinación sin alcohol de hibisco, granada, frambuesa y agua con gas.', 6.50, 'eclipse_rubi_hibisco.jpg', true),
(6, 'Cometa Dorado de Manzana y Jengibre', 'Bebida sin alcohol de manzana natural, jengibre fresco, limón y un ligero toque de azafrán.', 6.50, 'cometa_dorado_jengibre.jpg', true),
(6, 'Nebulosa de Lichi y Jazmín', 'Infusión fría de té de jazmín con lichi, lima y un delicado toque de almíbar natural.', 6.00, 'nebulosa_lichi_jazmin.jpg', true),
(6, 'Eclipse de Café y Naranja', 'Café espresso frío combinado con naranja natural, tónica y un toque aromático de cardamomo.', 5.50, 'eclipse_cafe_naranja.jpg', true);

USE daw2mvc;

-- 1. Desactivar temporalmente claves foráneas y limpiar tablas
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE producto_ingrediente;
TRUNCATE TABLE ingredientes;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Insertar los 39 Extras / Ingredientes opcionales
INSERT INTO ingredientes (id, nombre, precio_extra, activo) VALUES 
(1, 'Láminas de trufa negra', 4.50, true),
(2, 'Caviar Oscietra', 6.50, true),
(3, 'Foie gras a la plancha', 5.00, true),
(4, 'Bogavante adicional', 7.50, true),
(5, 'Cigala a la brasa', 5.50, true),
(6, 'Langostino premium', 3.50, true),
(7, 'Setas de temporada', 2.50, true),
(8, 'Queso parmesano curado', 2.00, true),
(9, 'Huevo a baja temperatura', 2.00, true),
(10, 'Brotes y flores comestibles', 1.50, true),
(11, 'Frutos rojos frescos', 2.00, true),
(12, 'Frutos secos caramelizados', 1.50, true),
(13, 'Salsa de trufa negra', 2.00, true),
(14, 'Bisque de marisco', 2.50, true),
(15, 'Emulsión de azafrán', 2.00, true),
(16, 'Reducción de vino de Madeira', 2.00, true),
(17, 'Beurre blanc de limón', 1.50, true),
(18, 'Reducción de frutos rojos', 1.50, true),
(19, 'Caramelo salado', 1.50, true),
(20, 'Chocolate negro fundido', 1.50, true),
(21, 'Patatas fondant', 3.00, true),
(22, 'Verduras baby asadas', 3.50, true),
(23, 'Puré de patata trufado', 3.50, true),
(24, 'Espárragos a la brasa', 3.50, true),
(25, 'Arroz cremoso de parmesano', 3.50, true),
(26, 'Pan artesanal de masa madre', 1.80, true),
(27, 'Bola de helado de vainilla', 2.00, true),
(28, 'Bola de helado de pistacho', 2.50, true),
(29, 'Nube de nata montada', 1.00, true),
(30, 'Crumble de almendra', 1.20, true),
(31, 'Frambuesas adicionales', 1.80, true),
(32, 'Salsa de chocolate blanco', 1.50, true),
(33, 'Shot adicional de espresso', 1.50, true),
(34, 'Doble dosis de fruta', 1.50, true),
(35, 'Toque extra de jengibre', 0.80, true),
(36, 'Sirope de vainilla', 0.80, true),
(37, 'Extra de burbujas', 0.50, true),
(38, 'Hielo premium', 0.50, true),
(39, 'Rodajas cítricas', 0.50, true);

-- 3. Vincular los productos (ID 1-36) con sus extras compatibles
INSERT INTO producto_ingrediente (producto_id, ingrediente_id, cantidad) VALUES 
-- Entrantes
(1, 2, 1), (1, 10, 1), (1, 39, 1),        -- Nebulosa de Atún Rojo
(2, 5, 1), (2, 6, 1), (2, 10, 1),         -- Ceviche Orbital
(3, 1, 1), (3, 3, 1), (3, 13, 1),         -- Esfera Sideral de Wagyu
(4, 2, 1), (4, 5, 1), (4, 14, 1),         -- Mosaico Abisal
(5, 1, 1), (5, 7, 1), (5, 9, 1),          -- Nido Galáctico
(6, 5, 1), (6, 6, 1), (6, 15, 1),         -- Eclipse de Cigala

-- Primeros Platos
(7, 1, 1), (7, 4, 1), (7, 15, 1),         -- Ravioli de Bogavante
(8, 1, 1), (8, 4, 1), (8, 8, 1),          -- Risotto de Remolacha
(9, 4, 1), (9, 14, 1), (9, 8, 1),         -- Canelón Galáctico
(10, 2, 1), (10, 7, 1), (10, 10, 1),      -- Velouté Esmeralda
(11, 18, 1), (11, 21, 1), (11, 22, 1),    -- Confit Estelar de Pato
(12, 1, 1), (12, 7, 1), (12, 8, 1),       -- Gnocchi Áureos

-- Segundos Platos
(13, 1, 1), (13, 21, 1), (13, 22, 1),     -- Carré de Cordero
(14, 14, 1), (14, 15, 1), (14, 24, 1),    -- Lomo de Bacalao
(15, 18, 1), (15, 21, 1), (15, 22, 1),    -- Lomo de Ciervo
(16, 1, 1), (16, 3, 1), (16, 13, 1), (16, 21, 1), -- Solomillo de Ternera
(17, 7, 1), (17, 18, 1), (17, 22, 1),     -- Presa Ibérica
(18, 17, 1), (18, 24, 1), (18, 22, 1),    -- Lubina Salvaje

-- Guarniciones
(19, 1, 1), (19, 8, 1),                   -- Órbita Dorada de Patata
(20, 22, 1), (20, 24, 1),                 -- Zanahorias Aurora
(21, 17, 1), (21, 22, 1),                 -- Espárragos Estelares
(22, 1, 1), (22, 8, 1),                   -- Puré Lunar
(23, 1, 1), (23, 8, 1),                   -- Setas Nebulares
(24, 13, 1), (24, 8, 1),                  -- Meteoritos de Polenta

-- Postres
(25, 27, 1), (25, 29, 1), (25, 30, 1),    -- Eclipse de Chocolate
(26, 32, 1), (26, 30, 1), (26, 31, 1),    -- Esfera Lunar
(27, 28, 1), (27, 29, 1), (27, 31, 1),    -- Nebulosa de Pistacho
(28, 27, 1), (28, 19, 1), (28, 30, 1),    -- Anillo de Saturno
(29, 27, 1), (29, 33, 1),                 -- Crème Brûlée
(30, 32, 1), (30, 31, 1), (30, 30, 1),    -- Jardín Cósmico

-- Bebidas
(31, 37, 1), (31, 38, 1), (31, 39, 1),    -- Órbita Azul
(32, 36, 1), (32, 38, 1),                 -- Vía Láctea
(33, 34, 1), (33, 37, 1), (33, 39, 1),    -- Eclipse Rubí
(34, 35, 1), (34, 37, 1), (34, 39, 1),    -- Cometa Dorado
(35, 34, 1), (35, 37, 1), (35, 39, 1),    -- Nebulosa de Lichi
(36, 33, 1), (36, 36, 1), (36, 38, 1);     -- Eclipse de Café
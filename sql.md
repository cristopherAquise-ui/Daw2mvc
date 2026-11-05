USE daw2mvc;



\-- 1. Insertar / Actualizar las 5 Categorías oficiales

INSERT INTO categorias (id, nombre, descripcion, activa) VALUES 

(1, 'Entrantes', 'Para empezar el viaje espacial', true),

(2, 'Primeros Platos', 'El núcleo de la galaxia', true),

(3, 'Segundos Platos', 'Platos contundentes de otro planeta', true),

(4, 'Postres', 'Dulces meteoritos', true),

(5, 'Bebidas', 'Hidratación y elixires cósmicos', true)

ON DUPLICATE KEY UPDATE 

nombre=VALUES(nombre), descripcion=VALUES(descripcion), activa=VALUES(activa);



\-- 2. Limpiar la tabla de productos para reinsertar todo limpio

TRUNCATE TABLE productos;



\-- 3. ENTRANTES (categoria\_id = 1)

INSERT INTO productos (categoria\_id, nombre, descripcion, precio, imagen, activo) VALUES 

(1, 'Nebulosa de Atún Rojo', 'Finas láminas de atún marinado con crema de yuzu y sésamo estelar.', 18.50, 'nebulosa\_atun.jpg', true),

(1, 'Mosaico Abisal', 'Degustación profunda de sabores marinos con algas crujientes.', 15.00, 'mosaico\_abisal.jpg', true),

(1, 'Croquetas Supernova', 'Croquetas cremosas de jamón ibérico con rebozado crujiente galáctico (4 ud).', 9.50, 'croquetas\_supernova.jpg', true),

(1, 'Nachos Cósmicos', 'Totopos artesanos con queso cheddar fundido, guacamole y picante espacial.', 11.00, 'nachos\_cosmicos.jpg', true);



\-- 4. PRIMEROS PLATOS (categoria\_id = 2)

INSERT INTO productos (categoria\_id, nombre, descripcion, precio, imagen, activo) VALUES 

(2, 'Ramen Pulsar', 'Fideos artesanos en caldo concentrado de panceta, huevo marinado y verduras.', 14.50, 'ramen\_pulsar.jpg', true),

(2, 'Risotto Vía Láctea', 'Arroz meloso con setas silvestres, aceite de trufa blanca y parmesano.', 16.00, 'risotto\_via\_lactea.jpg', true),

(2, 'Ensalada Meteorito', 'Brotes tiernos, queso de cabra flameado, nueces y vinagreta de miel espacial.', 12.50, 'ensalada\_meteorito.jpg', true);



\-- 5. SEGUNDOS PLATOS (categoria\_id = 3)

INSERT INTO productos (categoria\_id, nombre, descripcion, precio, imagen, activo) VALUES 

(3, 'Burger Asteroide', '200g de carne de buey, queso cheddar, cebolla crujiente y salsa Star Bites.', 15.50, 'burger\_asteroide.jpg', true),

(3, 'Costillar Agujero Negro', 'Costillar de cerdo cocinado 12h a baja temperatura glaseado en salsa barbacoa.', 19.90, 'costillar\_agujero\_negro.jpg', true),

(3, 'Salmón Constelación', 'Lomo de salmón a la parrilla sobre cama de espárragos y emulsión de eneldo.', 17.50, 'salmon\_constelacion.jpg', true);



\-- 6. POSTRES (categoria\_id = 4)

INSERT INTO productos (categoria\_id, nombre, descripcion, precio, imagen, activo) VALUES 

(4, 'Tarta Cero Gravedad', 'Tarta de queso cremosa al horno con coulis de frutos rojos estelares.', 6.50, 'tarta\_cero\_gravedad.jpg', true),

(4, 'Coulant Big Bang', 'Bizcocho fluido de chocolate negro con helado de vainilla de Madagascar.', 7.00, 'coulant\_big\_bang.jpg', true),

(4, 'Polvo de Estrellas', 'Copa de helado artesano con rocas de chocolate y crujiente de galleta.', 5.50, 'polvo\_estrellas.jpg', true);



\-- 7. BEBIDAS (categoria\_id = 5)

INSERT INTO productos (categoria\_id, nombre, descripcion, precio, imagen, activo) VALUES 

(5, 'Cóctel Plasma Verde', 'Refrescante combinación de lima, menta, kiwi y un toque efervescente.', 6.50, 'coctel\_plasma.jpg', true),

(5, 'Cerveza Galáctica IPA', 'Cerveza artesana de lúpulo intenso con notas cítricas.', 4.00, 'cerveza\_galactica.jpg', true),

(5, 'Agua Mineral Estelar', 'Agua mineral natural de manantial (50cl).', 2.50, 'agua\_mineral.jpg', true),

(5, 'Refresco Orbital Cola', 'Bebida carbonatada clásica bien fría.', 3.00, 'orbital\_cola.jpg', true);


USE daw2mvc;

-- 1. Insertar Categorías
INSERT INTO categorias (nombre, descripcion, activa) VALUES 
('Entrantes', 'Para empezar el viaje espacial', true),
('Primeros Platos', 'El núcleo de la galaxia', true),
('Segundos Platos', 'Platos contundentes de otro planeta', true),
('Postres', 'Dulces meteoritos', true);

-- 2. Insertar Productos (El categoria_id = 1 corresponde a "Entrantes")
INSERT INTO productos (categoria_id, nombre, descripcion, precio, imagen, activo) VALUES 
(1, 'Nebulosa de Atún Rojo y Yuzu', 'Finas láminas de atún con un toque cítrico estelar.', 18.50, 'nebulosa_atun.jpg', true),
(1, 'Mosaico Abisal', 'Degustación profunda de sabores marinos.', 15.00, 'mosaico_abisal.jpg', true);

-- 3. Insertar Usuarios de prueba (Las contraseñas irían hasheadas en un entorno real)
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@starbites.com', 'admin123', 'admin'),
('Cliente Prueba', 'cliente@tierra.com', '1234', 'usuario');

-- 4. Insertar Ingredientes extra
INSERT INTO ingredientes (nombre, precio_extra, activo) VALUES 
('Salsa Yuzu extra', 2.00, true),
('Picante espacial', 1.50, true);
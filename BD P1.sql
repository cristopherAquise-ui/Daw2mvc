CREATE TABLE `usuarios` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20),
  `direccion` varchar(255),
  `fecha_nacimiento` date,
  `rol` varchar(20) NOT NULL DEFAULT 'usuario' COMMENT 'Valores: usuario, admin',
  `fecha_registro` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `categorias` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `descripcion` text,
  `activa` boolean NOT NULL DEFAULT true
);

CREATE TABLE `productos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `categoria_id` int NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255),
  `activo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `ingredientes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT 0,
  `activo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `producto_ingrediente` (
  `producto_id` int NOT NULL,
  `ingrediente_id` int NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`producto_id`, `ingrediente_id`)
);

CREATE TABLE `ofertas` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `tipo` varchar(20) NOT NULL COMMENT 'Valores: descuento, 2x1, combo, codigo',
  `valor` decimal(10,2),
  `codigo` varchar(50) UNIQUE,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime,
  `activa` boolean NOT NULL DEFAULT true
);

CREATE TABLE `oferta_producto` (
  `oferta_id` int NOT NULL,
  `producto_id` int NOT NULL,
  PRIMARY KEY (`oferta_id`, `producto_id`)
);

CREATE TABLE `pedidos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente' COMMENT 'Valores: pendiente, confirmado, preparando, enviado, entregado, cancelado',
  `fecha_pedido` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `subtotal` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL DEFAULT 'simulado',
  `estado_pago` varchar(20) NOT NULL DEFAULT 'pendiente' COMMENT 'Valores: pendiente, pagado, fallido'
);

CREATE TABLE `pedido_detalle` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `producto_id` int,
  `nombre_producto` varchar(150) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL COMMENT 'CHECK cantidad > 0 y precio_unitario >= 0'
);

CREATE TABLE `pedido_detalle_ingrediente` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pedido_detalle_id` int NOT NULL,
  `ingrediente_id` int,
  `nombre_ingrediente` varchar(100) NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT 0,
  `accion` varchar(20) NOT NULL COMMENT 'Valores: incluido, quitado, añadido'
);

ALTER TABLE `productos` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

ALTER TABLE `producto_ingrediente` ADD FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

ALTER TABLE `producto_ingrediente` ADD FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`);

ALTER TABLE `oferta_producto` ADD FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`);

ALTER TABLE `oferta_producto` ADD FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

ALTER TABLE `pedidos` ADD FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

ALTER TABLE `pedido_detalle` ADD FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`);

ALTER TABLE `pedido_detalle` ADD FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

ALTER TABLE `pedido_detalle_ingrediente` ADD FOREIGN KEY (`pedido_detalle_id`) REFERENCES `pedido_detalle` (`id`);

ALTER TABLE `pedido_detalle_ingrediente` ADD FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`);

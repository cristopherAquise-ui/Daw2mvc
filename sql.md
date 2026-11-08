Script definitivo (copiar y pegar en la terminal MySQL)
-- ============================================================
-- StarBites - Base de datos definitiva 
-- MySQL 8+/MariaDB 10.5+. Importar pegando todo en la consola.
-- Contenido: 14 tablas, 36 productos, 39 extras, ingredientes base,
-- ofertas, usuarios de prueba y 2 pedidos de ejemplo.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `daw2mvc`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `daw2mvc`;

DROP TABLE IF EXISTS `pedido_detalle_ingrediente`;
DROP TABLE IF EXISTS `pedido_detalle`;
DROP TABLE IF EXISTS `pedidos`;
DROP TABLE IF EXISTS `carrito_detalle_ingrediente`;
DROP TABLE IF EXISTS `carrito_detalle`;
DROP TABLE IF EXISTS `carrito`;
DROP TABLE IF EXISTS `api_tokens`;
DROP TABLE IF EXISTS `oferta_producto`;
DROP TABLE IF EXISTS `ofertas`;
DROP TABLE IF EXISTS `producto_ingrediente`;
DROP TABLE IF EXISTS `ingredientes`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `usuarios`;

-- ------------------------------------------------------------
-- 1. USUARIOS
-- ------------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `email_verificado` tinyint(1) NOT NULL DEFAULT '0',
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuarios_email` (`email`),
  KEY `idx_usuarios_rol` (`rol`),
  KEY `idx_usuarios_activo` (`activo`),
  CONSTRAINT `chk_usuarios_rol` CHECK (`rol` IN ('usuario','admin'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. CATEGORIAS
-- ------------------------------------------------------------
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `imagen` varchar(255) DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categorias_nombre` (`nombre`),
  KEY `idx_categorias_activa_orden` (`activa`,`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. PRODUCTOS
-- ------------------------------------------------------------
CREATE TABLE `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria_id` int NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `destacado` tinyint(1) NOT NULL DEFAULT '0',
  `orden` int NOT NULL DEFAULT '0',
  `iva_porcentaje` decimal(5,2) NOT NULL DEFAULT '10.00',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_productos_categoria_activo` (`categoria_id`,`activo`),
  KEY `idx_productos_nombre` (`nombre`),
  KEY `idx_productos_precio` (`precio`),
  KEY `idx_productos_destacado` (`destacado`,`activo`),
  CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `chk_productos_precio` CHECK (`precio` >= 0),
  CONSTRAINT `chk_productos_iva` CHECK (`iva_porcentaje` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. INGREDIENTES
-- ------------------------------------------------------------
CREATE TABLE `ingredientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ingredientes_nombre` (`nombre`),
  KEY `idx_ingredientes_activo` (`activo`),
  CONSTRAINT `chk_ingredientes_precio` CHECK (`precio_extra` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. RELACION PRODUCTO - INGREDIENTE
-- ------------------------------------------------------------
CREATE TABLE `producto_ingrediente` (
  `producto_id` int NOT NULL,
  `ingrediente_id` int NOT NULL,
  `tipo` enum('base','extra') NOT NULL DEFAULT 'extra',
  `incluido_por_defecto` tinyint(1) NOT NULL DEFAULT '0',
  `obligatorio` tinyint(1) NOT NULL DEFAULT '0',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '1.00',
  PRIMARY KEY (`producto_id`,`ingrediente_id`),
  KEY `idx_pi_ingrediente` (`ingrediente_id`),
  KEY `idx_pi_producto_tipo` (`producto_id`,`tipo`),
  CONSTRAINT `fk_pi_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pi_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_pi_cantidad` CHECK (`cantidad` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. OFERTAS
-- ------------------------------------------------------------
CREATE TABLE `ofertas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `tipo` enum('descuento','2x1','combo','codigo') NOT NULL,
  `ambito` enum('producto','categoria','pedido') NOT NULL DEFAULT 'producto',
  `categoria_id` int DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `cantidad_minima` int NOT NULL DEFAULT '1',
  `importe_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ofertas_codigo` (`codigo`),
  KEY `idx_ofertas_vigencia` (`activa`,`fecha_inicio`,`fecha_fin`),
  KEY `idx_ofertas_ambito` (`ambito`,`categoria_id`),
  CONSTRAINT `fk_ofertas_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `chk_ofertas_valor` CHECK (`valor` IS NULL OR `valor` >= 0),
  CONSTRAINT `chk_ofertas_cantidad` CHECK (`cantidad_minima` >= 1),
  CONSTRAINT `chk_ofertas_importe` CHECK (`importe_minimo` >= 0),
  CONSTRAINT `chk_ofertas_fechas` CHECK (`fecha_fin` IS NULL OR `fecha_fin` >= `fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `oferta_producto` (
  `oferta_id` int NOT NULL,
  `producto_id` int NOT NULL,
  PRIMARY KEY (`oferta_id`,`producto_id`),
  KEY `idx_op_producto` (`producto_id`),
  CONSTRAINT `fk_op_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_op_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. TOKENS DE API (PANEL DE ADMINISTRACION)
-- ------------------------------------------------------------
CREATE TABLE `api_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `token` char(64) NOT NULL,
  `creado_en` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expira_en` datetime NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_api_tokens_token` (`token`),
  KEY `idx_api_tokens_usuario` (`usuario_id`),
  KEY `idx_api_tokens_expira` (`expira_en`),
  CONSTRAINT `fk_api_tokens_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. CARRITO
-- ------------------------------------------------------------
CREATE TABLE `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `sesion_id` varchar(128) NOT NULL,
  `estado` enum('activo','convertido','abandonado') NOT NULL DEFAULT 'activo',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_carrito_sesion` (`sesion_id`),
  KEY `idx_carrito_usuario` (`usuario_id`),
  KEY `idx_carrito_estado` (`estado`),
  CONSTRAINT `fk_carrito_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `carrito_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `carrito_id` int NOT NULL,
  `producto_id` int DEFAULT NULL,
  `oferta_id` int DEFAULT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `iva_porcentaje` decimal(5,2) NOT NULL DEFAULT '10.00',
  PRIMARY KEY (`id`),
  KEY `idx_cd_carrito` (`carrito_id`),
  KEY `idx_cd_producto` (`producto_id`),
  KEY `idx_cd_oferta` (`oferta_id`),
  CONSTRAINT `fk_cd_carrito` FOREIGN KEY (`carrito_id`) REFERENCES `carrito` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cd_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cd_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chk_cd_cantidad` CHECK (`cantidad` > 0),
  CONSTRAINT `chk_cd_precio` CHECK (`precio_unitario` >= 0),
  CONSTRAINT `chk_cd_subtotal` CHECK (`subtotal` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `carrito_detalle_ingrediente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `carrito_detalle_id` int NOT NULL,
  `ingrediente_id` int DEFAULT NULL,
  `nombre_ingrediente` varchar(100) NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '1.00',
  `accion` enum('incluido','quitado','añadido') NOT NULL DEFAULT 'incluido',
  PRIMARY KEY (`id`),
  KEY `idx_cdi_detalle` (`carrito_detalle_id`),
  KEY `idx_cdi_ingrediente` (`ingrediente_id`),
  CONSTRAINT `fk_cdi_detalle` FOREIGN KEY (`carrito_detalle_id`) REFERENCES `carrito_detalle` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cdi_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chk_cdi_cantidad` CHECK (`cantidad` > 0),
  CONSTRAINT `chk_cdi_precio` CHECK (`precio_extra` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. PEDIDOS
-- ------------------------------------------------------------
CREATE TABLE `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `estado` enum('pendiente','confirmado','preparando','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `fecha_pedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `base_imponible` decimal(10,2) NOT NULL DEFAULT '0.00',
  `iva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('simulado','tarjeta','efectivo','paypal','transferencia') NOT NULL DEFAULT 'simulado',
  `estado_pago` enum('pendiente','pagado','fallido','reembolsado') NOT NULL DEFAULT 'pendiente',
  `direccion_entrega` varchar(255) DEFAULT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `notas` text,
  `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pedidos_usuario_fecha` (`usuario_id`,`fecha_pedido`),
  KEY `idx_pedidos_estado` (`estado`),
  KEY `idx_pedidos_fecha` (`fecha_pedido`),
  KEY `idx_pedidos_estado_pago` (`estado_pago`),
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_pedidos_subtotal` CHECK (`subtotal` >= 0),
  CONSTRAINT `chk_pedidos_descuento` CHECK (`descuento` >= 0),
  CONSTRAINT `chk_pedidos_total` CHECK (`total` >= 0),
  CONSTRAINT `chk_pedidos_iva` CHECK (`iva` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pedido_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `producto_id` int DEFAULT NULL,
  `oferta_id` int DEFAULT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `iva_porcentaje` decimal(5,2) NOT NULL DEFAULT '10.00',
  PRIMARY KEY (`id`),
  KEY `idx_pd_pedido` (`pedido_id`),
  KEY `idx_pd_producto` (`producto_id`),
  KEY `idx_pd_oferta` (`oferta_id`),
  CONSTRAINT `fk_pd_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pd_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pd_oferta` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chk_pd_cantidad` CHECK (`cantidad` > 0),
  CONSTRAINT `chk_pd_precio` CHECK (`precio_unitario` >= 0),
  CONSTRAINT `chk_pd_subtotal` CHECK (`subtotal` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pedido_detalle_ingrediente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_detalle_id` int NOT NULL,
  `ingrediente_id` int DEFAULT NULL,
  `nombre_ingrediente` varchar(100) NOT NULL,
  `precio_extra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cantidad` decimal(10,2) NOT NULL DEFAULT '1.00',
  `accion` enum('incluido','quitado','añadido') NOT NULL DEFAULT 'incluido',
  PRIMARY KEY (`id`),
  KEY `idx_pdi_detalle` (`pedido_detalle_id`),
  KEY `idx_pdi_ingrediente` (`ingrediente_id`),
  CONSTRAINT `fk_pdi_detalle` FOREIGN KEY (`pedido_detalle_id`) REFERENCES `pedido_detalle` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pdi_ingrediente` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `chk_pdi_cantidad` CHECK (`cantidad` > 0),
  CONSTRAINT `chk_pdi_precio` CHECK (`precio_extra` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATOS INICIALES
-- ============================================================

-- Usuarios de prueba: admin@starbites.gal / Admin123!  (admin)
--                     cliente@starbites.gal / Cliente123! (usuario)

INSERT INTO `usuarios` VALUES (1,'Administrador','StarBites','admin@starbites.gal','$2y$10$wQz9gIkwGn4BNgHNVdiESOkCKfwmDCDlCNh3L4RYTty9BCYQ0lLHq','+34 900 113 800','Sector Corellia, Bloque 4','Coruscant','00000','1990-01-01','admin',1,1,NULL,'2026-11-01 10:00:00'),(2,'Cliente','Prueba','cliente@starbites.gal','$2y$10$c0iBiveu0kcSSpL2A3z2ZO2axXfoB0fNGge7dOfJdTsM.z6LrMqGC','+34 600 000 000','Calle Mayor 1','Madrid','28001','1995-05-05','usuario',1,1,'2026-11-06 20:55:00','2026-11-01 10:05:00');

INSERT INTO `categorias` VALUES (1,'Entrantes / Aperitivos','Para comenzar el viaje sensorial galáctico.',NULL,1,1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(2,'Primeros platos','El núcleo de la gastronomía estelar.',NULL,1,2,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(3,'Segundos platos','Creaciones contundentes de alta cocina galáctica.',NULL,1,3,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(4,'Guarniciones / Acompañamientos','Complementos perfectos para acompañar tu experiencia.',NULL,1,4,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(5,'Postres','Dulces meteoritos y finales estelares.',NULL,1,5,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(6,'Bebidas','Elixires, cócteles y combinados cósmicos.',NULL,1,6,'2026-11-01 10:00:00','2026-11-01 10:00:00');

INSERT INTO `productos` VALUES (1,1,'Nebulosa de Atún Rojo y Yuzu','Tartar de atún rojo marinado en yuzu, acompañado de cítricos, hierbas frescas y perlas de sabor marino.',18.50,'entrantes/nebulosa_atun_yuzu.jpg',1,1,1,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(2,1,'Ceviche Orbital de Carabinero y Maracuyá','Carabinero fresco con leche de tigre de maracuyá, cilantro y matices cítricos.',19.00,'entrantes/ceviche_orbital_carabinero.jpg',1,0,2,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(3,1,'Esfera Sideral de Wagyu, Trufa Negra y Foie Gras','Wagyu de textura delicada combinado con foie gras y trufa negra, presentado en una elegante esfera gastronómica.',22.00,'entrantes/esfera_sideral_wagyu.jpg',1,1,3,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(4,1,'Mosaico Abisal de Pulpo y Erizo','Finas láminas de pulpo acompañadas de erizo de mar, aceite de hierbas y pequeños contrastes cítricos.',17.50,'entrantes/mosaico_abisal_pulpo.jpg',1,0,4,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(5,1,'Nido Galáctico de Huevo a 63° y Trufa Blanca','Huevo cocinado a baja temperatura sobre un nido crujiente, con setas y delicados aromas de trufa blanca.',15.00,'entrantes/nido_galactico_huevo.jpg',1,0,5,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(6,1,'Eclipse de Cigala y Rubí Cítrico','Cigala a la plancha sobre una crema de aguacate, acompañada de una reducción cítrica de color rubí.',20.00,'entrantes/eclipse_cigala_rubi.jpg',1,0,6,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');
INSERT INTO `productos` VALUES (7,2,'Ravioli de Medianoche con Bogavante y Emulsión de Azafrán','Raviolis rellenos de bogavante con una suave emulsión de azafrán y notas de mar.',21.00,'primeros/ravioli_medianoche_bogavante.jpg',1,0,7,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(8,2,'Risotto Nebulosa de Remolacha y Langosta','Risotto cremoso de remolacha con langosta, queso parmesano y un delicado contraste de sabores marinos.',23.50,'primeros/risotto_nebulosa_langosta.jpg',1,1,8,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(9,2,'Canelón Galáctico Txangurro y Bisque de Coral','Canelón relleno de centollo con una intensa salsa de bisque de marisco y coral.',19.50,'primeros/canelon_galactico_txangurro.jpg',1,0,9,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(10,2,'Velouté Esmeralda de Guisantes y Plancton con Caviar Beluga','Crema fina de guisantes y plancton marino, terminada con caviar beluga y brotes frescos.',24.00,'primeros/veloute_esmeralda_caviar.jpg',1,0,10,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(11,2,'Confit Estelar de Pato y Cerezas Negras','Pato confitado lentamente sobre una base cremosa, acompañado de cerezas negras y una reducción de su propio jugo.',18.50,'primeros/confit_estelar_pato.jpg',1,0,11,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(12,2,'Gnocchi Áureos con Emulsión de Berros y Trufa Negra','Gnocchi dorados de patata con emulsión de berros, trufa negra y pequeños toques de queso curado.',16.50,'primeros/gnocchi_aureos_trufa.jpg',1,0,12,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');
INSERT INTO `productos` VALUES (13,3,'Carré de Cordero en Eclipse de Ajo Negro y Remolacha','Carré de cordero asado con costra de hierbas, acompañado de crema de remolacha y salsa de ajo negro.',26.00,'segundos/carre_cordero_eclipse.jpg',1,1,13,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(14,3,'Lomo de Bacalao Astral con Coral de Tinta y Azafrán','Lomo de bacalao confitado con una salsa de tinta de calamar, crema de azafrán y delicados brotes marinos.',24.00,'segundos/lomo_bacalao_astral.jpg',1,0,14,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(15,3,'Lomo de Ciervo en Nebulosa de Zarzamora','Lomo de ciervo asado al punto, acompañado de una reducción de zarzamoras y crema suave de raíces.',27.50,'segundos/lomo_ciervo_nebulosa.jpg',1,0,15,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(16,3,'Solomillo de Ternera en Órbita de Madeira y Café','Solomillo de ternera a la parrilla con salsa de Madeira y café tostado, acompañado de patatas fondant.',28.00,'segundos/solomillo_orbita_madeira.jpg',1,1,16,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(17,3,'Presa Ibérica Lacada en Miso de Castañas y Manzana','Presa ibérica cocinada lentamente y lacada con miso de castañas, acompañada de manzana asada y jugo de carne.',23.00,'segundos/presa_iberica_miso.jpg',1,0,17,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(18,3,'Lubina Salvaje en Aurora de Limón y Mantequilla','Lubina salvaje a la brasa con beurre blanc de limón, hinojo asado y verduras de temporada.',25.50,'segundos/lubina_salvaje_aurora.jpg',1,0,18,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');
INSERT INTO `productos` VALUES (19,4,'Órbita Dorada de Patata Anna y Azafrán','Finas capas de patata horneadas con mantequilla avellanada, azafrán y una textura exterior crujiente.',7.50,'guarniciones/orbita_dorada_patata.jpg',1,0,19,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(20,4,'Zanahorias Aurora Glaseadas con Miel de Azahar','Zanahorias baby asadas y glaseadas con miel de azahar, mantequilla y un toque de comino.',6.50,'guarniciones/zanahorias_aurora_miel.jpg',1,0,20,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(21,4,'Espárragos Estelares a la Brasa con Holandesa de Limón','Espárragos verdes a la parrilla acompañados de una salsa holandesa ligera y ralladura de limón.',8.00,'guarniciones/esparragos_estelares_brasa.jpg',1,0,21,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(22,4,'Puré Lunar de Apionabo y Vainilla','Crema sedosa de apionabo con mantequilla, vainilla natural y un delicado toque de pimienta blanca.',7.00,'guarniciones/pure_lunar_apionabo.jpg',1,0,22,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(23,4,'Setas Nebulares con Ajo Negro y Tomillo','Selección de setas de temporada salteadas con ajo negro, tomillo fresco y aceite de oliva virgen extra.',8.50,'guarniciones/setas_nebulares_ajo.jpg',1,0,23,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(24,4,'Meteoritos Crujientes de Polenta y Parmesano','Pequeños bocados de polenta dorada con parmesano, trufa y una textura crujiente por fuera y cremosa por dentro.',7.50,'guarniciones/meteoritos_polenta_parmesano.jpg',1,0,24,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');
INSERT INTO `productos` VALUES (25,5,'Eclipse de Chocolate Guanaja y Frambuesa','Esfera de chocolate negro rellena de mousse de chocolate y corazón de frambuesa, con una base crujiente de avellana.',9.50,'postres/eclipse_chocolate_guanaja.jpg',1,1,25,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(26,5,'Esfera Lunar de Yuzu y Chocolate Blanco','Mousse de chocolate blanco con núcleo cítrico de yuzu, acompañado de crumble de almendra y gel de limón.',9.00,'postres/esfera_lunar_yuzu.jpg',1,0,26,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(27,5,'Nebulosa de Pistacho, Frambuesa y Rosa','Cremoso de pistacho con frambuesas frescas, bizcocho ligero y delicados aromas de agua de rosas.',8.50,'postres/nebulosa_pistacho_rosa.jpg',1,0,27,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(28,5,'Anillo de Saturno de Manzana y Caramelo Salado','Manzana caramelizada sobre hojaldre crujiente, acompañada de helado de vainilla y salsa de caramelo salado.',8.50,'postres/anillo_saturno_manzana.jpg',1,0,28,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(29,5,'Crème Brûlée Estelar de Vainilla de Madagascar','Crema tradicional de vainilla de Madagascar con una fina capa de azúcar caramelizado y notas de café.',7.50,'postres/creme_brulee_estelar.jpg',1,0,29,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(30,5,'Jardín Cósmico de Coco, Mango y Lima','Mousse de coco con corazón de mango, gel de lima y crumble tropical de almendras.',8.00,'postres/jardin_cosmico_coco.jpg',1,0,30,10.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');
INSERT INTO `productos` VALUES (31,6,'Órbita Azul de Ginebra y Flor de Mariposa','Cóctel de ginebra, flor de guisante azul, flor de saúco y lima, con un delicado acabado cítrico.',11.00,'bebidas/orbita_azul_ginebra.jpg',1,1,31,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(32,6,'Vía Láctea de Coco y Vainilla','Bebida cremosa sin alcohol elaborada con leche de coco, vainilla natural y un toque de piña.',7.50,'bebidas/via_lactea_coco.jpg',1,0,32,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(33,6,'Eclipse Rubí de Hibisco y Granada','Refrescante combinación sin alcohol de hibisco, granada, frambuesa y agua con gas.',6.50,'bebidas/eclipse_rubi_hibisco.jpg',1,0,33,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(34,6,'Cometa Dorado de Manzana y Jengibre','Bebida sin alcohol de manzana natural, jengibre fresco, limón y un ligero toque de azafrán.',6.50,'bebidas/cometa_dorado_jengibre.jpg',1,0,34,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(35,6,'Nebulosa de Lichi y Jazmín','Infusión fría de té de jazmín con lichi, lima y un delicado toque de almíbar natural.',6.00,'bebidas/nebulosa_lichi_jazmin.jpg',1,0,35,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(36,6,'Eclipse de Café y Naranja','Café espresso frío combinado con naranja natural, tónica y un toque aromático de cardamomo.',5.50,'bebidas/eclipse_cafe_naranja.jpg',1,0,36,21.00,'2026-11-01 10:00:00','2026-11-01 10:00:00');

INSERT INTO `ingredientes` VALUES (1,'Láminas de trufa negra',4.50,1,'2026-11-01 10:00:00'),(2,'Caviar Oscietra',6.50,1,'2026-11-01 10:00:00'),(3,'Foie gras a la plancha',5.00,1,'2026-11-01 10:00:00'),(4,'Bogavante adicional',7.50,1,'2026-11-01 10:00:00'),(5,'Cigala a la brasa',5.50,1,'2026-11-01 10:00:00'),(6,'Langostino premium',3.50,1,'2026-11-01 10:00:00'),(7,'Setas de temporada',2.50,1,'2026-11-01 10:00:00'),(8,'Queso parmesano curado',2.00,1,'2026-11-01 10:00:00'),(9,'Huevo a baja temperatura',2.00,1,'2026-11-01 10:00:00'),(10,'Brotes y flores comestibles',1.50,1,'2026-11-01 10:00:00'),(11,'Frutos rojos frescos',2.00,1,'2026-11-01 10:00:00'),(12,'Frutos secos caramelizados',1.50,1,'2026-11-01 10:00:00'),(13,'Salsa de trufa negra',2.00,1,'2026-11-01 10:00:00'),(14,'Bisque de marisco',2.50,1,'2026-11-01 10:00:00'),(15,'Emulsión de azafrán',2.00,1,'2026-11-01 10:00:00'),(16,'Reducción de vino de Madeira',2.00,1,'2026-11-01 10:00:00'),(17,'Beurre blanc de limón',1.50,1,'2026-11-01 10:00:00'),(18,'Reducción de frutos rojos',1.50,1,'2026-11-01 10:00:00'),(19,'Caramelo salado',1.50,1,'2026-11-01 10:00:00'),(20,'Chocolate negro fundido',1.50,1,'2026-11-01 10:00:00'),(21,'Patatas fondant',3.00,1,'2026-11-01 10:00:00'),(22,'Verduras baby asadas',3.50,1,'2026-11-01 10:00:00'),(23,'Puré de patata trufado',3.50,1,'2026-11-01 10:00:00'),(24,'Espárragos a la brasa',3.50,1,'2026-11-01 10:00:00'),(25,'Arroz cremoso de parmesano',3.50,1,'2026-11-01 10:00:00'),(26,'Pan artesanal de masa madre',1.80,1,'2026-11-01 10:00:00'),(27,'Bola de helado de vainilla',2.00,1,'2026-11-01 10:00:00');
INSERT INTO `ingredientes` VALUES (28,'Bola de helado de pistacho',2.50,1,'2026-11-01 10:00:00'),(29,'Nube de nata montada',1.00,1,'2026-11-01 10:00:00'),(30,'Crumble de almendra',1.20,1,'2026-11-01 10:00:00'),(31,'Frambuesas adicionales',1.80,1,'2026-11-01 10:00:00'),(32,'Salsa de chocolate blanco',1.50,1,'2026-11-01 10:00:00'),(33,'Shot adicional de espresso',1.50,1,'2026-11-01 10:00:00'),(34,'Doble dosis de fruta',1.50,1,'2026-11-01 10:00:00'),(35,'Toque extra de jengibre',0.80,1,'2026-11-01 10:00:00'),(36,'Sirope de vainilla',0.80,1,'2026-11-01 10:00:00'),(37,'Extra de burbujas',0.50,1,'2026-11-01 10:00:00'),(38,'Hielo premium',0.50,1,'2026-11-01 10:00:00'),(39,'Rodajas cítricas',0.50,1,'2026-11-01 10:00:00'),(40,'Atún rojo',0.00,1,'2026-11-01 10:00:00'),(41,'Yuzu',0.00,1,'2026-11-01 10:00:00'),(42,'Cítricos',0.00,1,'2026-11-01 10:00:00'),(43,'Hierbas frescas',0.00,1,'2026-11-01 10:00:00'),(44,'Carabinero',0.00,1,'2026-11-01 10:00:00'),(45,'Leche de tigre',0.00,1,'2026-11-01 10:00:00'),(46,'Cilantro',0.00,1,'2026-11-01 10:00:00'),(47,'Maracuyá',0.00,1,'2026-11-01 10:00:00'),(48,'Wagyu',0.00,1,'2026-11-01 10:00:00'),(49,'Foie gras',0.00,1,'2026-11-01 10:00:00'),(50,'Trufa negra',0.00,1,'2026-11-01 10:00:00'),(51,'Huevo',0.00,1,'2026-11-01 10:00:00'),(52,'Pulpo',0.00,1,'2026-11-01 10:00:00'),(53,'Erizo de mar',0.00,1,'2026-11-01 10:00:00'),(54,'Aceite de hierbas',0.00,1,'2026-11-01 10:00:00'),(55,'Setas frescas',0.00,1,'2026-11-01 10:00:00'),(56,'Trufa blanca',0.00,1,'2026-11-01 10:00:00'),(57,'Nido crujiente',0.00,1,'2026-11-01 10:00:00');
INSERT INTO `ingredientes` VALUES (58,'Cigala',0.00,1,'2026-11-01 10:00:00'),(59,'Aguacate',0.00,1,'2026-11-01 10:00:00'),(60,'Reducción cítrica',0.00,1,'2026-11-01 10:00:00'),(61,'Bogavante',0.00,1,'2026-11-01 10:00:00'),(62,'Ravioli',0.00,1,'2026-11-01 10:00:00'),(63,'Azafrán',0.00,1,'2026-11-01 10:00:00'),(64,'Nata',0.00,1,'2026-11-01 10:00:00'),(65,'Remolacha',0.00,1,'2026-11-01 10:00:00'),(66,'Langosta',0.00,1,'2026-11-01 10:00:00'),(67,'Arroz',0.00,1,'2026-11-01 10:00:00'),(68,'Parmesano',0.00,1,'2026-11-01 10:00:00'),(69,'Centollo',0.00,1,'2026-11-01 10:00:00'),(70,'Canelón',0.00,1,'2026-11-01 10:00:00'),(71,'Bisque',0.00,1,'2026-11-01 10:00:00'),(72,'Coral',0.00,1,'2026-11-01 10:00:00'),(73,'Guisantes',0.00,1,'2026-11-01 10:00:00'),(74,'Plancton',0.00,1,'2026-11-01 10:00:00'),(75,'Caviar beluga',0.00,1,'2026-11-01 10:00:00'),(76,'Pato',0.00,1,'2026-11-01 10:00:00'),(77,'Cerezas negras',0.00,1,'2026-11-01 10:00:00'),(78,'Jugo de pato',0.00,1,'2026-11-01 10:00:00'),(79,'Gnocchi',0.00,1,'2026-11-01 10:00:00'),(80,'Berros',0.00,1,'2026-11-01 10:00:00'),(81,'Queso curado',0.00,1,'2026-11-01 10:00:00'),(82,'Cordero',0.00,1,'2026-11-01 10:00:00'),(83,'Ajo negro',0.00,1,'2026-11-01 10:00:00'),(84,'Bacalao',0.00,1,'2026-11-01 10:00:00'),(85,'Tinta de calamar',0.00,1,'2026-11-01 10:00:00'),(86,'Ciervo',0.00,1,'2026-11-01 10:00:00'),(87,'Zarzamora',0.00,1,'2026-11-01 10:00:00'),(88,'Raíces',0.00,1,'2026-11-01 10:00:00'),(89,'Ternera',0.00,1,'2026-11-01 10:00:00'),(90,'Madeira',0.00,1,'2026-11-01 10:00:00'),(91,'Café',0.00,1,'2026-11-01 10:00:00');
INSERT INTO `ingredientes` VALUES (92,'Patata',0.00,1,'2026-11-01 10:00:00'),(93,'Presa ibérica',0.00,1,'2026-11-01 10:00:00'),(94,'Miso',0.00,1,'2026-11-01 10:00:00'),(95,'Castañas',0.00,1,'2026-11-01 10:00:00'),(96,'Manzana',0.00,1,'2026-11-01 10:00:00'),(97,'Lubina',0.00,1,'2026-11-01 10:00:00'),(98,'Limón',0.00,1,'2026-11-01 10:00:00'),(99,'Hinojo',0.00,1,'2026-11-01 10:00:00'),(100,'Verduras',0.00,1,'2026-11-01 10:00:00'),(101,'Mantequilla',0.00,1,'2026-11-01 10:00:00'),(102,'Zanahoria',0.00,1,'2026-11-01 10:00:00'),(103,'Miel',0.00,1,'2026-11-01 10:00:00'),(104,'Comino',0.00,1,'2026-11-01 10:00:00'),(105,'Espárragos',0.00,1,'2026-11-01 10:00:00'),(106,'Holandesa',0.00,1,'2026-11-01 10:00:00'),(107,'Apionabo',0.00,1,'2026-11-01 10:00:00'),(108,'Vainilla',0.00,1,'2026-11-01 10:00:00'),(109,'Setas salteadas',0.00,1,'2026-11-01 10:00:00'),(110,'Tomillo',0.00,1,'2026-11-01 10:00:00'),(111,'Polenta',0.00,1,'2026-11-01 10:00:00'),(112,'Chocolate',0.00,1,'2026-11-01 10:00:00'),(113,'Frambuesa',0.00,1,'2026-11-01 10:00:00'),(114,'Avellana',0.00,1,'2026-11-01 10:00:00'),(115,'Chocolate blanco',0.00,1,'2026-11-01 10:00:00'),(116,'Almendra',0.00,1,'2026-11-01 10:00:00'),(117,'Pistacho',0.00,1,'2026-11-01 10:00:00'),(118,'Rosa',0.00,1,'2026-11-01 10:00:00'),(119,'Caramelo',0.00,1,'2026-11-01 10:00:00'),(120,'Hojaldre',0.00,1,'2026-11-01 10:00:00'),(121,'Azúcar',0.00,1,'2026-11-01 10:00:00'),(122,'Coco',0.00,1,'2026-11-01 10:00:00'),(123,'Mango',0.00,1,'2026-11-01 10:00:00'),(124,'Lima',0.00,1,'2026-11-01 10:00:00'),(125,'Ginebra',0.00,1,'2026-11-01 10:00:00');
INSERT INTO `ingredientes` VALUES (126,'Flor de guisante azul',0.00,1,'2026-11-01 10:00:00'),(127,'Flor de saúco',0.00,1,'2026-11-01 10:00:00'),(128,'Leche de coco',0.00,1,'2026-11-01 10:00:00'),(129,'Piña',0.00,1,'2026-11-01 10:00:00'),(130,'Hibisco',0.00,1,'2026-11-01 10:00:00'),(131,'Granada',0.00,1,'2026-11-01 10:00:00'),(132,'Agua con gas',0.00,1,'2026-11-01 10:00:00'),(133,'Jengibre',0.00,1,'2026-11-01 10:00:00'),(134,'Té de jazmín',0.00,1,'2026-11-01 10:00:00'),(135,'Lichi',0.00,1,'2026-11-01 10:00:00'),(136,'Café espresso',0.00,1,'2026-11-01 10:00:00'),(137,'Naranja',0.00,1,'2026-11-01 10:00:00'),(138,'Tónica',0.00,1,'2026-11-01 10:00:00'),(139,'Cardamomo',0.00,1,'2026-11-01 10:00:00');

INSERT INTO `producto_ingrediente` VALUES (1,2,'extra',0,0,1.00),(1,10,'extra',0,0,1.00),(1,39,'extra',0,0,1.00),(2,5,'extra',0,0,1.00),(2,6,'extra',0,0,1.00),(2,10,'extra',0,0,1.00),(3,1,'extra',0,0,1.00),(3,3,'extra',0,0,1.00),(3,13,'extra',0,0,1.00),(4,2,'extra',0,0,1.00),(4,5,'extra',0,0,1.00),(4,14,'extra',0,0,1.00),(5,1,'extra',0,0,1.00),(5,7,'extra',0,0,1.00),(5,9,'extra',0,0,1.00),(6,5,'extra',0,0,1.00),(6,6,'extra',0,0,1.00),(6,15,'extra',0,0,1.00),(7,1,'extra',0,0,1.00),(7,4,'extra',0,0,1.00),(7,15,'extra',0,0,1.00),(8,1,'extra',0,0,1.00),(8,4,'extra',0,0,1.00),(8,8,'extra',0,0,1.00),(9,4,'extra',0,0,1.00),(9,8,'extra',0,0,1.00),(9,14,'extra',0,0,1.00),(10,2,'extra',0,0,1.00),(10,7,'extra',0,0,1.00),(10,10,'extra',0,0,1.00),(11,18,'extra',0,0,1.00),(11,21,'extra',0,0,1.00),(11,22,'extra',0,0,1.00),(12,1,'extra',0,0,1.00),(12,7,'extra',0,0,1.00),(12,8,'extra',0,0,1.00),(13,1,'extra',0,0,1.00),(13,21,'extra',0,0,1.00),(13,22,'extra',0,0,1.00),(14,14,'extra',0,0,1.00),(14,15,'extra',0,0,1.00),(14,24,'extra',0,0,1.00),(15,18,'extra',0,0,1.00),(15,21,'extra',0,0,1.00),(15,22,'extra',0,0,1.00),(16,1,'extra',0,0,1.00),(16,3,'extra',0,0,1.00),(16,13,'extra',0,0,1.00),(16,21,'extra',0,0,1.00),(17,7,'extra',0,0,1.00),(17,18,'extra',0,0,1.00),(17,22,'extra',0,0,1.00),(18,17,'extra',0,0,1.00),(18,22,'extra',0,0,1.00),(18,24,'extra',0,0,1.00),(19,1,'extra',0,0,1.00),(19,8,'extra',0,0,1.00),(20,22,'extra',0,0,1.00),(20,24,'extra',0,0,1.00),(21,17,'extra',0,0,1.00),(21,22,'extra',0,0,1.00),(22,1,'extra',0,0,1.00),(22,8,'extra',0,0,1.00),(23,1,'extra',0,0,1.00);
INSERT INTO `producto_ingrediente` VALUES (23,8,'extra',0,0,1.00),(24,8,'extra',0,0,1.00),(24,13,'extra',0,0,1.00),(25,27,'extra',0,0,1.00),(25,29,'extra',0,0,1.00),(25,30,'extra',0,0,1.00),(26,30,'extra',0,0,1.00),(26,31,'extra',0,0,1.00),(26,32,'extra',0,0,1.00),(27,28,'extra',0,0,1.00),(27,29,'extra',0,0,1.00),(27,31,'extra',0,0,1.00),(28,19,'extra',0,0,1.00),(28,27,'extra',0,0,1.00),(28,30,'extra',0,0,1.00),(29,27,'extra',0,0,1.00),(29,33,'extra',0,0,1.00),(30,30,'extra',0,0,1.00),(30,31,'extra',0,0,1.00),(30,32,'extra',0,0,1.00),(31,37,'extra',0,0,1.00),(31,38,'extra',0,0,1.00),(31,39,'extra',0,0,1.00),(32,36,'extra',0,0,1.00),(32,38,'extra',0,0,1.00),(33,34,'extra',0,0,1.00),(33,37,'extra',0,0,1.00),(33,39,'extra',0,0,1.00),(34,35,'extra',0,0,1.00),(34,37,'extra',0,0,1.00),(34,39,'extra',0,0,1.00),(35,34,'extra',0,0,1.00),(35,37,'extra',0,0,1.00),(35,39,'extra',0,0,1.00),(36,33,'extra',0,0,1.00),(36,36,'extra',0,0,1.00),(36,38,'extra',0,0,1.00),(1,40,'base',1,0,1.00),(1,41,'base',1,0,1.00),(1,42,'base',1,0,1.00),(1,43,'base',1,0,1.00),(2,44,'base',1,0,1.00),(2,45,'base',1,0,1.00),(2,46,'base',1,0,1.00),(2,47,'base',1,0,1.00),(3,48,'base',1,0,1.00),(3,49,'base',1,0,1.00),(3,50,'base',1,0,1.00),(3,51,'base',1,0,1.00),(4,52,'base',1,0,1.00),(4,53,'base',1,0,1.00),(4,54,'base',1,0,1.00),(4,42,'base',1,0,1.00),(5,51,'base',1,0,1.00),(5,55,'base',1,0,1.00),(5,56,'base',1,0,1.00),(5,57,'base',1,0,1.00),(6,58,'base',1,0,1.00),(6,59,'base',1,0,1.00),(6,60,'base',1,0,1.00),(7,61,'base',1,0,1.00),(7,62,'base',1,0,1.00),(7,63,'base',1,0,1.00),(7,64,'base',1,0,1.00);
INSERT INTO `producto_ingrediente` VALUES (8,65,'base',1,0,1.00),(8,66,'base',1,0,1.00),(8,67,'base',1,0,1.00),(8,68,'base',1,0,1.00),(9,69,'base',1,0,1.00),(9,70,'base',1,0,1.00),(9,71,'base',1,0,1.00),(9,72,'base',1,0,1.00),(10,73,'base',1,0,1.00),(10,74,'base',1,0,1.00),(10,75,'base',1,0,1.00),(10,64,'base',1,0,1.00),(11,76,'base',1,0,1.00),(11,77,'base',1,0,1.00),(11,78,'base',1,0,1.00),(12,79,'base',1,0,1.00),(12,80,'base',1,0,1.00),(12,50,'base',1,0,1.00),(12,81,'base',1,0,1.00),(13,82,'base',1,0,1.00),(13,83,'base',1,0,1.00),(13,65,'base',1,0,1.00),(13,43,'base',1,0,1.00),(14,84,'base',1,0,1.00),(14,85,'base',1,0,1.00),(14,63,'base',1,0,1.00),(15,86,'base',1,0,1.00),(15,87,'base',1,0,1.00),(15,88,'base',1,0,1.00),(16,89,'base',1,0,1.00),(16,90,'base',1,0,1.00),(16,91,'base',1,0,1.00),(16,92,'base',1,0,1.00),(17,93,'base',1,0,1.00),(17,94,'base',1,0,1.00),(17,95,'base',1,0,1.00),(17,96,'base',1,0,1.00),(18,97,'base',1,0,1.00),(18,98,'base',1,0,1.00),(18,99,'base',1,0,1.00),(18,100,'base',1,0,1.00),(19,92,'base',1,0,1.00),(19,101,'base',1,0,1.00),(19,63,'base',1,0,1.00),(20,102,'base',1,0,1.00),(20,103,'base',1,0,1.00),(20,104,'base',1,0,1.00),(21,105,'base',1,0,1.00),(21,106,'base',1,0,1.00),(21,98,'base',1,0,1.00),(22,107,'base',1,0,1.00),(22,108,'base',1,0,1.00),(22,101,'base',1,0,1.00),(23,109,'base',1,0,1.00),(23,83,'base',1,0,1.00),(23,110,'base',1,0,1.00),(24,111,'base',1,0,1.00),(24,68,'base',1,0,1.00),(24,50,'base',1,0,1.00),(25,112,'base',1,0,1.00),(25,113,'base',1,0,1.00),(25,114,'base',1,0,1.00),(26,115,'base',1,0,1.00),(26,41,'base',1,0,1.00);
INSERT INTO `producto_ingrediente` VALUES (26,116,'base',1,0,1.00),(27,117,'base',1,0,1.00),(27,113,'base',1,0,1.00),(27,118,'base',1,0,1.00),(28,96,'base',1,0,1.00),(28,119,'base',1,0,1.00),(28,120,'base',1,0,1.00),(28,108,'base',1,0,1.00),(29,64,'base',1,0,1.00),(29,108,'base',1,0,1.00),(29,121,'base',1,0,1.00),(30,122,'base',1,0,1.00),(30,123,'base',1,0,1.00),(30,124,'base',1,0,1.00),(30,116,'base',1,0,1.00),(31,125,'base',1,0,1.00),(31,126,'base',1,0,1.00),(31,127,'base',1,0,1.00),(31,124,'base',1,0,1.00),(32,128,'base',1,0,1.00),(32,108,'base',1,0,1.00),(32,129,'base',1,0,1.00),(33,130,'base',1,0,1.00),(33,131,'base',1,0,1.00),(33,113,'base',1,0,1.00),(33,132,'base',1,0,1.00),(34,96,'base',1,0,1.00),(34,133,'base',1,0,1.00),(34,98,'base',1,0,1.00),(34,63,'base',1,0,1.00),(35,134,'base',1,0,1.00),(35,135,'base',1,0,1.00),(35,124,'base',1,0,1.00),(36,136,'base',1,0,1.00),(36,137,'base',1,0,1.00),(36,138,'base',1,0,1.00),(36,139,'base',1,0,1.00);

INSERT INTO `ofertas` VALUES (1,'2x1 en Entrantes Galácticos','Dos entrantes de la carta al precio de uno.','2x1','categoria',1,NULL,NULL,2,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(2,'10% en Primeros Platos','Descuento del 10% en toda la categoría de primeros.','descuento','categoria',2,10.0,NULL,1,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(3,'Combo 3 Postres por 24€','Elige tres postres y paga solo 24€.','combo','categoria',5,24.0,NULL,3,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(4,'2x1 en Bebidas','Dos bebidas al precio de una.','2x1','categoria',6,NULL,NULL,2,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(5,'15% en Guarniciones','Descuento del 15% al llevar dos o más guarniciones.','descuento','categoria',4,15.0,NULL,2,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(6,'Solomillo Orbital -10%','Descuento exclusivo en el Solomillo de Ternera.','descuento','producto',NULL,10.0,NULL,1,0.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00'),(7,'STARBITES10','Código de bienvenida: 10% en pedidos desde 30€.','codigo','pedido',NULL,10.0,'STARBITES10',1,30.0,'2026-01-01 00:00:00','2026-12-31 23:59:59',1,'2026-11-01 10:00:00','2026-11-01 10:00:00');

INSERT INTO `oferta_producto` VALUES (6,16);

-- Carrito y api_tokens se dejan vacios: se llenan desde la aplicacion.

INSERT INTO `pedidos` VALUES (1,2,'entregado','2026-11-02 20:15:00',61.0,0.0,54.55,6.45,61.0,'simulado','pagado','Calle Mayor 1, 28001 Madrid','+34 600 000 000','Sin cebolla en la guarnición.','2026-11-02 20:15:00'),(2,2,'pendiente','2026-11-06 21:00:00',41.0,4.1,33.55,3.35,36.9,'simulado','pendiente','Calle Mayor 1, 28001 Madrid','+34 600 000 000','Entregar a partir de las 21:00.','2026-11-06 21:00:00');

INSERT INTO `pedido_detalle` VALUES (1,1,1,NULL,'Nebulosa de Atún Rojo y Yuzu',18.5,2,0.0,50.0,10.0),(2,1,31,NULL,'Órbita Azul de Ginebra y Flor de Mariposa',11.0,1,0.0,11.0,21.0),(3,2,13,NULL,'Carré de Cordero en Eclipse de Ajo Negro y Remolacha',26.0,1,0.0,26.0,10.0),(4,2,19,NULL,'Órbita Dorada de Patata Anna y Azafrán',7.5,2,0.0,15.0,10.0);

INSERT INTO `pedido_detalle_ingrediente` VALUES (1,1,2,'Caviar Oscietra',6.5,2,'añadido'),(2,1,40,'Atún rojo',0.0,2,'incluido'),(3,1,41,'Yuzu',0.0,2,'incluido'),(4,2,125,'Ginebra',0.0,1,'incluido'),(5,3,82,'Cordero',0.0,1,'incluido'),(6,3,83,'Ajo negro',0.0,1,'incluido'),(7,4,92,'Patata',0.0,2,'incluido'),(8,4,63,'Azafrán',0.0,2,'incluido');

SET FOREIGN_KEY_CHECKS = 1;

-- Fin del script
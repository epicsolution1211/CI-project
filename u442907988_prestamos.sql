/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 100428 (10.4.28-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : u442907988_prestamos

 Target Server Type    : MySQL
 Target Server Version : 100428 (10.4.28-MariaDB)
 File Encoding         : 65001

 Date: 28/11/2023 04:09:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cajas
-- ----------------------------
DROP TABLE IF EXISTS `cajas`;
CREATE TABLE `cajas`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `monto_inicial` decimal(10, 2) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_usuario` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_usuario_caja`(`id_usuario` ASC) USING BTREE,
  CONSTRAINT `fk_usuario_caja` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cajas
-- ----------------------------
INSERT INTO `cajas` VALUES (1, 55000000.00, '2023-11-03 02:14:08', 1, '2023-11-03 02:14:08', '2023-11-03 02:14:08', 1);

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `identidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `num_identidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apellido` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `correo` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `direccion` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `num_identidad`(`num_identidad` ASC) USING BTREE,
  UNIQUE INDEX `telefono`(`telefono` ASC) USING BTREE,
  UNIQUE INDEX `whatsapp`(`whatsapp` ASC) USING BTREE,
  UNIQUE INDEX `correo`(`correo` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES (1, 'INE', '0376097635713', 'Leonardo', 'Urraza', '6181023660', '6181023660', 'pagos@leonardogrinn.com', 'BURDEOS 106, Residencial La Salle, Durango, Durango ', 1, '2023-10-22 03:41:59', '2023-10-26 19:35:33');
INSERT INTO `clientes` VALUES (3, '111', '1111111111111', '111111', '1111', '1111111111111111', '1111111111111111111', '1@1.com', '11111', 0, '2023-10-27 02:26:31', '2023-10-27 02:26:48');
INSERT INTO `clientes` VALUES (4, 'INE', '111111111112', '1111111111111111111', '1111111111111111111', '1111111111111111111', '1211111111111111111', '2@1.com', '1111111111111111111', 0, '2023-10-27 02:28:19', '2023-10-27 02:28:25');
INSERT INTO `clientes` VALUES (5, 'INE', '1244354656745', 'Alma', 'Padilla', '6188152122', '6188152122', 'alma.pas@live.com.mx', 'Bruno Martínez 120, Zona Centro, Durango, Durango.', 1, '2023-11-16 00:19:26', '2023-11-16 00:19:54');

-- ----------------------------
-- Table structure for configuracion
-- ----------------------------
DROP TABLE IF EXISTS `configuracion`;
CREATE TABLE `configuracion`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `identidad` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `correo` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `direccion` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `tasa_interes` decimal(10, 2) NOT NULL,
  `cuotas` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `identidad`(`identidad` ASC) USING BTREE,
  UNIQUE INDEX `telefono`(`telefono` ASC) USING BTREE,
  UNIQUE INDEX `correo`(`correo` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of configuracion
-- ----------------------------
INSERT INTO `configuracion` VALUES (1, '123456789', 'Grinbin.io', '6181023660', 'leonardo@grinbin.io', 'México', 'GRACIAS POR ADQUIRIR LA PLATAFORMA', 19.20, 48, '2023-10-22 01:33:45', '2023-11-03 14:29:20');

-- ----------------------------
-- Table structure for detalle_prestamos
-- ----------------------------
DROP TABLE IF EXISTS `detalle_prestamos`;
CREATE TABLE `detalle_prestamos`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cuota` int NOT NULL,
  `fecha_venc` date NOT NULL,
  `installment` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `interest` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `paid_installment` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `importe_cuota` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_prestamo` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_prestamos`(`id_prestamo` ASC) USING BTREE,
  CONSTRAINT `fk_prestamos` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 129 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of detalle_prestamos
-- ----------------------------
INSERT INTO `detalle_prestamos` VALUES (112, 1, '2023-12-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:03:31', 31);
INSERT INTO `detalle_prestamos` VALUES (113, 2, '2024-01-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 31);
INSERT INTO `detalle_prestamos` VALUES (114, 3, '2024-02-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 31);
INSERT INTO `detalle_prestamos` VALUES (115, 4, '2024-03-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 31);
INSERT INTO `detalle_prestamos` VALUES (116, 5, '2024-04-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 31);
INSERT INTO `detalle_prestamos` VALUES (117, 6, '2024-05-22', 1667.50, 160.08, 0.00, 1827.58, 1, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 31);

-- ----------------------------
-- Table structure for fabricantes
-- ----------------------------
DROP TABLE IF EXISTS `fabricantes`;
CREATE TABLE `fabricantes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_fabricante` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fabricantes
-- ----------------------------
INSERT INTO `fabricantes` VALUES (1, 'Toyota', 1, NULL, '2023-10-30 00:28:38');
INSERT INTO `fabricantes` VALUES (2, 'Ford', 1, NULL, '2023-11-01 10:02:06');
INSERT INTO `fabricantes` VALUES (3, 'Dodge', 1, '2023-11-01 10:02:50', '2023-11-01 10:02:50');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `namespace` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2023-06-26-145832', 'App\\Database\\Migrations\\Admin', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (2, '2023-06-26-150959', 'App\\Database\\Migrations\\Permisos', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (3, '2023-06-26-151017', 'App\\Database\\Migrations\\Roles', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (4, '2023-06-26-151033', 'App\\Database\\Migrations\\Usuarios', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (5, '2023-06-26-151046', 'App\\Database\\Migrations\\Cajas', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (6, '2023-06-26-151051', 'App\\Database\\Migrations\\Clientes', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (7, '2023-06-26-151104', 'App\\Database\\Migrations\\Prestamos', 'default', 'App', 1697956358, 1);
INSERT INTO `migrations` VALUES (8, '2023-06-26-151116', 'App\\Database\\Migrations\\DetallePrestamos', 'default', 'App', 1697956358, 1);

-- ----------------------------
-- Table structure for modelos
-- ----------------------------
DROP TABLE IF EXISTS `modelos`;
CREATE TABLE `modelos`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fabricante` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nombre_modelo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modelos
-- ----------------------------
INSERT INTO `modelos` VALUES (1, 'Toyota', 'Tacoma', 1, '2023-11-01 11:01:27', '2023-11-01 11:01:27');
INSERT INTO `modelos` VALUES (2, 'Ford', 'Lobo', 1, '2023-11-01 11:18:38', '2023-11-01 11:18:38');
INSERT INTO `modelos` VALUES (3, 'Dodge', 'RAM', 1, '2023-11-01 21:50:09', '2023-11-01 21:50:09');
INSERT INTO `modelos` VALUES (4, 'Toyota', 'Tundra', 1, '2023-11-01 22:18:32', '2023-11-01 22:18:32');

-- ----------------------------
-- Table structure for permisos
-- ----------------------------
DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `modulo` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `campos` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permisos
-- ----------------------------
INSERT INTO `permisos` VALUES (50, 'usuarios', '[\"listar usuarios\",\"nuevo usuario\",\"editar usuario\",\"eliminar usuario\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (51, 'configuracion', '[\"actualizar empresa\",\"backup\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (52, 'roles', '[\"listar roles\",\"nuevo rol\",\"editar rol\",\"eliminar rol\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (53, 'clientes', '[\"listar clientes\",\"nuevo cliente\",\"editar cliente\",\"eliminar cliente\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (54, 'prestamos', '[\"nuevo prestamo\",\"historial prestamos\",\"ver prestamo\",\"eliminar prestamo\",\"abono prestamo\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (55, 'vehiculos', '[\"listar vehiculos\",\"nuevo vehiculo\",\"editar vehiculo\",\"eliminar vehiculo\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (56, 'fabricantes', '[\"listar fabricantes\",\"nuevo fabricante\",\"editar fabricante\",\"eliminar fabricante\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (57, 'modelos', '[\"listar modelos\",\"nuevo modelo\",\"editar modelo\",\"eliminar modelo\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (58, 'cajas', '[\"ver saldo\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');
INSERT INTO `permisos` VALUES (59, 'reportes', '[\"pdf prestamos\",\"excel prestamos\"]', '2023-11-01 13:58:10', '2023-11-01 13:58:10');

-- ----------------------------
-- Table structure for prestamos
-- ----------------------------
DROP TABLE IF EXISTS `prestamos`;
CREATE TABLE `prestamos`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehiculo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `valor_vehiculo` decimal(10, 2) NOT NULL,
  `importe` decimal(10, 2) NOT NULL,
  `enganche` decimal(10, 2) NOT NULL,
  `modalidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tasa_interes` decimal(10, 2) NOT NULL,
  `cuotas` int NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_venc` date NULL DEFAULT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_cliente` int UNSIGNED NOT NULL,
  `id_vehiculo` int UNSIGNED NOT NULL,
  `id_usuario` int UNSIGNED NOT NULL,
  `saldo_pendiente` decimal(10, 2) NOT NULL,
  `interes_total` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_clientes`(`id_cliente` ASC) USING BTREE,
  INDEX `fk_usuarios`(`id_usuario` ASC) USING BTREE,
  CONSTRAINT `fk_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of prestamos
-- ----------------------------
INSERT INTO `prestamos` VALUES (31, '55733774147878571 - Toyota Tacoma 4x2', 99999999.99, 10005.00, 99999999.99, 'MENSUAL', 9.60, 6, '2023-11-22 00:00:00', '2024-04-22', 2, '2023-11-22 04:13:45', '2023-11-27 14:05:53', 1, 9, 2, 10005.00, 960.48);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `permisos` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'ADMINISTRADOR', '[\"listar usuarios\",\"nuevo usuario\",\"editar usuario\",\"eliminar usuario\",\"actualizar empresa\",\"backup\",\"listar roles\",\"nuevo rol\",\"editar rol\",\"eliminar rol\",\"listar clientes\",\"nuevo cliente\",\"editar cliente\",\"eliminar cliente\",\"nuevo prestamo\",\"historial prestamos\",\"ver prestamo\",\"eliminar prestamo\",\"abono prestamo\",\"listar vehiculos\",\"nuevo vehiculo\",\"editar vehiculo\", \"eliminar vehiculo\", \"listar fabricantes\", \"nuevo fabricante\", \"editar fabricante\", \"eliminar fabricante\", \"listar modelos\",\"nuevo modelo\",\"editar modelo\", \"eliminar modelo\", \"ver saldo\",\"pdf prestamos\",\"excel prestamos\"]', 1, '2023-10-22 01:33:45', '2023-10-22 07:14:37');

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apellido` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `correo` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `direccion` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `clave` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `perfil` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `token` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `verify` int NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_rol` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `telefono`(`telefono` ASC) USING BTREE,
  UNIQUE INDEX `correo`(`correo` ASC) USING BTREE,
  INDEX `fk_roles`(`id_rol` ASC) USING BTREE,
  CONSTRAINT `fk_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES (1, 'Leonardo', 'Grinn', '6181023660', 'leonardo@grinbin.io', 'México', '$2y$10$HlD3kIR87PHLQvCEthANXeByZ1QkfRQ1wGRDw3xhaSqKDggjWNoSy', '1.png', 1, '0bb7852f0f0a1b2ecb8cd7d5f783e0d8', 1, '2023-10-22 01:33:45', '2023-11-03 14:19:53', 1);
INSERT INTO `usuarios` VALUES (2, 'Demo', 'Admin', '1234567897876', 'tester@leonardogrinn.com', '12345678', '$2y$10$0Vu5xmpRd3GdAXKdWkJyQOdppT97EWx96Xr6ShAFQIt7g3/QdQzNS', NULL, 1, NULL, 0, '2023-11-17 01:22:50', '2023-11-17 01:22:50', 1);

-- ----------------------------
-- Table structure for vehiculos
-- ----------------------------
DROP TABLE IF EXISTS `vehiculos`;
CREATE TABLE `vehiculos`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `vin` varchar(17) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_fabricante` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_modelo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `categoria` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `transmision` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `motor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `año` int NOT NULL,
  `kilometraje` int NOT NULL,
  `numero_puertas` int NOT NULL,
  `precio` double NOT NULL,
  `estado` int NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of vehiculos
-- ----------------------------
INSERT INTO `vehiculos` VALUES (1, '12345678901234556', 'Toyota', 'Tacoma', 'Blanco', 'Pick-Up', 'Automática', 'Gasolina', 2020, 0, 5, 50000, 1, NULL, '2023-11-01 23:34:44');
INSERT INTO `vehiculos` VALUES (2, '42356780986543246', 'Dodge', 'RAM', 'Oxford', 'Pick-Up', 'Automática', 'Diesel', 1, 0, 0, 724499, 1, '2023-10-26 20:00:24', '2023-11-15 21:18:38');
INSERT INTO `vehiculos` VALUES (6, '89746544147878579', 'Toyota', 'Tundra', 'Verde', 'Pick-Up', 'Manual', 'Gasolina', 0, 0, 0, 322332, 1, '2023-10-27 03:02:27', '2023-11-02 21:12:27');
INSERT INTO `vehiculos` VALUES (7, '12345467590987564', '1', '1', 'Rojo', 'Minivan', 'Automática', 'Gasolina', 2023, 0, 5, 235000, 0, '2023-10-27 03:03:29', '2023-10-27 04:08:41');
INSERT INTO `vehiculos` VALUES (8, '95746544147878571', 'Ford', 'Lobo', 'Azul', 'Pick-Up', 'Automática', 'Gasolina', 2023, 10000, 4, 670000, 1, '2023-10-29 23:20:01', '2023-11-02 21:12:20');
INSERT INTO `vehiculos` VALUES (9, '55733774147878571', 'Toyota', 'Tacoma 4x2', 'Blanco', 'Pick-Up', 'Automática', 'Gasolina', 2020, 0, 5, 690000, 1, '2023-10-30 00:38:02', '2023-11-15 21:19:39');

SET FOREIGN_KEY_CHECKS = 1;

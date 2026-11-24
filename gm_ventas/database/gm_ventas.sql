-- ============================================================
-- GM VENTAS - Base de Datos MySQL (Salón de Belleza)
-- ============================================================
CREATE DATABASE IF NOT EXISTS gm_ventas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gm_ventas;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS detalle_venta;
DROP TABLE IF EXISTS venta;
DROP TABLE IF EXISTS detalle_ingreso;
DROP TABLE IF EXISTS ingreso;
DROP TABLE IF EXISTS usuario_permiso;
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS permiso;
DROP TABLE IF EXISTS articulo;
DROP TABLE IF EXISTS marca;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS persona;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categoria (
  idcategoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  condicion TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE marca (
  idmarca INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL,
  descripcion VARCHAR(255) DEFAULT NULL,
  condicion TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE articulo (
  idarticulo INT AUTO_INCREMENT PRIMARY KEY,
  idcategoria INT NOT NULL,
  idmarca INT NOT NULL,
  codigo VARCHAR(40) NOT NULL UNIQUE,
  nombre VARCHAR(140) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  descripcion VARCHAR(255) DEFAULT NULL,
  imagen VARCHAR(120) DEFAULT '',
  condicion TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_articulo_categoria FOREIGN KEY (idcategoria) REFERENCES categoria(idcategoria),
  CONSTRAINT fk_articulo_marca FOREIGN KEY (idmarca) REFERENCES marca(idmarca)
) ENGINE=InnoDB;

CREATE TABLE persona (
  idpersona INT AUTO_INCREMENT PRIMARY KEY,
  tipo_persona VARCHAR(20) NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  tipo_documento VARCHAR(20) NOT NULL,
  num_documento VARCHAR(20) NOT NULL,
  direccion VARCHAR(180) DEFAULT '',
  telefono VARCHAR(25) DEFAULT '',
  email VARCHAR(100) DEFAULT '',
  condicion TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE permiso (
  idpermiso INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE usuario (
  idusuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  tipo_documento VARCHAR(20) NOT NULL,
  num_documento VARCHAR(20) NOT NULL,
  direccion VARCHAR(160) DEFAULT '',
  telefono VARCHAR(25) DEFAULT '',
  email VARCHAR(100) DEFAULT '',
  cargo VARCHAR(50) NOT NULL,
  login VARCHAR(40) NOT NULL UNIQUE,
  clave VARCHAR(128) NOT NULL,
  imagen VARCHAR(120) DEFAULT '',
  condicion TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE usuario_permiso (
  idusuario_permiso INT AUTO_INCREMENT PRIMARY KEY,
  idusuario INT NOT NULL,
  idpermiso INT NOT NULL,
  CONSTRAINT fk_up_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario) ON DELETE CASCADE,
  CONSTRAINT fk_up_permiso FOREIGN KEY (idpermiso) REFERENCES permiso(idpermiso) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ingreso (
  idingreso INT AUTO_INCREMENT PRIMARY KEY,
  idproveedor INT NOT NULL,
  idusuario INT NOT NULL,
  tipo_comprobante VARCHAR(20) NOT NULL,
  serie_comprobante VARCHAR(10) NOT NULL,
  num_comprobante VARCHAR(15) NOT NULL,
  fecha_hora DATE NOT NULL,
  impuesto DECIMAL(4,2) NOT NULL DEFAULT 18.00,
  metodo_pago VARCHAR(30) NOT NULL DEFAULT 'Transferencia',
  total_compra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  estado VARCHAR(20) NOT NULL DEFAULT 'Aceptado',
  CONSTRAINT fk_ingreso_proveedor FOREIGN KEY (idproveedor) REFERENCES persona(idpersona),
  CONSTRAINT fk_ingreso_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_ingreso (
  iddetalle_ingreso INT AUTO_INCREMENT PRIMARY KEY,
  idingreso INT NOT NULL,
  idarticulo INT NOT NULL,
  cantidad INT NOT NULL,
  precio_compra DECIMAL(10,2) NOT NULL,
  precio_venta DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_di_ingreso FOREIGN KEY (idingreso) REFERENCES ingreso(idingreso) ON DELETE CASCADE,
  CONSTRAINT fk_di_articulo FOREIGN KEY (idarticulo) REFERENCES articulo(idarticulo)
) ENGINE=InnoDB;

CREATE TABLE venta (
  idventa INT AUTO_INCREMENT PRIMARY KEY,
  idcliente INT NOT NULL,
  idusuario INT NOT NULL,
  tipo_comprobante VARCHAR(20) NOT NULL,
  serie_comprobante VARCHAR(10) NOT NULL,
  num_comprobante VARCHAR(15) NOT NULL,
  fecha_hora DATE NOT NULL,
  impuesto DECIMAL(4,2) NOT NULL DEFAULT 18.00,
  metodo_pago VARCHAR(30) NOT NULL DEFAULT 'Efectivo',
  total_venta DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  estado VARCHAR(20) NOT NULL DEFAULT 'Aceptado',
  CONSTRAINT fk_venta_cliente FOREIGN KEY (idcliente) REFERENCES persona(idpersona),
  CONSTRAINT fk_venta_usuario FOREIGN KEY (idusuario) REFERENCES usuario(idusuario)
) ENGINE=InnoDB;

CREATE TABLE detalle_venta (
  iddetalle_venta INT AUTO_INCREMENT PRIMARY KEY,
  idventa INT NOT NULL,
  idarticulo INT NOT NULL,
  cantidad INT NOT NULL,
  precio_venta DECIMAL(10,2) NOT NULL,
  descuento DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT fk_dv_venta FOREIGN KEY (idventa) REFERENCES venta(idventa) ON DELETE CASCADE,
  CONSTRAINT fk_dv_articulo FOREIGN KEY (idarticulo) REFERENCES articulo(idarticulo)
) ENGINE=InnoDB;

-- Permisos
INSERT INTO permiso (idpermiso, nombre) VALUES
(1, 'Escritorio'),
(2, 'Almacen'),
(3, 'Compras'),
(4, 'Ventas'),
(5, 'Acceso'),
(6, 'Consulta Compras'),
(7, 'Consulta Ventas');

-- Usuarios (gladys / 123456 y mery / 12345 en SHA-256)
INSERT INTO usuario (idusuario, nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion) VALUES
(1, 'Gladys', 'DNI', '72458910', 'Av. Arequipa 1420, Lima', '987654321', 'gladys@gmventas.pe', 'Administradora', 'gladys', SHA2('123456', 256), '', 1),
(2, 'Mery', 'DNI', '74123698', 'Jr. Camaná 450, Lima', '951753852', 'mery@gmventas.pe', 'Vendedora', 'mery', SHA2('12345', 256), '', 1);

INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),
(2,1),(2,2),(2,4);

-- 5 Categorías
INSERT INTO categoria (idcategoria, nombre, descripcion, condicion) VALUES
(1, 'Cuidado Capilar', 'Shampoos, acondicionadores y mascarillas profesionales de salón', 1),
(2, 'Tintes y Coloración', 'Tintes permanentes, matizadores y oxidantes capilares', 1),
(3, 'Tratamientos de Keratina', 'Alisados brasileños, botox capilar y selladores térmicos', 1),
(4, 'Herramientas Térmicas', 'Planchas de titanio, secadoras iónicas y rizadores', 1),
(5, 'Manicure y Estética', 'Esmaltes semipermanentes, cabinas LED y cuidado de uñas', 1);

-- 5 Marcas
INSERT INTO marca (idmarca, nombre, descripcion, condicion) VALUES
(1, 'L''Oréal Professionnel', 'Línea francesa experta en coloración y reparación capilar', 1),
(2, 'Kérastase', 'Tratamientos capilares de lujo y nutrición profunda', 1),
(3, 'Wella Professionals', 'Coloración de alto desempeño y fijación profesional', 1),
(4, 'Schwarzkopf', 'Tecnología alemana en alisados, tintes y reconstrucción', 1),
(5, 'BaBylissPRO', 'Equipos térmicos de titanio para estilistas profesionales', 1);

-- 5 Artículos de Salón (SLN-001 a SLN-005)
INSERT INTO articulo (idarticulo, idcategoria, idmarca, codigo, nombre, stock, precio_venta, descripcion, imagen, condicion) VALUES
(1, 1, 2, 'SLN-001', 'Shampoo Nutritivo Bain Satin 1000ml', 24, 145.00, 'Limpieza nutritiva profunda para cabello seco y sensibilizado', '', 1),
(2, 2, 1, 'SLN-002', 'Tinte Majirel Rubios Cenizos 50ml + Oxidante', 36, 58.00, 'Coloración permanente con alta cobertura de canas y brillo intenso', '', 1),
(3, 3, 4, 'SLN-003', 'Kit Alisado Keratina Orgánica 500ml', 14, 210.00, 'Tratamiento alisador libre de formol con aminoácidos y argán', '', 1),
(4, 4, 5, 'SLN-004', 'Plancha Nano Titanium 450°F Edición Salón', 8, 380.00, 'Placas de titanio puro para sellado de keratina y liso espejo', '', 1),
(5, 5, 3, 'SLN-005', 'Set Esmaltes Semipermanentes Gel Salon (6 und)', 19, 125.00, 'Colección de tonos tendencia de larga duración hasta 21 días', '', 1);

-- 5 Proveedores + 5 Clientes
INSERT INTO persona (idpersona, tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email, condicion) VALUES
(1, 'Proveedor', 'Distribuidora Belleza Total SAC', 'RUC', '20541289631', 'Av. Javier Prado Este 1850, San Borja', '014752100', 'compras@bellezatotal.pe', 1),
(2, 'Proveedor', 'Cosmética Andina Mayorista EIRL', 'RUC', '20601987412', 'Jr. Andahuaylas 920, Cercado de Lima', '013284510', 'ventas@cosmeticaandina.pe', 1),
(3, 'Proveedor', 'Importadora Capilar Luxury SAC', 'RUC', '20558741209', 'Av. Benavides 2140, Miraflores', '014468920', 'pedidos@capilarluxury.pe', 1),
(4, 'Proveedor', 'ProEstética Equipos & Salón SAC', 'RUC', '20608452177', 'Av. La Marina 2410, San Miguel', '015621480', 'contacto@proestetica.pe', 1),
(5, 'Proveedor', 'Glamour Insumos Profesionales SRL', 'RUC', '20519632541', 'Av. Petit Thouars 3180, San Isidro', '014229870', 'distribucion@glamourinsumos.pe', 1),
(6, 'Cliente', 'Camila Mendoza Rojas', 'DNI', '71258963', 'Calle Los Pinos 240, Lince', '984125630', 'camila.mendoza@gmail.com', 1),
(7, 'Cliente', 'Salón Valentina Studio SAC', 'RUC', '20604512890', 'Av. Pardo 620, Miraflores', '991452360', 'admin@valentinastudio.pe', 1),
(8, 'Cliente', 'Lucía Fernández Vega', 'DNI', '45896321', 'Av. Brasil 1480, Jesús María', '976321458', 'lucia.fernandez@hotmail.com', 1),
(9, 'Cliente', 'Spa & Estilo Aura EIRL', 'RUC', '20607854123', 'Av. Aviación 2950, San Borja', '988741256', 'compras@auraspa.pe', 1),
(10, 'Cliente', 'Daniela Quispe Huamán', 'DNI', '73654128', 'Jr. Huiracocha 1320, Jesús María', '965412387', 'daniela.quispe@gmail.com', 1);

-- 5 Ingresos / Compras
INSERT INTO ingreso (idingreso, idproveedor, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, metodo_pago, total_compra, estado) VALUES
(1, 1, 1, 'Factura', 'F001', '0001021', DATE_FORMAT(CURDATE(), '%Y-%m-02'), 18.00, 'Transferencia', 1420.00, 'Aceptado'),
(2, 2, 1, 'Factura', 'F001', '0001022', DATE_FORMAT(CURDATE(), '%Y-%m-05'), 18.00, 'Efectivo', 960.00, 'Aceptado'),
(3, 3, 1, 'Factura', 'F002', '0000415', DATE_FORMAT(CURDATE(), '%Y-%m-09'), 18.00, 'Transferencia', 1680.00, 'Aceptado'),
(4, 4, 1, 'Boleta', 'B001', '0000840', DATE_FORMAT(CURDATE(), '%Y-%m-12'), 18.00, 'Tarjeta', 1520.00, 'Aceptado'),
(5, 5, 1, 'Factura', 'F001', '0001025', DATE_FORMAT(CURDATE(), '%Y-%m-14'), 18.00, 'Yape/Plin', 760.00, 'Aceptado');

INSERT INTO detalle_ingreso (idingreso, idarticulo, cantidad, precio_compra, precio_venta) VALUES
(1, 1, 10, 98.00, 145.00),
(1, 2, 12, 36.67, 58.00),
(2, 2, 24, 40.00, 58.00),
(3, 3, 12, 140.00, 210.00),
(4, 4, 5, 304.00, 380.00),
(5, 5, 10, 76.00, 125.00);

-- 5 Ventas
INSERT INTO venta (idventa, idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, metodo_pago, total_venta, estado) VALUES
(1, 6, 2, 'Boleta', 'B001', '0000101', DATE_FORMAT(CURDATE(), '%Y-%m-04'), 18.00, 'Yape/Plin', 203.00, 'Aceptado'),
(2, 7, 1, 'Factura', 'F001', '0000042', DATE_FORMAT(CURDATE(), '%Y-%m-07'), 18.00, 'Transferencia', 630.00, 'Aceptado'),
(3, 8, 2, 'Boleta', 'B001', '0000102', DATE_FORMAT(CURDATE(), '%Y-%m-10'), 18.00, 'Efectivo', 380.00, 'Aceptado'),
(4, 9, 1, 'Factura', 'F001', '0000043', DATE_FORMAT(CURDATE(), '%Y-%m-13'), 18.00, 'Tarjeta', 495.00, 'Aceptado'),
(5, 10, 2, 'Ticket', 'T001', '0000088', DATE_FORMAT(CURDATE(), '%Y-%m-15'), 18.00, 'Yape/Plin', 174.00, 'Aceptado');

INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento) VALUES
(1, 1, 1, 145.00, 0.00),
(1, 2, 1, 58.00, 0.00),
(2, 3, 3, 210.00, 0.00),
(3, 4, 1, 380.00, 0.00),
(4, 5, 3, 125.00, 0.00),
(4, 1, 1, 145.00, 25.00),
(5, 2, 3, 58.00, 0.00);

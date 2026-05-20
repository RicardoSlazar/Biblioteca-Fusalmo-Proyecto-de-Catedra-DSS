-- ============================================================
-- Base de Datos — Sistema de Gestión de Biblioteca Fusalmo
-- DSS 404 G03T
-- Generado: 2026-05-20
-- Servidor: MariaDB 10.4.32 | PHP 8.0.30
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Crear y seleccionar la base de datos
-- ------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `biblioteca_fusalmo`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `biblioteca_fusalmo`;

-- ============================================================
-- TABLAS
-- ============================================================

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `nombre`      VARCHAR(100)        NOT NULL,
    `correo`      VARCHAR(150)        NOT NULL,
    `contrasena`  VARCHAR(255)        NOT NULL,           -- hash bcrypt
    `telefono`    VARCHAR(20)         DEFAULT NULL,
    `rol`         ENUM('admin','bibliotecario','usuario') NOT NULL DEFAULT 'usuario',
    `estado`      ENUM('activo','inactivo')               NOT NULL DEFAULT 'activo',
    `created_at`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_correo` (`correo`),
    INDEX `idx_rol`    (`rol`),
    INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: categorias
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias` (
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `nombre`      VARCHAR(80)         NOT NULL,
    `descripcion` TEXT                DEFAULT NULL,
    `created_at`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: autores
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `autores` (
    `id`           INT(10) UNSIGNED   NOT NULL AUTO_INCREMENT,
    `nombre`       VARCHAR(150)       NOT NULL,
    `nacionalidad` VARCHAR(100)       DEFAULT NULL,
    `biografia`    TEXT               DEFAULT NULL,
    `created_at`   TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: libros
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `libros` (
    `id`           INT(10) UNSIGNED   NOT NULL AUTO_INCREMENT,
    `titulo`       VARCHAR(200)       NOT NULL,
    `autor_id`     INT(10) UNSIGNED   DEFAULT NULL,
    `categoria_id` INT(10) UNSIGNED   DEFAULT NULL,
    `isbn`         VARCHAR(20)        DEFAULT NULL,
    `cantidad`     INT(11)            NOT NULL DEFAULT 1,
    `estado`       ENUM('disponible','reservado','agotado') NOT NULL DEFAULT 'disponible',
    `descripcion`  TEXT               DEFAULT NULL,
    `created_at`   TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_titulo`     (`titulo`),
    INDEX `idx_estado`     (`estado`),
    INDEX `idx_autor_id`   (`autor_id`),
    INDEX `idx_categoria`  (`categoria_id`),
    CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`autor_id`)     REFERENCES `autores`    (`id`) ON DELETE SET NULL,
    CONSTRAINT `libros_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: prestamos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `prestamos` (
    `id`                       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id`               INT(10) UNSIGNED DEFAULT NULL,
    `libro_id`                 INT(10) UNSIGNED DEFAULT NULL,
    `fecha_prestamo`           DATE             DEFAULT NULL,
    `fecha_devolucion_esperada` DATE            DEFAULT NULL,
    `fecha_devolucion_real`    DATE             DEFAULT NULL,
    `estado`                   ENUM('activo','devuelto','vencido') NOT NULL DEFAULT 'activo',
    `multa`                    DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
    `observaciones`            TEXT             DEFAULT NULL,
    `registrado_por`           INT(10) UNSIGNED DEFAULT NULL,     -- FK usuario bibliotecario
    `created_at`               TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_usuario`   (`usuario_id`),
    INDEX `idx_libro`     (`libro_id`),
    INDEX `idx_estado`    (`estado`),
    INDEX `idx_fecha_dev` (`fecha_devolucion_esperada`),
    CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`usuario_id`)    REFERENCES `usuarios` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`libro_id`)      REFERENCES `libros`   (`id`) ON DELETE RESTRICT,
    CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: devoluciones
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `devoluciones` (
    `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `prestamo_id`   INT(10) UNSIGNED NOT NULL,
    `fecha_real`    DATE             NOT NULL,
    `estado_fisico` ENUM('bueno','regular','malo') NOT NULL DEFAULT 'bueno',
    `observaciones` TEXT             DEFAULT NULL,
    `registrado_por` INT(10) UNSIGNED DEFAULT NULL,
    `created_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_prestamo` (`prestamo_id`),
    CONSTRAINT `devoluciones_ibfk_1` FOREIGN KEY (`prestamo_id`)   REFERENCES `prestamos` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `devoluciones_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios`  (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: calificaciones
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `calificaciones` (
    `id`         INT(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
    `usuario_id` INT(10) UNSIGNED     NOT NULL,
    `libro_id`   INT(10) UNSIGNED     NOT NULL,
    `estrellas`  TINYINT(1) UNSIGNED  NOT NULL,
    `created_at` TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_usuario_libro` (`usuario_id`, `libro_id`),
    INDEX `idx_libro_cal` (`libro_id`),
    CONSTRAINT `chk_estrellas`      CHECK (`estrellas` BETWEEN 1 AND 5),
    CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
    CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`libro_id`)   REFERENCES `libros`   (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Tabla: reportes_mensuales
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reportes_mensuales` (
    `id`           INT(10) UNSIGNED      NOT NULL AUTO_INCREMENT,
    `anio`         SMALLINT(4) UNSIGNED  NOT NULL,
    `mes`          TINYINT(2) UNSIGNED   NOT NULL,   -- 1-12
    `datos`        JSON                  NOT NULL,   -- snapshot del reporte
    `generado_por` INT(10) UNSIGNED      DEFAULT NULL,
    `created_at`   TIMESTAMP             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP             NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_anio_mes` (`anio`, `mes`),
    INDEX `idx_anio_mes` (`anio`, `mes`),
    CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`generado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- DATOS INICIALES
-- ============================================================

-- ------------------------------------------------------------
-- Usuarios
-- Contraseña para todos: Admin@2026
-- Hash: $2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi
-- ------------------------------------------------------------
INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `telefono`, `rol`, `estado`) VALUES
(1, 'Administrador',  'admin@fusalmo.org',          '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', NULL,        'admin',         'activo'),
(2, 'Bibliotecario',  'bibliotecario@fusalmo.org',   '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', '7000-1111', 'bibliotecario', 'activo'),
(3, 'Ricardo Usuario','usuario@fusalmo.org',         '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', '7000-2222', 'usuario',       'activo');

-- ------------------------------------------------------------
-- Categorías
-- ------------------------------------------------------------
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Literatura',  'Novelas y cuentos para jóvenes y adultos'),
(2, 'Historia',    'Libros históricos y documentales'),
(3, 'Tecnología',  'Programación y sistemas'),
(4, 'Fantasía',    'Libros de fantasía y ciencia ficción'),
(5, 'Educativo',   'Material de apoyo escolar'),
(6, 'Infantil',    'Libros para niños de 3 a 10 años'),
(7, 'Autoayuda',   'Desarrollo personal y bienestar');

-- ------------------------------------------------------------
-- Autores
-- ------------------------------------------------------------
INSERT INTO `autores` (`id`, `nombre`, `nacionalidad`, `biografia`) VALUES
(1, 'Gabriel García Márquez', 'Colombia',     NULL),
(2, 'J.K. Rowling',           'Reino Unido',  NULL),
(3, 'Paulo Coelho',           'Brasil',       NULL),
(4, 'Miguel de Cervantes',    'España',       NULL),
(5, 'George Orwell',          'Reino Unido',  NULL);

-- ------------------------------------------------------------
-- Libros
-- ------------------------------------------------------------
INSERT INTO `libros` (`id`, `titulo`, `autor_id`, `categoria_id`, `isbn`, `cantidad`, `estado`, `descripcion`) VALUES
(1, 'Cien años de soledad',               1, 1, '9780307474728', 5,  'disponible', 'Novela clásica del realismo mágico'),
(2, 'Harry Potter y la piedra filosofal', 2, 4, '9788478884452', 8,  'disponible', 'Primer libro de la saga Harry Potter'),
(3, 'El Alquimista',                      3, 1, '9780061122415', 4,  'disponible', 'Novela de desarrollo personal'),
(4, 'Don Quijote de la Mancha',           4, 1, '9788420412146', 3,  'disponible', 'Clásico de la literatura española'),
(5, '1984',                               5, 1, '9780451524935', 6,  'disponible', 'Novela distópica'),
(6, 'Redes y Servicios en la Nube',       5, 3, '9781234567890', 10, 'disponible', 'Infraestructura y cloud computing');

-- ------------------------------------------------------------
-- Préstamos
-- ------------------------------------------------------------
INSERT INTO `prestamos` (`id`, `usuario_id`, `libro_id`, `fecha_prestamo`, `fecha_devolucion_esperada`, `fecha_devolucion_real`, `estado`, `multa`) VALUES
(1, 2, 1, '2026-05-19', '2026-06-02', NULL, 'activo',   0.00),
(2, 2, 3, '2026-05-19', '2026-06-02', NULL, 'activo',   0.00),
(3, 3, 2, '2026-05-19', '2026-06-02', NULL, 'activo',   0.00);


-- ============================================================
-- AUTO_INCREMENT
-- ============================================================
ALTER TABLE `usuarios`          MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `categorias`        MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `autores`           MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `libros`            MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `prestamos`         MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `devoluciones`      MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `calificaciones`    MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `reportes_mensuales` MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

COMMIT;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 20-05-2026 a las 07:23:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca_fusalmo`
--

CREATE DATABASE IF NOT EXISTS biblioteca_fusalmo
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE biblioteca_fusalmo;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autores`
--

CREATE TABLE `autores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autores`
--

INSERT INTO `autores` (`id`, `nombre`, `nacionalidad`, `biografia`, `created_at`) VALUES
(1, 'Gabriel García Márquez', NULL, NULL, '2026-05-20 04:31:19'),
(2, 'Paulo Coelho', NULL, NULL, '2026-05-20 04:31:19'),
(3, 'Gabriel García Márquez', 'Colombia', NULL, '2026-05-20 04:37:28'),
(4, 'J.K. Rowling', 'Reino Unido', NULL, '2026-05-20 04:37:28'),
(5, 'Paulo Coelho', 'Brasil', NULL, '2026-05-20 04:37:28'),
(6, 'Miguel de Cervantes', 'España', NULL, '2026-05-20 04:37:28'),
(7, 'George Orwell', 'Reino Unido', NULL, '2026-05-20 04:37:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `created_at`) VALUES
(1, 'Literatura', NULL, '2026-05-20 04:31:19'),
(2, 'Historia', NULL, '2026-05-20 04:31:19'),
(3, 'Literatura', 'Novelas y cuentos', '2026-05-20 04:37:28'),
(4, 'Historia', 'Libros históricos', '2026-05-20 04:37:28'),
(5, 'Tecnología', 'Programación y sistemas', '2026-05-20 04:37:28'),
(6, 'Fantasía', 'Ficción y fantasía', '2026-05-20 04:37:28'),
(7, 'Educativo', 'Material académico', '2026-05-20 04:37:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `autor_id` int(10) UNSIGNED DEFAULT NULL,
  `categoria_id` int(10) UNSIGNED DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1,
  `estado` enum('disponible','reservado','agotado') DEFAULT 'disponible',
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor_id`, `categoria_id`, `isbn`, `cantidad`, `estado`, `descripcion`, `created_at`) VALUES
(1, 'Cien años de soledad', 1, 1, '978123456', 5, 'disponible', 'Novela', '2026-05-20 04:31:19'),
(2, 'El Alquimista', 2, 1, '978789456', 3, 'disponible', 'Novela', '2026-05-20 04:31:19'),
(3, 'Cien años de soledad', 1, 1, '9780307474728', 5, 'disponible', 'Novela clásica', '2026-05-20 04:37:28'),
(4, 'Harry Potter y la piedra filosofal', 2, 4, '9788478884452', 8, 'disponible', 'Saga Harry Potter', '2026-05-20 04:37:28'),
(5, 'El Alquimista', 3, 1, '9780061122415', 4, 'disponible', 'Desarrollo personal', '2026-05-20 04:37:28'),
(6, 'Don Quijote de la Mancha', 4, 1, '9788420412146', 3, 'disponible', 'Clásico español', '2026-05-20 04:37:28'),
(7, '1984', 5, 1, '9780451524935', 6, 'disponible', 'Novela distópica', '2026-05-20 04:37:28'),
(8, 'Redes y Servicios en la Nube', 5, 3, '9781234567890', 10, 'disponible', 'Infraestructura y cloud', '2026-05-20 04:37:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `libro_id` int(10) UNSIGNED DEFAULT NULL,
  `fecha_prestamo` date DEFAULT NULL,
  `estado` enum('activo','devuelto','vencido') DEFAULT 'activo',
  `fecha_devolucion_esperada` date DEFAULT NULL,
  `fecha_devolucion_real` date DEFAULT NULL,
  `multa` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `usuario_id`, `libro_id`, `fecha_prestamo`, `estado`, `fecha_devolucion_esperada`, `fecha_devolucion_real`, `multa`, `created_at`) VALUES
(1, 2, 1, '2026-05-19', 'activo', '2026-06-02', NULL, 0.00, '2026-05-20 04:37:28'),
(2, 2, 2, '2026-05-19', 'activo', '2026-06-02', NULL, 0.00, '2026-05-20 04:37:28'),
(3, 3, 3, '2026-05-19', 'activo', '2026-06-02', NULL, 0.00, '2026-05-20 04:37:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('admin','bibliotecario','usuario') DEFAULT 'usuario',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `telefono`, `rol`, `estado`, `created_at`) VALUES
(1, 'Administrador', 'admin@fusalmo.org', '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', NULL, 'admin', 'activo', '2026-05-20 04:31:19'),
(2, 'Bibliotecario', 'bibliotecario@fusalmo.org', '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', '7000-1111', 'bibliotecario', 'activo', '2026-05-20 04:37:28'),
(3, 'Ricardo Usuario', 'usuario@fusalmo.org', '$2y$10$NpiKSrjb8eSc259jrMoQ/.yJXLttbvY.GIg6aTWlnEKkWHCYB.YHi', '7000-2222', 'usuario', 'activo', '2026-05-20 04:37:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor_id` (`autor_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autores`
--
ALTER TABLE `autores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `libros_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

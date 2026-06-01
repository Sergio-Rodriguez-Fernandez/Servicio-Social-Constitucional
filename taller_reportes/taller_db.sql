-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2026 a las 17:26:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taller_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `grado` varchar(10) DEFAULT NULL,
  `grupo` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `nombre`, `numero_cuenta`, `grado`, `grupo`) VALUES
(2, 'Eduardo Rincon', '20215489', '3', 'A'),
(3, 'Sergio Emmanuel Rodríguez Fernández ', '20206581', '6', 'B'),
(4, 'Pepe jose', '20225588', '1', 'A'),
(6, 'Hector Manuel Rodriguez Vargas', '20216123', '4', 'J');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materiales`
--

INSERT INTO `materiales` (`id`, `nombre`) VALUES
(1, 'Lentes'),
(2, 'Martillo'),
(5, 'Mandil'),
(6, 'Llave Fresa'),
(7, 'Broca de Centro'),
(8, 'Broca 9/16'),
(9, 'Llave T torno'),
(10, 'Broquero con Llave'),
(11, 'Broca 3/8'),
(12, 'Vernier'),
(13, 'Lima'),
(14, 'Esmeril'),
(15, 'Llave Buril'),
(16, 'Careta para Soldar'),
(17, 'Guantes'),
(18, 'Broquero'),
(19, 'Llave 30'),
(20, 'Broca 1/4'),
(21, 'Broca 1/2'),
(22, 'Broca 5/8'),
(23, 'Collect'),
(24, 'Cort Vert 1/4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `contrasena`) VALUES
(1, 'Administrador', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `responsable_salida_id` int(11) DEFAULT NULL,
  `profesor_recibe_id` int(11) DEFAULT NULL,
  `responsable_recibe_id` int(11) DEFAULT NULL,
  `maquina` varchar(50) DEFAULT NULL,
  `mesa` varchar(50) DEFAULT NULL,
  `practica` varchar(255) DEFAULT NULL,
  `nombre_profesor_opcional` varchar(150) DEFAULT NULL,
  `hora_inicio` datetime DEFAULT current_timestamp(),
  `hora_termino` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `estudiante_id`, `responsable_salida_id`, `profesor_recibe_id`, `responsable_recibe_id`, `maquina`, `mesa`, `practica`, `nombre_profesor_opcional`, `hora_inicio`, `hora_termino`, `observaciones`) VALUES
(4, 2, NULL, NULL, 1, '', '', 'Práctica General', NULL, '2026-03-16 16:23:07', '2026-03-16 17:01:17', ''),
(5, 2, 1, NULL, 1, NULL, NULL, '', NULL, '2026-03-16 16:39:03', '2026-03-16 16:40:48', NULL),
(6, 2, 1, NULL, 1, NULL, NULL, 'prueba', NULL, '2026-03-16 17:00:55', '2026-03-16 17:01:25', NULL),
(7, 2, 1, NULL, 1, '', '', '', NULL, '2026-03-16 17:37:55', '2026-03-16 17:38:34', 'me muy descuidado en el laboratorio'),
(8, 2, 1, NULL, 1, '', '', '', NULL, '2026-03-16 17:47:08', '2026-03-16 17:59:15', 'ninguna'),
(9, 2, 1, NULL, 1, '', '', '', NULL, '2026-03-16 17:55:34', '2026-03-16 17:56:04', ''),
(10, 2, 1, NULL, 1, '', '', '', NULL, '2026-03-17 08:19:14', '2026-03-17 08:20:46', ''),
(11, 2, 1, NULL, 1, '', '', '', '', '2026-03-17 09:24:07', '2026-03-18 03:09:37', ''),
(12, 2, 1, NULL, 1, '', '', '', '', '2026-03-17 18:08:12', '2026-03-17 18:52:29', ''),
(13, 2, 1, NULL, 1, 'Tormo A', '2', 'prueba de fuego', 'Armando Farias', '2026-03-17 19:55:47', '2026-03-18 03:08:19', 'me cayo bien'),
(14, 2, 1, NULL, 1, 'Taladro', '', 'retirada', 'Josefina', '2026-03-17 20:15:03', '2026-03-17 20:18:54', ''),
(15, 2, 1, NULL, 1, 'Tormo A', '5', 'noche', '', '2026-03-17 21:41:16', '2026-03-17 21:42:19', ''),
(16, 2, 1, NULL, 1, '', '', '', '', '2026-03-17 22:18:30', '2026-03-17 22:25:24', ''),
(17, 3, 1, NULL, 1, '', '', 'sgdsdv', 'tyery', '2026-03-20 10:57:08', '2026-03-20 10:58:40', ''),
(18, 4, 1, NULL, 1, '', '', 'soldar', 'M', '2026-03-20 11:00:51', '2026-03-20 11:01:28', 'dañó martillo'),
(19, 4, 1, NULL, NULL, '', '', 'ertetreryr', 'dfkbmnldfkhg', '2026-03-20 11:03:23', NULL, NULL),
(20, 4, 1, NULL, 1, '', '', 'dsrgdsrg', 'srgsr', '2026-03-20 11:13:54', '2026-03-20 11:15:06', ''),
(21, 4, 1, NULL, 1, 'Tormo A', '5', 'no se', 'Armando Farias', '2026-03-21 22:42:17', '2026-03-21 22:44:29', ''),
(22, 2, 1, NULL, 1, 'Tormo A', '2', 'dios funciona', 'Armando Farias', '2026-04-20 11:25:05', '2026-04-20 11:25:29', ''),
(23, 4, 1, NULL, NULL, '', '', '', 'Josefina', '2026-04-22 12:45:05', NULL, NULL),
(24, 4, 1, NULL, NULL, 'Tormo A', '2', '', '', '2026-05-27 14:49:27', NULL, NULL),
(26, 4, 1, NULL, NULL, '', '', '', '', '2026-05-27 14:57:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_detalle_materiales`
--

CREATE TABLE `reporte_detalle_materiales` (
  `id` int(11) NOT NULL,
  `reporte_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `entregado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_materiales`
--

CREATE TABLE `reporte_materiales` (
  `id` int(11) NOT NULL,
  `reporte_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `cantidad_devuelta` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte_materiales`
--

INSERT INTO `reporte_materiales` (`id`, `reporte_id`, `material_id`, `cantidad`, `cantidad_devuelta`) VALUES
(1, 4, 1, 2, 2),
(2, 5, 1, 4, 4),
(3, 6, 1, 3, 4),
(4, 7, 1, 4, 4),
(5, 8, 1, 1, 1),
(6, 9, 1, 1, 1),
(7, 9, 2, 1, 1),
(8, 10, 1, 1, 1),
(9, 11, 1, 1, 1),
(10, 12, 1, 1, 1),
(11, 12, 2, 1, 1),
(12, 11, 2, 3, 3),
(13, 13, 1, 1, 1),
(14, 14, 1, 2, 2),
(15, 15, 1, 1, 1),
(16, 16, 1, 2, 2),
(17, 16, 1, 1, 1),
(18, 17, 1, 1, 1),
(19, 17, 2, 1, 1),
(20, 18, 1, 1, 0),
(21, 18, 2, 1, 1),
(22, 19, 1, 5, 0),
(23, 20, 1, 20, 16),
(24, 21, 1, 1, 1),
(25, 21, 5, 1, 1),
(26, 21, 2, 1, 1),
(27, 22, 5, 3, 3),
(28, 23, 1, 2, 0),
(29, 24, 1, 1, 0),
(31, 26, 5, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsables`
--

CREATE TABLE `responsables` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `responsables`
--

INSERT INTO `responsables` (`id`, `nombre`, `contrasena`) VALUES
(1, 'Irvin Rodriguez', '1234'),
(2, 'Mendoza', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_cuenta` (`numero_cuenta`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estudiante` (`estudiante_id`),
  ADD KEY `fk_profe_salida` (`responsable_salida_id`),
  ADD KEY `fk_profe_recibe` (`responsable_recibe_id`);

--
-- Indices de la tabla `reporte_detalle_materiales`
--
ALTER TABLE `reporte_detalle_materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reporte` (`reporte_id`),
  ADD KEY `fk_material` (`material_id`);

--
-- Indices de la tabla `reporte_materiales`
--
ALTER TABLE `reporte_materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporte_id` (`reporte_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indices de la tabla `responsables`
--
ALTER TABLE `responsables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `reporte_detalle_materiales`
--
ALTER TABLE `reporte_detalle_materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reporte_materiales`
--
ALTER TABLE `reporte_materiales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `responsables`
--
ALTER TABLE `responsables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `fk_profe_recibe` FOREIGN KEY (`responsable_recibe_id`) REFERENCES `responsables` (`id`),
  ADD CONSTRAINT `fk_profe_salida` FOREIGN KEY (`responsable_salida_id`) REFERENCES `responsables` (`id`);

--
-- Filtros para la tabla `reporte_detalle_materiales`
--
ALTER TABLE `reporte_detalle_materiales`
  ADD CONSTRAINT `fk_material` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`),
  ADD CONSTRAINT `fk_reporte` FOREIGN KEY (`reporte_id`) REFERENCES `reportes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reporte_materiales`
--
ALTER TABLE `reporte_materiales`
  ADD CONSTRAINT `reporte_materiales_ibfk_1` FOREIGN KEY (`reporte_id`) REFERENCES `reportes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reporte_materiales_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

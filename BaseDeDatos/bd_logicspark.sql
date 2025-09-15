-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2025 a las 15:42:03
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
-- Base de datos: `bd_logicspark`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `Perfil` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`Perfil`, `contrasena`) VALUES
('admin', '$2y$10$S4wNBH3m1a2bP1o3vO3uU.3f1rZ5b8m7Gf8eW6tQ0mCwqjO1m2ZxK'),
('admin1', 'admin123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradospor`
--

CREATE TABLE `administradospor` (
  `Cedula` int(11) NOT NULL,
  `Perfil` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socios`
--

CREATE TABLE `socios` (
  `Cedula` int(11) NOT NULL,
  `NombreCompleto` varchar(100) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Edad` varchar(3) NOT NULL,
  `FotoDePerfil` blob NOT NULL,
  `AporteInicial` int(11) DEFAULT NULL,
  `contrasena` varchar(100) NOT NULL,
  `Ntelefono` int(11) DEFAULT NULL,
  `Aceptado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `socios`
--

INSERT INTO `socios` (`Cedula`, `NombreCompleto`, `Email`, `Edad`, `FotoDePerfil`, `AporteInicial`, `contrasena`, `Ntelefono`, `Aceptado`) VALUES
(3232132, 'Eliass Salaverria', 'Eliass@gmail.com', '36', 0x363863376664306165613532332e6a706567, NULL, '$2y$10$avG354Ql7.snZ9KMkvoy8OVFM.xWBok0cvVKbaR8dh8nc4WNMjiRq', 5964157, 1),
(54213121, 'Santiago Cacola', 'Miles@gmail.com', '23', 0x363863376665363262396531662e6a706567, NULL, '$2y$10$0A/sBZ5R9wZVckEE9akZ6u7PorpmvZ3xqxRUJtui.kf97s00aZxdO', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienen`
--

CREATE TABLE `tienen` (
  `Cedula` int(11) NOT NULL,
  `CeduladelSocio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidadhabitacional`
--

CREATE TABLE `unidadhabitacional` (
  `CeduladelSocio` int(11) NOT NULL,
  `NumeroDeHabitacion` int(11) DEFAULT NULL,
  `HorasSemanales` int(11) DEFAULT NULL,
  `Completadas` tinyint(1) DEFAULT NULL,
  `ComprobantedePago` blob DEFAULT NULL,
  `ValidoInvalido` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidadhabitacional`
--

INSERT INTO `unidadhabitacional` (`CeduladelSocio`, `NumeroDeHabitacion`, `HorasSemanales`, `Completadas`, `ComprobantedePago`, `ValidoInvalido`) VALUES
(3232132, 2, NULL, NULL, NULL, NULL),
(54213121, 3, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`Perfil`);

--
-- Indices de la tabla `administradospor`
--
ALTER TABLE `administradospor`
  ADD KEY `FK_AdministradosPor_Cedula2` (`Cedula`),
  ADD KEY `FK_AdministradosPor_Perfil` (`Perfil`);

--
-- Indices de la tabla `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`Cedula`);

--
-- Indices de la tabla `tienen`
--
ALTER TABLE `tienen`
  ADD KEY `FL_Tienen_Cedula` (`Cedula`),
  ADD KEY `FK_Tienen_CeduladelSocio` (`CeduladelSocio`);

--
-- Indices de la tabla `unidadhabitacional`
--
ALTER TABLE `unidadhabitacional`
  ADD PRIMARY KEY (`CeduladelSocio`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradospor`
--
ALTER TABLE `administradospor`
  ADD CONSTRAINT `FK_AdministradosPor_Cedula2` FOREIGN KEY (`Cedula`) REFERENCES `socios` (`Cedula`),
  ADD CONSTRAINT `FK_AdministradosPor_Perfil` FOREIGN KEY (`Perfil`) REFERENCES `administradores` (`Perfil`);

--
-- Filtros para la tabla `tienen`
--
ALTER TABLE `tienen`
  ADD CONSTRAINT `FK_Tienen_CeduladelSocio` FOREIGN KEY (`CeduladelSocio`) REFERENCES `unidadhabitacional` (`CeduladelSocio`),
  ADD CONSTRAINT `FL_Tienen_Cedula` FOREIGN KEY (`Cedula`) REFERENCES `socios` (`Cedula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

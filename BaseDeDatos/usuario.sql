-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2025 a las 02:36:00
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
-- Base de datos: `base_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usr_name` varchar(100) NOT NULL,
  `usr_age` int(3) NOT NULL,
  `usr_email` varchar(100) NOT NULL,
  `usr_pass` varchar(100) NOT NULL,
  `imagen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usr_name`, `usr_age`, `usr_email`, `usr_pass`, `imagen`) VALUES
(1, 'Usuario1', 22, 'usuario122@gmail.com', '123456', NULL),
(2, 'Thiago azar', 0, 'Thiagoazar2008@gmail.com', '$2y$10$psOALIBCSUDVbySVDH5TcuVenHgwFrIxkeOxsQxKL4p9ULUvHlNOC', '6889587c6125d.jpeg'),
(3, 'dsd', 21, 'dfsdsf@d32321', '$2y$10$Lu/lx/cLLXmdm.slKXXn0uxyE7sje4aw0NAIP3iz2nVDNt7I2r2Dy', '68895d89222dd.jpeg'),
(4, 'dsd', 21, 'dassad@321312', '$2y$10$eWddiBChdq4u5G71OAGA6.32UH2bTo.Cgco/dS2WpEJQ1NYzFvQ8W', '68895dfbb259e.jpeg'),
(5, 'thiago', 21, 'Thiago@gmail.com', '$2y$10$e.4Eqirs1fpJi3rbvc9EBO0GjjW443XlO5o.HJU6B/mgExxB8AjcG', '68895e1b0b69e.jpeg'),
(6, 'thiago azar', 18, 'Thiago1@gmail.com', '$2y$10$Ih/F2RCF2n7XVrqAGT0BlODuw3yQrC/FBE4EdoQ25qSRue4oh.juW', '688960fed6d74.jpeg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

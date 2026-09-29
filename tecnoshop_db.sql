-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2026 a las 19:58:02
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
-- Base de datos: `tecnoshop_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Celulares', 'Smartphones y teléfonos móviles de todas las marcas'),
(2, 'Computadores', 'Laptops, desktops y todo en uno'),
(3, 'Accesorios', 'Cables, cargadores, fundas y protectores'),
(4, 'Periféricos', 'Teclados, mouse, monitores y audífonos'),
(5, 'Celulares', NULL),
(6, 'Computadores', NULL),
(7, 'Accesorios', NULL),
(8, 'Periféricos', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `documento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `email`, `telefono`, `documento`) VALUES
(1, 'Ana Sofía Gómez', 'ana@correo.com', '3001234567', '1098765432'),
(2, 'Carlos Andrés Ruiz', 'carlos@correo.com', '3209876543', '1023456789');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unit` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) GENERATED ALWAYS AS (`cantidad` * `precio_unit`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `marca` varchar(80) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `categoria_id`, `precio`, `stock`, `marca`, `estado`, `fecha_registro`) VALUES
(1, 'Samsung Galaxy A55', 'Pantalla AMOLED 6.6 pulgadas, 128GB', 1, 1299000.00, 12, 'Samsung', 'activo', '2026-07-23 04:08:38'),
(2, 'iPhone 15', 'Chip A16 Bionic, cámara 48MP, 256GB', 1, 3899000.00, 5, 'Apple', 'activo', '2026-07-23 04:08:38'),
(3, 'Laptop Lenovo IdeaPad', 'Core i5 13va gen, 8GB RAM, SSD 512GB', 2, 2450000.00, 8, 'Lenovo', 'activo', '2026-07-23 04:08:38'),
(4, 'Cable USB-C 2m', 'Carga rápida 65W, trenzado nylon', 3, 35000.00, 50, 'Genérico', 'activo', '2026-07-23 04:08:38'),
(5, 'Mouse Logitech M185', 'Inalámbrico, receptor USB, 3 botones', 4, 65000.00, 20, 'Logitech', 'activo', '2026-07-23 04:08:38'),
(6, 'Audífonos Sony WH-1000XM5', 'Cancelación de ruido, 30h batería', 4, 1100000.00, 8, 'Sony', 'activo', '2026-07-23 04:08:38'),
(7, 'Teclado Gamer', NULL, NULL, 150000.00, 5, 'Logitech', 'activo', '2026-08-21 02:50:49'),
(8, 'iPhone 15', 'Apple iPhone 15 128GB', 1, 3999000.00, 8, 'Apple', 'activo', '2026-09-03 02:35:27'),
(9, 'Samsung A55', 'Samsung Galaxy A55 256GB', 1, 1299000.00, 12, 'Samsung', 'activo', '2026-09-03 02:35:27'),
(10, 'iPhone 15 Pro', 'Apple iPhone 15 Pro 256GB', 1, 5499000.00, 4, 'Apple', 'activo', '2026-09-03 02:35:27'),
(11, 'MacBook Air M2', 'MacBook Air chip M2 8GB RAM', 2, 6499000.00, 4, 'Apple', 'activo', '2026-09-03 02:35:27'),
(12, 'Dell Inspiron 15', 'Dell Inspiron i5 8GB 512GB SSD', 2, 2899000.00, 6, 'Dell', 'activo', '2026-09-03 02:35:27'),
(13, 'HP Pavilion 15', 'HP Pavilion i7 16GB 1TB', 2, 3499000.00, 3, 'HP', 'activo', '2026-09-03 02:35:27'),
(14, 'Audífonos Sony WH', 'Sony WH-1000XM5 Bluetooth', 3, 899000.00, 15, 'Sony', 'activo', '2026-09-03 02:35:27'),
(15, 'Funda iPhone 15', 'Funda silicona premium iPhone 15', 3, 89000.00, 30, 'Genérico', 'activo', '2026-09-03 02:35:27'),
(16, 'Cable USB-C 2m', 'Cable carga rápida 65W 2 metros', 3, 45000.00, 50, 'Genérico', 'activo', '2026-09-03 02:35:27'),
(17, 'Mouse Logitech MX', 'Mouse inalámbrico Logitech MX Master 3', 4, 399000.00, 10, 'Logitech', 'activo', '2026-09-03 02:35:27'),
(18, 'Teclado Mecánico', 'Teclado mecánico RGB switches blue', 4, 289000.00, 8, 'Redragon', 'activo', '2026-09-03 02:35:27'),
(19, 'Monitor LG 24', 'Monitor LG 24 pulgadas Full HD IPS', 4, 899000.00, 5, 'LG', 'activo', '2026-09-03 02:35:27'),
(20, 'iPhone 15', 'Celular 128GB', NULL, 3999000.00, 8, 'Apple', 'activo', '2026-09-03 21:59:46'),
(21, 'Laptop Asus', NULL, NULL, 2500000.00, 10, NULL, 'activo', '2026-09-10 02:56:11'),
(22, 'Mouse', NULL, NULL, 0.00, 5, NULL, 'activo', '2026-09-10 02:58:30'),
(23, 'Mouse', NULL, NULL, 0.00, 5, NULL, 'activo', '2026-09-10 02:59:48'),
(24, 'Teclado', NULL, NULL, 50000.00, -5, NULL, 'activo', '2026-09-10 03:02:17'),
(25, 'Teclado', NULL, NULL, 50000.00, -5, NULL, 'activo', '2026-09-10 03:02:54'),
(26, 'Laptop Asus TUF', NULL, NULL, 3500000.00, 10, NULL, 'activo', '2026-09-10 03:30:40'),
(27, 'Teclado RGB', NULL, NULL, 0.00, 15, NULL, 'activo', '2026-09-10 03:32:54'),
(28, 'Teclado RGB', NULL, NULL, 0.00, -1, NULL, 'activo', '2026-09-10 03:33:32'),
(29, 'Mouse Gamer', NULL, NULL, 120000.00, -5, NULL, 'activo', '2026-09-10 03:34:43'),
(30, 'Mouse Gamer', NULL, NULL, 120000.00, 0, NULL, 'activo', '2026-09-10 03:35:05'),
(31, 'Mouse Gamer', NULL, NULL, 120000.00, -100, NULL, 'activo', '2026-09-10 03:35:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(14,2) NOT NULL,
  `estado` enum('activa','anulada') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

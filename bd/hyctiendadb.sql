-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-08-2023 a las 19:47:18
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hycdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `cantProd` int(11) DEFAULT NULL,
  `ImporteTotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`) VALUES
(1, 'Almohada'),
(2, 'Edredon'),
(3, 'Frazada'),
(4, 'Fundas'),
(5, 'Sabanas'),
(6, 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `apellido` varchar(80) DEFAULT NULL,
  `DNI` varchar(8) DEFAULT NULL,
  `telefono` varchar(9) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecarrito`
--

CREATE TABLE `detallecarrito` (
  `id` int(11) NOT NULL,
  `idCarrito` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantProd` int(11) NOT NULL,
  `precioProd` decimal(10,2) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `id` int(11) NOT NULL,
  `idFactura` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantProd` int(11) NOT NULL,
  `precioProd` decimal(10,2) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `id` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantProd` int(11) NOT NULL,
  `precioProd` decimal(10,2) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `id` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantProd` int(11) NOT NULL,
  `precioProd` decimal(10,2) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listadeseos`
--

CREATE TABLE `listadeseos` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `mensaje` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `tipo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `fecha`, `idProducto`, `cantidad`, `tipo`) VALUES
(7, '0000-00-00 00:00:00', 1, 2, 'salida'),
(8, '0000-00-00 00:00:00', 5, 1, 'salida'),
(9, '0000-00-00 00:00:00', 12, 2, 'salida'),
(10, '0000-00-00 00:00:00', 5, 1, 'salida'),
(11, '0000-00-00 00:00:00', 12, 3, 'salida'),
(12, '0000-00-00 00:00:00', 12, 3, 'salida'),
(13, '0000-00-00 00:00:00', 1, 2, 'salida'),
(14, '0000-00-00 00:00:00', 2, 2, 'salida'),
(15, '0000-00-00 00:00:00', 5, 1, 'salida'),
(16, '0000-00-00 00:00:00', 8, 2, 'salida'),
(17, '0000-00-00 00:00:00', 6, 1, 'salida'),
(18, '0000-00-00 00:00:00', 9, 1, 'salida'),
(19, '0000-00-00 00:00:00', 9, 1, 'salida'),
(20, '0000-00-00 00:00:00', 2, 2, 'salida'),
(21, '0000-00-00 00:00:00', 2, 2, 'salida'),
(22, '0000-00-00 00:00:00', 1, 2, 'salida'),
(23, '0000-00-00 00:00:00', 16, 1, 'salida'),
(24, '0000-00-00 00:00:00', 18, 6, 'salida'),
(25, '2023-08-08 12:27:41', 1, 14, 'entrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fechalimit` datetime NOT NULL,
  `cantProd` int(11) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL,
  `metpago` varchar(50) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `detalles` varchar(250) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `idcat` int(11) NOT NULL,
  `imagen1` varchar(100) NOT NULL,
  `imagen2` varchar(100) NOT NULL,
  `imagen3` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `detalles`, `precio`, `stock`, `idcat`, `imagen1`, `imagen2`, `imagen3`) VALUES
(1, 'Almohada 2x15', 'Almohada de tamaño estándar, suave y cómoda.', '15.00', 60, 1, '1.jpg', '2.jpg', '3.jpg'),
(2, 'Almohada de napa grande', 'Almohada grande y confortable, ideal para un descanso placentero.', '15.00', 119, 1, '4.jpg', '5.jpg', '6.jpg'),
(3, 'Almohada de napa chica', 'Almohada de tamaño pequeño con relleno de napa, ideal para viajes o uso individual.', '10.00', 125, 1, '7.jpg', '8.jpg', '9.jpg'),
(4, 'Almohada ortopédica', 'Almohada diseñada ergonómicamente para brindar soporte y aliviar la presión en el cuello y la cabeza.', '35.00', 145, 1, '10.jpg', '11.jpg', '12.jpg'),
(5, 'Edredón de invierno mingjia en algodón', 'Edredón de invierno de la marca Mingjia, fabricado en algodón, ideal para climas fríos.', '150.00', 84, 2, '13.jpg', '14.jpg', '15.jpg'),
(6, 'Edredón de verano 1 pieza', 'Edredón ligero y fresco de una sola pieza, perfecto para el verano.', '65.00', 64, 2, '16.jpg', '17.jpg', '18.jpg'),
(7, 'Edredón piel con carnero', 'Edredón confeccionado con piel de carnero en su diseño, brindando calidez y estilo.', '45.00', 210, 2, '19.jpg', '20.jpg', '21.jpg'),
(8, 'Edredón piel con carnero alto relieve', 'Edredón con detalles en alto relieve de piel de carnero, que agrega un toque elegante a la habitación.', '70.00', 42, 2, '22.jpg', '23.jpg', '24.jpg'),
(9, 'Edredón piel con carnero equipo 1 pieza', 'Edredón completo de una sola pieza con diseño de piel de carnero, ideal para climas fríos.', '85.00', 33, 2, '25.jpg', '26.jpg', '27.jpg'),
(10, 'Edredón piel con carnero niño', 'Edredón de tamaño niño confeccionado con piel de carnero, brindando comodidad y abrigo.', '70.00', 88, 2, '28.jpg', '29.jpg', '30.jpg'),
(11, 'Edredón piel con piel', 'Edredón confeccionado con piel de alta calidad en su diseño, proporcionando lujo y confort.', '70.00', 99, 2, '31.jpg', '32.jpg', '33.jpg'),
(12, 'Forro de colchón 1 1/2', 'Protector de colchón para cama individual de 1 1/2 plazas, fabricado en materiales resistentes.', '25.00', 180, 6, '34.jpg', '35.jpg', '36.jpg'),
(13, 'Forro de colchón 2 plazas', 'Protector de colchón para cama matrimonial o de 2 plazas, que protege contra derrames y manchas.', '30.00', 195, 6, '37.jpg', '38.jpg', '39.jpg'),
(14, 'Forro de colchón en algodón 1 1/2', 'Protector de colchón de algodón para cama individual de 1 1/2 plazas, suave y transpirable.', '45.00', 20, 6, '40.jpg', '41.jpg', '42.jpg'),
(15, 'Forro de colchón en algodón 2 plazas', 'Protector de colchón de algodón para cama matrimonial o de 2 plazas, cómodo y de calidad.', '50.00', 55, 6, '43.jpg', '44.jpg', '45.jpg'),
(16, 'Forro de colchón en algodón king', 'Protector de colchón de algodón para cama king size, que proporciona protección y suavidad.', '115.00', 24, 6, '46.jpg', '47.jpg', '48.jpg'),
(17, 'Forro de colchón en algodón queen', 'Protector de colchón de algodón para cama queen size, brindando comodidad y protección.', '98.00', 74, 6, '49.jpg', '50.jpg', '51.jpg'),
(18, 'Frazada banderita', 'Frazada suave y ligera con diseño de banderitas, perfecta para añadir un toque decorativo.', '25.00', 29, 3, '52.jpg', '53.jpg', '54.jpg'),
(19, 'Frazada de terciopelo con carnero', 'Frazada de terciopelo con detalles de carnero, brindando calidez y estilo a la cama.', '150.00', 28, 3, '55.jpg', '56.jpg', '57.jpg'),
(20, 'Frazada profitex 1 1/2', 'Frazada de la marca Profitex para cama individual de 1 1/2 plazas, cómoda y duradera.', '45.00', 100, 3, '58.jpg', '59.jpg', '60.jpg'),
(21, 'Frazada profitex 2 plazas', 'Frazada de la marca Profitex para cama matrimonial o de 2 plazas, suave y resistente.', '55.00', 38, 3, '61.jpg', '62.jpg', '63.jpg'),
(22, 'Frazada Santa catalina 1 1/2', 'Frazada de la marca Santa Catalina para cama individual de 1 1/2 plazas, de calidad y diseño elegante.', '80.00', 28, 3, '64.jpg', '65.jpg', '66.jpg'),
(23, 'Frazada Santa catalina 2 plazas', 'Frazada de la marca Santa Catalina para cama matrimonial o de 2 plazas, confortable y sofisticada.', '90.00', 42, 3, '67.jpg', '68.jpg', '69.jpg'),
(24, 'Frazada terciopelo 6 kilos', 'Frazada de terciopelo con un peso de 6 kilogramos, ideal para climas fríos y un mayor abrigo.', '150.00', 68, 3, '70.jpg', '71.jpg', '72.jpg'),
(25, 'Frazada terciopelo 6 kilos color entero', 'Frazada de terciopelo en un solo color, con un peso de 6 kilogramos, brindando suavidad y calidez.', '180.00', 74, 4, '73.jpg', '74.jpg', '75.jpg'),
(26, 'Funda de algodón 2x10', 'Funda de almohada de algodón en tamaño estándar, suave y resistente.', '10.00', 0, 4, '76.jpg', '77.jpg', '78.jpg'),
(27, 'Funda grande con cierre', 'Funda de almohada de tamaño grande con cierre, que facilita el lavado y el cambio de funda.', '18.00', 16, 4, '79.jpg', '80.jpg', '81.jpg'),
(28, 'Funda ortopédica', 'Funda de almohada diseñada específicamente para brindar soporte a cuello y cabeza, cómoda y ergonómica.', '30.00', 0, 4, '82.jpg', '83.jpg', '84.jpg'),
(29, 'Funda poliseda 2x6', 'Funda de almohada de poliseda en tamaño estándar, suave y de fácil cuidado.', '6.00', 4, 4, '85.jpg', '86.jpg', '87.jpg'),
(30, 'Juego de tela con piel de ángel King', 'Juego de cama que incluye sábanas y fundas de almohada confeccionadas en tela de calidad con detalles de piel de ángel, tamaño King.', '250.00', 1, 6, '88.jpg', '89.jpg', '90.jpg'),
(31, 'Juego de tela con tela king', 'Juego de cama que incluye sábanas y fundas de almohada confeccionadas en tela de calidad, tamaño King.', '250.00', 0, 6, '91.jpg', '92.jpg', '93.jpg'),
(32, 'Juego de tela con tela mingjia queen', 'Juego de cama que incluye sábanas y fundas de almohada confeccionadas en tela de la marca Mingjia, tamaño Queen.', '180.00', 2, 6, '94.jpg', '95.jpg', '96.jpg'),
(33, 'Juego de tela con tela niño 5 piezas', 'Juego de cama infantil que incluye sábanas y fundas de almohada confeccionadas en tela, compuesto por 5 piezas.', '155.00', 1, 6, '97.jpg', '98.jpg', '99.jpg'),
(34, 'Juego de verano con cortina goldsun', 'Juego de cama de verano que incluye sábanas y fundas de almohada en tela ligera, además de cortinas Goldsun.', '180.00', 2, 6, '100.jpg', '101.jpg', '102.jpg'),
(35, 'Juego de verano goldsun en algodon', 'Juego de cama de verano que incluye sábanas y fundas de almohada en algodón, perfecto para climas cálidos.', '140.00', 11, 6, '103.jpg', '104.jpg', '105.jpg'),
(36, 'Juego de verano niño 5 piezas', 'Juego de cama infantil de verano que incluye sábanas y fundas de almohada en tela fresca, compuesto por 5 piezas.', '155.00', 2, 6, '106.jpg', '107.jpg', '108.jpg'),
(37, 'Juego de verano poliseda', 'Juego de cama de verano que incluye sábanas y fundas de almohada de poliseda, cómodo y fácil de cuidar.', '100.00', 5, 6, '109.jpg', '110.jpg', '111.jpg'),
(38, 'Juego piel con carnero 3 piezas', 'Juego de cama que incluye sábanas y fundas de almohada con detalles de piel de carnero, compuesto por 3 piezas.', '90.00', 10, 6, '112.jpg', '113.jpg', '114.jpg'),
(39, 'Juego piel con carnero equipo de 5 piezas', 'Juego de cama completo que incluye sábanas, fundas de almohada y edredón con detalles de piel de carnero, compuesto por 5 piezas.', '180.00', 2, 6, '115.jpg', '116.jpg', '117.jpg'),
(40, 'Manta piel de durazno', 'Manta suave y cálida fabricada en piel de durazno, perfecta para los días fríos.', '25.00', 1, 6, '118.jpg', '119.jpg', '120.jpg'),
(41, 'Manta piel de peluche', 'Manta de peluche de alta calidad, suave y acogedora para brindar calidez en el descanso.', '22.00', 0, 6, '121.jpg', '122.jpg', '123.jpg'),
(42, 'Mosquetero delgado', 'Mosquetero de tamaño delgado para proteger la cama de mosquitos y otros insectos.', '25.00', 2, 6, '124.jpg', '125.jpg', '126.jpg'),
(43, 'Mosquetero grueso 1 1/2', 'Mosquetero de tamaño grueso para proteger la cama de mosquitos y otros insectos, para camas individuales de 1 1/2 plazas.', '25.00', 4, 6, '127.jpg', '128.jpg', '129.jpg'),
(44, 'Mosquetero grueso 2 plazas', 'Mosquetero de tamaño grueso para proteger la cama de mosquitos y otros insectos, para camas matrimoniales o de 2 plazas.', '35.00', 3, 6, '130.jpg', '131.jpg', '132.jpg'),
(45, 'Mosquetero grueso queen', 'Mosquetero de tamaño grueso para proteger la cama de mosquitos y otros insectos, para camas queen size.', '55.00', 2, 6, '133.jpg', '134.jpg', '135.jpg'),
(46, 'Sabana de algodón 1 pieza', 'Sábana ajustable de algodón para cama individual, suave y transpirable.', '20.00', 2, 5, '136.jpg', '137.jpg', '138.jpg'),
(47, 'Sabana Nancy color entero 1 1/2', 'Sábana de la marca Nancy en color sólido para cama individual de 1 1/2 plazas, de calidad y estilo.', '45.00', 2, 5, '139.jpg', '140.jpg', '141.jpg'),
(48, 'Sabana Nancy color entero 2 plazas', 'Sábana de la marca Nancy en color sólido para cama matrimonial o de 2 plazas, suave y duradera.', '55.00', 3, 5, '142.jpg', '143.jpg', '144.jpg'),
(49, 'Sabana Nancy de algodón 1 1/2', 'Sábana de la marca Nancy de algodón para cama individual de 1 1/2 plazas, cómoda y fresca.', '45.00', 27, 5, '145.jpg', '146.jpg', '147.jpg'),
(50, 'Sabana Nancy de algodón 2 plazas', 'Sábana de la marca Nancy de algodón para cama matrimonial o de 2 plazas, transpirable y resistente.', '50.00', 21, 5, '148.jpg', '149.jpg', '150.jpg'),
(51, 'Sabana Nancy en poliseda 1 1/2', 'Sábana de la marca Nancy de poliseda para cama individual de 1 1/2 plazas, suave y de fácil cuidado.', '28.00', 14, 5, '151.jpg', '152.jpg', '153.jpg'),
(52, 'Sabana Nancy en poliseda 2 plazas', 'Sábana de la marca Nancy de poliseda para cama matrimonial o de 2 plazas, cómoda y elegante.', '32.00', 14, 5, '154.jpg', '155.jpg', '156.jpg'),
(53, 'Sabana piel de durazno', 'Sábana suave y confortable fabricada en piel de durazno, brindando una sensación de lujo.', '25.00', 9, 5, '157.jpg', '158.jpg', '159.jpg'),
(54, 'Sabana piel de peluche', 'Sábana de peluche de alta calidad, suave y acogedora para un descanso de lujo.', '22.00', 0, 5, '160.jpg', '161.jpg', '162.jpg'),
(55, 'Sabana poliseda 1 pieza', 'Sábana de poliseda ajustable para cama individual, suave y de fácil cuidado.', '10.00', 1, 5, '163.jpg', '164.jpg', '165.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reclamos`
--

CREATE TABLE `reclamos` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `reclamo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `descripcion`) VALUES
(1, 'gerente'),
(2, 'Administrador'),
(3, 'Encargado de Almacen'),
(4, 'Cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarioadmin`
--

CREATE TABLE `usuarioadmin` (
  `id` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `DNI` varchar(8) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `idRol` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarioadmin`
--

INSERT INTO `usuarioadmin` (`id`, `nombre`, `apellido`, `DNI`, `telefono`, `idRol`, `usuario`, `contraseña`) VALUES
(1, 'gerente', 'gerente', '12345678', '123456789', 1, 'gerente', '$2y$10$ugDugERO2udxg1DlqNT3m.OXnVGQbie6suk1Gn4SdmceDoXg./Gw6'),
(2, 'admin', 'admin', '12345678', '123456789', 2, 'admin', '$2y$10$J6kxbZq7I6JcHATygQr8kORSukoJUEkr.2iMnGcOGhHC.bVziTwNK'),
(3, 'almacen', 'almacen', '12345678', '123456789', 3, 'almacen', '$2y$10$Tkvq4FqReR4nRzvX2XYdXeMcd2wnBQWpNN033vwxaWzCaDzHj3oz6'),
(4, 'cajero', 'cajero', '12345678', '123456789', 4, 'cajero', '$2y$10$jJO/irsfyVBzCp1aOq1u7.knlKx3NFTH5ypl8ZP4X7dsI4L2jalsq');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariocliente`
--

CREATE TABLE `usuariocliente` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `cantProd` int(11) NOT NULL,
  `importeTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telefono` (`telefono`),
  ADD UNIQUE KEY `DNI` (`DNI`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `detallecarrito`
--
ALTER TABLE `detallecarrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCarrito` (`idCarrito`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFactura` (`idFactura`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVenta` (`idVenta`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVenta` (`idVenta`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `listadeseos`
--
ALTER TABLE `listadeseos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idProducto` (`idProducto`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idcat` (`idcat`);

--
-- Indices de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarioadmin`
--
ALTER TABLE `usuarioadmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idRol` (`idRol`);

--
-- Indices de la tabla `usuariocliente`
--
ALTER TABLE `usuariocliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCliente` (`idCliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `detallecarrito`
--
ALTER TABLE `detallecarrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `listadeseos`
--
ALTER TABLE `listadeseos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `reclamos`
--
ALTER TABLE `reclamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarioadmin`
--
ALTER TABLE `usuarioadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuariocliente`
--
ALTER TABLE `usuariocliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariocliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariocliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallecarrito`
--
ALTER TABLE `detallecarrito`
  ADD CONSTRAINT `detallecarrito_ibfk_1` FOREIGN KEY (`idCarrito`) REFERENCES `carrito` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallecarrito_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD CONSTRAINT `detallepedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallepedido_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`idVenta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`idVenta`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`id`);

--
-- Filtros para la tabla `listadeseos`
--
ALTER TABLE `listadeseos`
  ADD CONSTRAINT `listadeseos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariocliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `listadeseos_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariocliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`idcat`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reclamos`
--
ALTER TABLE `reclamos`
  ADD CONSTRAINT `reclamos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuariocliente` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarioadmin`
--
ALTER TABLE `usuarioadmin`
  ADD CONSTRAINT `usuarioadmin_ibfk_1` FOREIGN KEY (`idRol`) REFERENCES `rol` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

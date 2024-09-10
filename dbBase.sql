-- phpMyAdmin SQL Dump
-- version 5.1.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-08-2024 a las 08:41:15
-- Versión del servidor: 8.0.36-28
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbgxzcznebwvay`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL,
  `nombre_cliente` varchar(75) NOT NULL,
  `nif` varchar(9) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `pais` int DEFAULT NULL,
  `id_provincia` varchar(6) DEFAULT NULL,
  `poblacion` varchar(175) NOT NULL,
  `telf` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `cargaen` varchar(100) NOT NULL,
  `exportacion` int NOT NULL,
  `f_pago` varchar(100) NOT NULL,
  `otros_contactos` varchar(10) NOT NULL,
  `observaciones_cliente` tinytext NOT NULL,
  `id_contacto` int DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `id` int NOT NULL,
  `url_instalacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'La utiliza Gumlet.\r\nHay que ponerla sin htps://',
  `url_fichar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `url_gumlet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_appname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tiempo_recarga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `url_instalacion`, `url_fichar`, `url_logo`, `url_gumlet`, `google_id`, `google_secret`, `google_appname`, `tiempo_recarga`) VALUES
(1, 'admin.offertiles.com/intranet', 'https://fichar.offertiles.com/public', 'logo_offertiles_web.png', 'offertiles1.gumlet.io', '261874964358-sna5t6t5adkifcbqnjj9i59klvdfn8vn.apps.googleusercontent.com', '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', 'Intranet Offertiles', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id_contacto` int NOT NULL,
  `nombre` varchar(75) NOT NULL,
  `apellidos` varchar(90) NOT NULL,
  `telf` varchar(15) NOT NULL,
  `id_cliente` int NOT NULL,
  `cargo` varchar(90) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int NOT NULL,
  `nombre_estado` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nombre_estado`) VALUES
(0, 'Pendiente de material'),
(1, 'Ha faltado material'),
(2, 'Material recibido'),
(3, 'En máquina'),
(4, 'Terminado'),
(5, 'Entregado'),
(6, 'Anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familia_productos`
--

CREATE TABLE `familia_productos` (
  `id_familia` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `en_menu` int NOT NULL COMMENT '1 si 0 no',
  `orden` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `festivos`
--

CREATE TABLE `festivos` (
  `id` int NOT NULL,
  `festivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` date NOT NULL,
  `tipo_festivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichajes`
--

CREATE TABLE `fichajes` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `entrada` datetime NOT NULL,
  `salida` datetime NOT NULL,
  `total` int NOT NULL,
  `incidencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `extras` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichajes-activos`
--

CREATE TABLE `fichajes-activos` (
  `id_empleado` int NOT NULL,
  `entrada` datetime NOT NULL,
  `id_maquina` int NOT NULL,
  `id_linea_pedido` int NOT NULL,
  `extras` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formas_pago`
--

CREATE TABLE `formas_pago` (
  `id_formapago` int NOT NULL,
  `formapago` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoy`
--

CREATE TABLE `hoy` (
  `id` int NOT NULL,
  `hoy` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE `informes` (
  `id_informe` int NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `ausencias` int NOT NULL,
  `vacaciones` int NOT NULL,
  `extras` int NOT NULL,
  `incidencias` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laborables`
--

CREATE TABLE `laborables` (
  `id` int NOT NULL,
  `lunes` int NOT NULL,
  `martes` int NOT NULL,
  `miercoles` int NOT NULL,
  `jueves` int NOT NULL,
  `viernes` int NOT NULL,
  `sabado` int NOT NULL,
  `domingo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedidos`
--

CREATE TABLE `linea_pedidos` (
  `id_lineapedido` int NOT NULL,
  `id_pedido` int NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `id_producto` int DEFAULT NULL,
  `n_piezas` int NOT NULL,
  `nom_base` varchar(75) NOT NULL,
  `nom_inserto` varchar(75) NOT NULL,
  `tono` varchar(3) NOT NULL,
  `cal` varchar(3) NOT NULL,
  `torelo` varchar(7) NOT NULL,
  `med_inicial` varchar(15) NOT NULL,
  `med_final` varchar(15) NOT NULL,
  `lado` varchar(15) NOT NULL,
  `distancia` varchar(15) NOT NULL,
  `observaciones` tinytext NOT NULL,
  `id_usuario` smallint NOT NULL DEFAULT '0',
  `unidades` varchar(7) NOT NULL,
  `precio_venta` char(12) NOT NULL,
  `manipulacion` char(7) NOT NULL,
  `descuento` char(7) NOT NULL,
  `add_linea` smallint NOT NULL DEFAULT '0' COMMENT 'Es para añadir el botón de añadir lineas',
  `total_linea` char(8) NOT NULL,
  `estado` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id_log` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_usuario` varchar(20) NOT NULL,
  `log` mediumtext NOT NULL,
  `seccion` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id_log`, `fecha`, `id_usuario`, `log`, `seccion`) VALUES
(130, '2024-08-20 12:01:07', 'Maria Sancho Prades', 'Añade usuario', 'Usuarios'),
(131, '2024-08-20 12:01:11', 'Maria Sancho Prades', 'Elimina usuario, ID: 11', 'Usuarios'),
(132, '2024-08-20 12:13:27', 'Maria Sancho Prades', 'Añade usuario', 'Usuarios'),
(133, '2024-08-30 10:38:28', 'Maria Sancho Prades', 'Elimina usuario, ID: 6', 'Usuarios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maquinas`
--

CREATE TABLE `maquinas` (
  `id_maquina` int NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `titulo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dependencia` int NOT NULL DEFAULT '0',
  `enlace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `estilo` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `posicion` int NOT NULL,
  `activo` int NOT NULL DEFAULT '1',
  `nivel` int NOT NULL,
  `url_especial` int NOT NULL DEFAULT '0',
  `separador` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nueva_pestana` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `titulo`, `dependencia`, `enlace`, `estilo`, `posicion`, `activo`, `nivel`, `url_especial`, `separador`, `nueva_pestana`) VALUES
(1, 'Clientes', 0, '', 'clientes', 1, 1, 5, 0, '', 0),
(2, 'Empresas', 1, 'Empresas', 'empresas', 1, 1, 5, 0, '', 0),
(3, 'Contactos', 1, 'Contactos', 'contactos', 2, 1, 5, 0, '', 0),
(4, 'Pedidos', 0, '', 'pedidos', 2, 1, 5, 0, '', 0),
(5, 'Nuevo pedido', 4, 'Pedidos2/add', 'nuevopedido', 1, 1, 5, 1, '', 0),
(6, 'Pedidos en marcha', 4, 'Pedidos2/enmarcha/', 'pedidosenmarcha', 2, 1, 5, 0, '', 0),
(7, 'Pedidos terminados', 4, '/Pedidos2/terminados/', 'pedidosterminados', 3, 1, 5, 0, '', 0),
(8, 'Pedidos entregados', 4, 'Pedidos2/entregados/', 'pedidosentregados', 4, 1, 5, 0, '', 0),
(9, 'Todos los pedidos', 4, '/Pedidos2/', 'todoslospedidos', 5, 1, 5, 0, '', 0),
(10, 'Producción', 0, '', 'produccion', 3, 1, 6, 0, '', 0),
(11, 'Pendientes de Material', 10, '/Lista_produccion/pendientes', 'pendientesdematerial', 1, 1, 6, 0, '', 0),
(12, 'Partes en cola', 10, '/Lista_produccion/enmarcha/', 'partesencola', 2, 1, 6, 1, '', 0),
(17, 'Configuración', 0, '', 'configuracion', 8, 1, 9, 0, 'abajo', 0),
(18, 'Menú', 17, 'Menu', 'menu', 5, 1, 9, 0, '', 0),
(22, 'Transporte', 0, '', 'transporte', 4, 1, 5, 0, '', 0),
(23, 'Productos', 0, '', 'productos', 5, 1, 7, 0, '', 0),
(24, 'Fichajes', 0, '', 'fichajes', 6, 1, 6, 0, 'arriba', 0),
(25, 'Usuarios', 0, '', 'usuarios', 7, 1, 6, 0, 'abajo', 0),
(26, 'Log', 17, '/Log', 'log', 1, 1, 9, 0, '', 0),
(28, 'Niveles de acceso', 17, '/Niveles_acceso', 'nivelesacceso', 3, 1, 9, 0, '', 0),
(29, 'Usuarios', 25, '/usuarios', 'usuarios', 1, 1, 6, 0, '', 0),
(30, 'Vacaciones', 25, '/Vacaciones/', 'vacaciones', 2, 1, 6, 0, '', 0),
(32, 'Fichajes', 24, '/Fichajes/', 'fichajes', 1, 1, 6, 0, '', 0),
(33, 'Festivos', 24, '/Festivos', 'festivos', 2, 1, 6, 0, '', 0),
(34, 'Informes', 24, '/Informes', 'informes', 3, 1, 6, 0, '', 0),
(35, 'Laborables', 24, '/Laborables', 'laborables', 4, 1, 6, 0, '', 0),
(36, 'Productos', 23, '/Productos', 'productos', 1, 1, 7, 0, '', 0),
(37, 'Familias', 23, '/Familia_productos', 'familias', 2, 1, 7, 0, '', 0),
(38, 'Procesos', 23, '/Procesos', 'procesos', 3, 1, 7, 0, '', 0),
(39, 'Máquinas', 23, '/Maquinas', 'maquinas', 4, 1, 7, 0, '', 0),
(40, 'Rutas en marcha', 22, '/Rutas/enmarcha', 'rutasenmarcha', 1, 1, 5, 0, '', 0),
(41, 'Todas las rutas', 22, '/Rutas/', 'rutas', 2, 1, 5, 0, '', 0),
(42, 'Vista transportista', 22, '/Rutas_transporte/rutas', 'vistatransportista', 3, 1, 5, 0, '', 1),
(43, 'Poblaciones', 22, '/Poblaciones_rutas', 'poblaciones', 4, 1, 6, 0, '', 0),
(44, 'Partes en producción', 10, '/Lista_produccion/enmaquina/', 'partesenproduccion', 3, 1, 6, 1, '', 0),
(45, 'Partes terminados', 10, '/Lista_produccion/terminados', 'partesterminados', 4, 1, 6, 0, '', 0),
(46, 'Todos los partes', 10, '/Lista_produccion/todoslospartes', 'todoslospartes', 5, 1, 6, 0, '', 0),
(51, 'ORGANIZADOR', 10, 'Procesos_pedidos/index', '', 6, 1, 6, 0, 'arriba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles_acceso`
--

CREATE TABLE `niveles_acceso` (
  `id_nivel` int NOT NULL,
  `nombre_nivel` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `id` int NOT NULL,
  `iso` char(2) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `referencia` varchar(75) NOT NULL,
  `observaciones` tinytext NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `estante` varchar(15) NOT NULL,
  `id_usuario` int NOT NULL,
  `total_pedido` char(15) NOT NULL,
  `detalles` int NOT NULL,
  `estado` int NOT NULL DEFAULT '0',
  `pedido_por` varchar(20) NOT NULL DEFAULT 'ATTAINET TECHNOLOGY',
  `representante` int NOT NULL,
  `bt_imprimir` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poblaciones_rutas`
--

CREATE TABLE `poblaciones_rutas` (
  `id_poblacion` int NOT NULL,
  `poblacion` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE `procesos` (
  `id_proceso` int NOT NULL,
  `nombre_proceso` varchar(50) NOT NULL,
  `id_maquina` int DEFAULT NULL,
  `estado_proceso` tinyint(1) NOT NULL,
  `restriccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos_pedidos`
--

CREATE TABLE `procesos_pedidos` (
  `id_relacion` int NOT NULL,
  `id_proceso` int DEFAULT NULL,
  `id_linea_pedido` int DEFAULT NULL,
  `id_maquina` int DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `orden` int DEFAULT NULL,
  `restriccion` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos_productos`
--

CREATE TABLE `procesos_productos` (
  `id_relacion` int NOT NULL,
  `id_producto` int DEFAULT NULL,
  `id_proceso` int DEFAULT NULL,
  `orden` int NOT NULL,
  `restriccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int NOT NULL,
  `nombre_producto` varchar(70) NOT NULL,
  `id_familia` int DEFAULT NULL,
  `imagen` varchar(75) NOT NULL,
  `precio` char(10) NOT NULL DEFAULT '0',
  `unidad` int DEFAULT NULL,
  `estado_producto` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `id_provincia` smallint NOT NULL DEFAULT '0',
  `provincia` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id_representante` int NOT NULL,
  `nombre` varchar(17) NOT NULL,
  `apellidos` varchar(35) NOT NULL,
  `telf` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id_ruta` int NOT NULL,
  `id_pedido` int NOT NULL,
  `id_cliente` int NOT NULL,
  `recogida_entrega` int NOT NULL,
  `observaciones` tinytext NOT NULL,
  `poblacion` varchar(25) DEFAULT NULL,
  `transportista` int DEFAULT NULL,
  `estado_ruta` int NOT NULL,
  `lugar` varchar(25) NOT NULL,
  `fecha_ruta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `id_unidad` int NOT NULL DEFAULT '0',
  `nombre_unidad` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nombre_usuario` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `apellidos_usuario` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_ficha` int NOT NULL DEFAULT '0',
  `user_activo` int NOT NULL DEFAULT '1',
  `userfoto` varchar(255) NOT NULL,
  `email` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `id_acceso` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre_usuario`, `apellidos_usuario`, `user_ficha`, `user_activo`, `userfoto`, `email`, `telefono`, `fecha_alta`, `fecha_baja`, `id_acceso`) VALUES
(3, 'Maria', 'Sancho Prades', 0, 1, '', NULL, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoresboleanos`
--

CREATE TABLE `valoresboleanos` (
  `idvalor` int NOT NULL,
  `valor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_linea_pedidos_con_familia`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_linea_pedidos_con_familia` (
`id_lineapedido` int
,`id_pedido` int
,`fecha_entrada` date
,`fecha_entrega` date
,`id_producto` int
,`n_piezas` int
,`nom_base` varchar(75)
,`nom_inserto` varchar(75)
,`tono` varchar(3)
,`cal` varchar(3)
,`torelo` varchar(7)
,`med_inicial` varchar(15)
,`med_final` varchar(15)
,`lado` varchar(15)
,`distancia` varchar(15)
,`observaciones` tinytext
,`id_usuario` smallint
,`unidades` varchar(7)
,`precio_venta` char(12)
,`manipulacion` char(7)
,`descuento` char(7)
,`add_linea` smallint
,`total_linea` char(8)
,`estado` int
,`id_familia` int
,`id_cliente` int
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_linea_pedidos_con_familia`
--
DROP TABLE IF EXISTS `v_linea_pedidos_con_familia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sbendx6woh3yy`@`localhost` SQL SECURITY DEFINER VIEW `v_linea_pedidos_con_familia`  AS SELECT `lp`.`id_lineapedido` AS `id_lineapedido`, `lp`.`id_pedido` AS `id_pedido`, `lp`.`fecha_entrada` AS `fecha_entrada`, `lp`.`fecha_entrega` AS `fecha_entrega`, `lp`.`id_producto` AS `id_producto`, `lp`.`n_piezas` AS `n_piezas`, `lp`.`nom_base` AS `nom_base`, `lp`.`nom_inserto` AS `nom_inserto`, `lp`.`tono` AS `tono`, `lp`.`cal` AS `cal`, `lp`.`torelo` AS `torelo`, `lp`.`med_inicial` AS `med_inicial`, `lp`.`med_final` AS `med_final`, `lp`.`lado` AS `lado`, `lp`.`distancia` AS `distancia`, `lp`.`observaciones` AS `observaciones`, `lp`.`id_usuario` AS `id_usuario`, `lp`.`unidades` AS `unidades`, `lp`.`precio_venta` AS `precio_venta`, `lp`.`manipulacion` AS `manipulacion`, `lp`.`descuento` AS `descuento`, `lp`.`add_linea` AS `add_linea`, `lp`.`total_linea` AS `total_linea`, `lp`.`estado` AS `estado`, `p`.`id_familia` AS `id_familia`, `c`.`id_cliente` AS `id_cliente` FROM (((`linea_pedidos` `lp` join `productos` `p` on((`lp`.`id_producto` = `p`.`id_producto`))) join `pedidos` `pe` on((`lp`.`id_pedido` = `pe`.`id_pedido`))) join `clientes` `c` on((`pe`.`id_cliente` = `c`.`id_cliente`)))  ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_provincia` (`id_provincia`),
  ADD KEY `pais` (`pais`),
  ADD KEY `nombre` (`nombre_cliente`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id_contacto`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `familia_productos`
--
ALTER TABLE `familia_productos`
  ADD PRIMARY KEY (`id_familia`);

--
-- Indices de la tabla `festivos`
--
ALTER TABLE `festivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fichajes`
--
ALTER TABLE `fichajes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fichajes-activos`
--
ALTER TABLE `fichajes-activos`
  ADD UNIQUE KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `formas_pago`
--
ALTER TABLE `formas_pago`
  ADD PRIMARY KEY (`id_formapago`);

--
-- Indices de la tabla `hoy`
--
ALTER TABLE `hoy`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `informes`
--
ALTER TABLE `informes`
  ADD PRIMARY KEY (`id_informe`);

--
-- Indices de la tabla `laborables`
--
ALTER TABLE `laborables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `linea_pedidos`
--
ALTER TABLE `linea_pedidos`
  ADD PRIMARY KEY (`id_lineapedido`),
  ADD KEY `id_pedido` (`id_pedido`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id_maquina`),
  ADD UNIQUE KEY `id_maquina_2` (`id_maquina`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `niveles_acceso`
--
ALTER TABLE `niveles_acceso`
  ADD PRIMARY KEY (`id_nivel`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombre` (`nombre`),
  ADD KEY `id` (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `estado` (`estado`),
  ADD KEY `representante` (`representante`);

--
-- Indices de la tabla `poblaciones_rutas`
--
ALTER TABLE `poblaciones_rutas`
  ADD PRIMARY KEY (`id_poblacion`);

--
-- Indices de la tabla `procesos`
--
ALTER TABLE `procesos`
  ADD PRIMARY KEY (`id_proceso`),
  ADD KEY `maquina` (`id_maquina`);

--
-- Indices de la tabla `procesos_pedidos`
--
ALTER TABLE `procesos_pedidos`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `fk_procesosPedidos_linea_pedidio` (`id_linea_pedido`),
  ADD KEY `fk_procesosPedidos_maquinas` (`id_maquina`),
  ADD KEY `fk_procesosPedidos_procesos` (`id_proceso`);

--
-- Indices de la tabla `procesos_productos`
--
ALTER TABLE `procesos_productos`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `producto` (`id_producto`,`id_proceso`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_familia` (`id_familia`),
  ADD KEY `unidad` (`unidad`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id_provincia`),
  ADD UNIQUE KEY `id_provincia_2` (`id_provincia`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id_representante`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id_ruta`);

--
-- Indices de la tabla `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id_unidad`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `valoresboleanos`
--
ALTER TABLE `valoresboleanos`
  ADD PRIMARY KEY (`idvalor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id_contacto` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `familia_productos`
--
ALTER TABLE `familia_productos`
  MODIFY `id_familia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `festivos`
--
ALTER TABLE `festivos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `fichajes`
--
ALTER TABLE `fichajes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `formas_pago`
--
ALTER TABLE `formas_pago`
  MODIFY `id_formapago` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1718;

--
-- AUTO_INCREMENT de la tabla `hoy`
--
ALTER TABLE `hoy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `informes`
--
ALTER TABLE `informes`
  MODIFY `id_informe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `laborables`
--
ALTER TABLE `laborables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `linea_pedidos`
--
ALTER TABLE `linea_pedidos`
  MODIFY `id_lineapedido` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42995;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de la tabla `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id_maquina` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16728;

--
-- AUTO_INCREMENT de la tabla `poblaciones_rutas`
--
ALTER TABLE `poblaciones_rutas`
  MODIFY `id_poblacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `procesos`
--
ALTER TABLE `procesos`
  MODIFY `id_proceso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `procesos_pedidos`
--
ALTER TABLE `procesos_pedidos`
  MODIFY `id_relacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=801;

--
-- AUTO_INCREMENT de la tabla `procesos_productos`
--
ALTER TABLE `procesos_productos`
  MODIFY `id_relacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4564;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id_representante` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id_ruta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8526;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `valoresboleanos`
--
ALTER TABLE `valoresboleanos`
  MODIFY `idvalor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `linea_pedidos`
--
ALTER TABLE `linea_pedidos`
  ADD CONSTRAINT `Borrar?dependientes` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `existen pedidos` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `procesos_pedidos`
--
ALTER TABLE `procesos_pedidos`
  ADD CONSTRAINT `fk_procesosPedidos_linea_pedidio` FOREIGN KEY (`id_linea_pedido`) REFERENCES `linea_pedidos` (`id_lineapedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_procesosPedidos_procesos` FOREIGN KEY (`id_proceso`) REFERENCES `procesos` (`id_proceso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `procesos_productos`
--
ALTER TABLE `procesos_productos`
  ADD CONSTRAINT `borrarproducto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

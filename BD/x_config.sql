-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 04-07-2024 a las 15:50:20
-- Versión del servidor: 10.11.8-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `x_config`
--

CREATE TABLE `x_config` (
  `id_config` int(11) NOT NULL COMMENT 'ID',
  `des_config` varchar(50) NOT NULL COMMENT 'Configuracion',
  `val` varchar(70) NOT NULL COMMENT 'Valor',
  `cod_su` int(11) NOT NULL DEFAULT 1 COMMENT 'Sucursal',
  `user_mod` smallint(6) NOT NULL DEFAULT 0 COMMENT 'Habilitar MOD',
  `nota_uso` varchar(250) NOT NULL COMMENT 'Nota',
  `nota` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `x_config`
--

INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`, `nota`) VALUES
(1, 'Tipo Utilidad', 'A', 1, 2, '', ''),
(2, 'REGIMEN', 'COMUN', 1, 2, '', ''),
(3, 'PUBLICO_GENERAL', 'No Aplica', 1, 2, '', ''),
(4, 'url_LOGO_A', 'Imagenes/LOGOS_EMPRESAS/LOGO_VET_ARAUCO.png', 1, 2, '', ''),
(5, 'url_LOGO_B', 'Imagenes/LOGOS_EMPRESAS/LOGO_VET_ARAUCO.png', 1, 2, '', ''),
(6, 'X_fac', '27.9cm', 1, 2, '', ''),
(7, 'Variable_size_imp_carta', '1', 1, 2, '', ''),
(8, 'x1', '130px', 1, 2, '', ''),
(9, 'y1', '50px', 1, 2, '', ''),
(10, 'X', '200px', 1, 2, '', ''),
(11, 'Y', '60px', 1, 2, '', ''),
(12, 'horaApertura', '00:00:00 am', 1, 2, '', ''),
(13, 'horaCierre', '19:29:59 pm', 1, 2, '', ''),
(14, 'PUNTUACION_MILES', '.', 1, 2, '', ''),
(15, 'PUNTUACION_DECIMALES', ',', 1, 2, '', ''),
(16, 'OPC_COMPRAS_REPLACEREF', '0', 1, 2, '', ''),
(17, 'OPC_FACVEN_BUSQ_STOCK', '1', 1, 2, '', ''),
(18, 'usar_iva', '1', 1, 2, '', ''),
(19, 'MAIN_ID_BAR', '0', 1, 2, '', ''),
(20, 'ver_util', '1', 1, 2, '', ''),
(21, 'usar_ubica', '1', 1, 2, '', ''),
(22, 'TIPO_CHUZO', 'DRO', 1, 2, '', ''),
(23, 'cert_import', '0', 1, 2, '', ''),
(24, 'imp_solo_pos', '1', 1, 2, '', ''),
(25, 'tipo_redondeo', 'decimal', 1, 2, '', ''),
(26, 'redondear_pvp_costo', 's', 1, 2, '', ''),
(27, 'redondear_util', 's', 1, 2, '', ''),
(28, 'global_text_fabricante', 'Marca', 1, 2, '', ''),
(29, '_descuentosFabricante', '0', 1, 2, '', ''),
(30, 'usar_costo_dcto', '1', 1, 2, '', ''),
(31, 'promediar_costos', '0', 1, 2, '', ''),
(32, 'vende_sin_cant', '0', 1, 2, '', ''),
(33, 'dias_anula_comps', '500', 1, 2, '', ''),
(35, 'fecha_lim_anulaVenta', 'AND ( MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW()) )', 1, 2, '', ''),
(36, 'mod_costos', '0', 1, 2, '', ''),
(37, 'tipo_imp_comprobantes', 'COM', 1, 2, '', ''),
(38, 'ImpURLcomprobantesPOS', 'imp_comp_ingre_pos.php', 1, 2, '', ''),
(39, 'ImpURLcomprobantesCOM', 'imp_comp_ingre.php', 1, 2, '', ''),
(40, 'ImpURLcontado', 'imp_fac_ven.php', 1, 2, '', ''),
(41, 'ImpURLcredito', 'imp_fac_ven_cre.php', 1, 2, '', ''),
(42, 'tipo_impresion', 'COM', 1, 2, '', ''),
(43, 'precioBonoCasco', '50000', 1, 2, '', ''),
(44, 'ver_pvp_sin_iva', '1', 1, 2, '', ''),
(45, 'impFacVen_mini', '1', 1, 2, '', ''),
(46, 'usar_remision', '0', 1, 2, '', ''),
(47, 'cross_fac', '1', 1, 2, '', ''),
(48, 'usar_bsf', '0', 1, 2, '', ''),
(49, 'usar_posFac', '0', 1, 2, '', ''),
(50, 'tipo_fac_default', 'POS', 1, 2, '', ''),
(51, 'ventas_credito', '0', 1, 2, '', ''),
(52, 'vista_remi', 'A', 1, 2, '', ''),
(53, 'usar_costo_remi', '0', 1, 2, '', ''),
(54, 'lim_dcto', '30.5', 1, 2, '', ''),
(55, 'per_credito', '5', 1, 2, '', ''),
(56, 'per_mayo', '0.25', 1, 1, '', ''),
(57, 'caja', '0', 1, 2, '', ''),
(58, 'Adminlvl', '10', 1, 2, '', ''),
(59, 'CEOlvl', '5', 1, 2, '', ''),
(60, 'Multilvl', '4', 1, 2, '', ''),
(61, 'Midlvl', '3', 1, 2, '', ''),
(62, 'Cajalvl', '2', 1, 2, '', ''),
(63, 'Bottonlvl', '1', 1, 2, '', ''),
(64, 'NIT_FANALCA', '890301886-1', 1, 2, '', ''),
(65, 'impCompra', 'A', 1, 2, '', ''),
(66, 'confirmar_tras', 'manual', 1, 2, '', ''),
(67, 'CARTERA', '1', 1, 2, '', ''),
(68, 'CARROS_RUTAS', '0', 1, 2, '', ''),
(69, 'ANTICIPOS', '0', 1, 2, '', ''),
(70, 'TRASLADOS', '0', 1, 2, '', ''),
(71, 'REMISIONES', '0', 1, 2, '', ''),
(72, 'CARGAR_CARROS', '0', 1, 2, '', ''),
(73, 'GASTOS', '1', 1, 2, '', ''),
(74, 'FLUJO_KARDEX', '0', 1, 2, '', ''),
(75, 'SERVICIOS', '0', 1, 2, '', ''),
(76, 'PVP_CREDITO', '1', 1, 2, '', ''),
(77, 'COTIZACIONES', '1', 1, 2, '', ''),
(78, 'MULTISEDES_UNIFICADAS', '0', 1, 2, '', ''),
(79, 'PAGO_EFECTIVO_TARJETA', '0', 1, 2, '', ''),
(80, 'AUTO_BAN_CLI', '0', 1, 2, '', ''),
(81, 'UN_BAN_CLI2', '0', 1, 2, '', ''),
(82, 'QUICK_FAC_INPUT', '1', 1, 2, '', ''),
(83, 'CUENTAS_BANCOS', '1', 1, 2, '', ''),
(84, 'VEHICULOS', '0', 1, 2, '', ''),
(85, 'PVP_MAYORISTA', '0', 1, 2, '', ''),
(86, 'LIM_FAC_REMI', '0', 1, 2, '', ''),
(87, 'CUENTAS_PAGAR', '1', 1, 2, '', ''),
(88, 'BALANCES_CONTABLES', '1', 1, 2, '', ''),
(89, 'EGRESOS_2', '1', 1, 2, '', ''),
(90, 'INFORMES_VENTAS_2', '1', 1, 2, '', ''),
(91, 'ROTACION_INV', '1', 1, 2, '', ''),
(92, 'MULTISEDES', '0', 1, 2, '', ''),
(93, 'COMI_VENTAS', '0', 1, 2, '', ''),
(94, 'APLICA_VEHI', '0', 1, 2, '', ''),
(95, 'DES_FULL', '0', 1, 2, '', ''),
(96, 'ALIAS_CLI', '0', 1, 2, '', ''),
(97, 'PLAN_AMOR', '0', 1, 2, '', ''),
(98, 'CARTERA_MASS', '0', 1, 2, '', ''),
(99, 'COD_GARANTIA', '0', 1, 2, '', ''),
(100, 'IMPORT_CSV', '0', 1, 2, '', ''),
(101, 'IGLESIAS', '0', 1, 2, '', ''),
(102, 'RETENCIONES', '1', 1, 2, '', ''),
(103, 'RES_VEN', '0', 1, 2, '', ''),
(104, 'BAN_DCTO_CRE', '0', 1, 2, '', ''),
(105, 'PVP_COTIZA', '1', 1, 2, '', ''),
(106, 'ARQ_VEN_RESOL', '0', 1, 2, '', ''),
(107, 'MAYORISTA_PER', '1', 1, 2, '', ''),
(108, 'ListEgreA', '0', 1, 2, '', ''),
(109, 'ListEgreB', '0', 1, 2, '', ''),
(110, 'ListEgreC', '0', 1, 2, '', ''),
(111, 'ListEgreD', '1', 1, 2, '', ''),
(112, 'usar_serial', '0', 1, 2, '', ''),
(114, 'IMG_PRODUCTO', '0', 1, 2, '', ''),
(115, 'impuesto_consumo', '0', 1, 2, '', ''),
(116, 'valor_impuesto_bolsas', '20', 1, 1, '', ''),
(117, 'impuesto_bolsas', '0', 1, 1, '', ''),
(121, 'formatos_peluqueria', '0', 1, 2, '', ''),
(122, 'COMPRAS', '1', 1, 2, '', ''),
(123, 'DEVOLUCIONES', '1', 1, 2, '', ''),
(124, 'VENTA_VEHICULOS', '0', 1, 2, '', ''),
(125, 'ARQUEOS', '1', 1, 2, '', ''),
(126, 'VALOR_CURSO_NOMINA', '0', 1, 2, '', ''),
(127, 'INVENTARIO_PVP_UNIFICADO', '0', 1, 2, '', ''),
(140, 'NUM_NOTA_ENTREGA', '0', 1, 2, '', ''),
(141, 'fecha_corte_cuentas_pagar', '', 1, 2, '', ''),
(142, 'planes_clientes', '0', 1, 2, '', ''),
(143, 'limitar_solo_contratos_credito', '0', 1, 2, '', ''),
(144, 'usar_color', '0', 1, 2, '', ''),
(145, 'usar_datos_motos', '0', 1, 2, '', ''),
(146, 'usar_fracciones_unidades', '1', 1, 2, '', ''),
(147, 'mesas_pedidos', '0', 1, 2, '', ''),
(148, 'ID_CLI_DCTO_ESPECIAL_1', '0', 1, 2, '', ''),
(149, 'ID_CLI_DCTO_ESPECIAL_2', '0', 1, 2, '', ''),
(150, 'fac_ven_font_size', '10', 1, 2, '', ''),
(151, 'ropa_campos_extra', '0', 1, 2, '', ''),
(152, 'label_pvp_mayo', 'PvP Mayorista', 1, 2, '', ''),
(153, 'DIAS_MORA_CREDITO', '0', 1, 2, '', ''),
(154, 'igualar_precios_traslados', '0', 1, 2, '', ''),
(155, 'imp_fac_formatoCustom', '0', 1, 2, '', ''),
(156, 'ver_inv_sedes', '0', 1, 2, '', ''),
(157, 'modulo_planes_internet', '0', 1, 2, '', ''),
(158, 'cartera_restar_devoluciones', '1', 1, 2, '', ''),
(159, 'FACTURACION_ELECTRONICA', '1', 1, 2, '', ''),
(160, 'label_campo_add_01', 'CUM', 1, 2, '', ''),
(161, 'label_campo_add_02', 'MG', 1, 2, '', ''),
(162, 'usar_campos_01_02', '0', 1, 2, '', ''),
(181, 'imp_logo', '1', 1, 2, '', ''),
(182, 'usar_remisiones', '0', 1, 2, '', ''),
(183, 'dctos_ropa', '0', 1, 2, '', ''),
(184, 'fix_lector_barras', '0', 1, 2, '', ''),
(185, 'impuestos_consumo', '0', 1, 2, '', ''),
(186, 'ganancia_ventas_mayorista', '0', 1, 2, '', ''),
(225, 'usar_firmas_factura', '0', 1, 2, '', ''),
(227, 'usar_firmas_cajas', '0', 1, 2, '', ''),
(235, 'dia_limite_pago_facturas', '6', 1, 1, '', ''),
(236, 'fac_servicios_mensuales', '0', 1, 2, '', ''),
(245, 'mostrar_dcto_fac', '0', 1, 2, '', ''),
(250, 'fac_ven_verSubtotales', '0', 1, 2, '', ''),
(258, 'dia_suspension', '0', 1, 2, '', ''),
(260, 'descuento_despues_iva_ventas', '0', 1, 2, '', ''),
(261, 'separar_registros_por_usuarios', '0', 1, 2, '', ''),
(263, 'vencimiento_meses_resol_dian', '18', 1, 2, '', ''),
(264, 'limite_anula_compras', '350', 1, 2, '', ''),
(265, 'limite_anula_ventas', '350', 1, 2, '', ''),
(266, 'usar_decimales_exactos', '0', 1, 2, '', ''),
(268, 'label_fabricado', '0', 1, 1, '', ''),
(269, 'imprimir_remi_pos', '0', 1, 2, '', ''),
(270, 'usar_productos2', '0', 1, 2, '', ''),
(275, 'usar_tipos_productos', '0', 1, 2, '', ''),
(277, 'mod_ivas_facs', '0', 1, 1, '', ''),
(278, 'dia_sin_iva', '1', 1, 2, '', ''),
(279, 'ganancia_ventas_mayorista2', '0', 1, 2, '', ''),
(280, 'TIPO_FAC', 'A', 1, 2, '', 'Afecta la cantidad cargada por defecto en las facs. de Venta. A: cant 1 B: cant vacia y focus.'),
(281, 'mostrar_cod_bar_facVenta', '0', 1, 2, '', ''),
(283, 'company_fe', 'DATAICO', 1, 2, 'MATIAS, DATAICO', ''),
(284, 'send_dian', 'true', 1, 2, 'false | true. Activa o desactiva envios de documentos a la Dian', ''),
(285, 'ResolCompartidaFE', '', 1, 2, 'Codigo de sucursales que comparten Resoluciones Dian. Separadas por coma.', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `x_config`
--
ALTER TABLE `x_config`
  ADD PRIMARY KEY (`id_config`),
  ADD UNIQUE KEY `des_config` (`des_config`,`val`,`cod_su`),
  ADD UNIQUE KEY `des_config_2` (`des_config`,`cod_su`),
  ADD UNIQUE KEY `des_config_3` (`des_config`,`cod_su`),
  ADD UNIQUE KEY `des_config_4` (`des_config`,`cod_su`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `x_config`
--
ALTER TABLE `x_config`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=286;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

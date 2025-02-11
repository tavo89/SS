
CREATE TABLE IF NOT EXISTS `x_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `des_config` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `val` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `des_config` (`des_config`,`val`,`cod_su`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=112 ;

 

INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`) VALUES
(29, '_descuentosFabricante', '0', 1),
(58, 'Adminlvl', '10', 1),
(96, 'ALIAS_CLI', '0', 1),
(69, 'ANTICIPOS', '1', 1),
(94, 'APLICA_VEHI', '1', 1),
(106, 'ARQ_VEN_RESOL', '0', 1),
(80, 'AUTO_BAN_CLI', '1', 1),
(88, 'BALANCES_CONTABLES', '1', 1),
(104, 'BAN_DCTO_CRE', '0', 1),
(63, 'Bottonlvl', '1', 1),
(57, 'caja', '0', 1),
(62, 'Cajalvl', '2', 1),
(72, 'CARGAR_CARROS', '0', 1),
(68, 'CARROS_RUTAS', '0', 1),
(67, 'CARTERA', '1', 1),
(98, 'CARTERA_MASS', '0', 1),
(59, 'CEOlvl', '5', 1),
(23, 'cert_import', '0', 1),
(99, 'COD_GARANTIA', '1', 1),
(93, 'COMI_VENTAS', '1', 1),
(66, 'confirmar_tras', 'manual', 1),
(77, 'COTIZACIONES', '1', 1),
(47, 'cross_fac', '0', 1),
(83, 'CUENTAS_BANCOS', '0', 1),
(87, 'CUENTAS_PAGAR', '1', 1),
(95, 'DES_FULL', '0', 1),
(33, 'dias_anula_comps', '500', 1),
(89, 'EGRESOS_2', '1', 1),
(34, 'fecha_lim_anulaCompra', '', 1),
(35, 'fecha_lim_anulaVenta', 'AND ( MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW()) )', 1),
(74, 'FLUJO_KARDEX', '0', 1),
(73, 'GASTOS', '1', 1),
(28, 'global_text_fabricante', 'Marca', 1),
(12, 'horaApertura', '00:00:00 am', 1),
(13, 'horaCierre', '19:29:59 pm', 1),
(101, 'IGLESIAS', '0', 1),
(24, 'imp_solo_pos', '1', 1),
(65, 'impCompra', 'A', 1),
(45, 'impFacVen_mini', '0', 1),
(100, 'IMPORT_CSV', '0', 1),
(39, 'ImpURLcomprobantesCOM', 'imp_comp_ingre.php', 1),
(38, 'ImpURLcomprobantesPOS', 'imp_comp_ingre_pos.php', 1),
(40, 'ImpURLcontado', 'imp_fac_ven.php', 1),
(41, 'ImpURLcredito', 'imp_fac_ven_cre.php', 1),
(90, 'INFORMES_VENTAS_2', '1', 1),
(54, 'lim_dcto', '30.5', 1),
(86, 'LIM_FAC_REMI', '0', 1),
(108, 'ListEgreA', '0', 1),
(109, 'ListEgreB', '0', 1),
(110, 'ListEgreC', '0', 1),
(111, 'ListEgreD', '1', 1),
(19, 'MAIN_ID_BAR', '0', 1),
(107, 'MAYORISTA_PER', '0', 1),
(61, 'Midlvl', '3', 1),
(36, 'mod_costos', '0', 1),
(60, 'Multilvl', '4', 1),
(92, 'MULTISEDES', '0', 1),
(78, 'MULTISEDES_UNIFICADAS', '0', 1),
(64, 'NIT_FANALCA', '890301886-1', 1),
(16, 'OPC_COMPRAS_REPLACEREF', '0', 1),
(17, 'OPC_FACVEN_BUSQ_STOCK', '1', 1),
(79, 'PAGO_EFECTIVO_TARJETA', '0', 1),
(55, 'per_credito', '5', 1),
(56, 'per_mayo', '0.25', 1),
(97, 'PLAN_AMOR', '0', 1),
(43, 'precioBonoCasco', '50000', 1),
(31, 'promediar_costos', '0', 1),
(3, 'PUBLICO_GENERAL', 'CLIENTE GENERAL', 1),
(15, 'PUNTUACION_DECIMALES', ',', 1),
(14, 'PUNTUACION_MILES', '.', 1),
(105, 'PVP_COTIZA', '0', 1),
(76, 'PVP_CREDITO', '0', 1),
(85, 'PVP_MAYORISTA', '0', 1),
(82, 'QUICK_FAC_INPUT', '0', 1),
(26, 'redondear_pvp_costo', 's', 1),
(27, 'redondear_util', 's', 1),
(2, 'REGIMEN', 'COMUN', 1),
(71, 'REMISIONES', '1', 1),
(103, 'RES_VEN', '0', 1),
(102, 'RETENCIONES', '1', 1),
(91, 'ROTACION_INV', '1', 1),
(75, 'SERVICIOS', '1', 1),
(1, 'Tipo Utilidad', 'A', 1),
(22, 'TIPO_CHUZO', 'STD', 1),
(50, 'tipo_fac_default', 'COM', 1),
(37, 'tipo_imp_comprobantes', 'COM', 1),
(42, 'tipo_impresion', 'COM', 1),
(25, 'tipo_redondeo', 'decimal', 1),
(70, 'TRASLADOS', '0', 1),
(81, 'UN_BAN_CLI2', '1', 1),
(4, 'url_LOGO_A', 'Imagenes/logoApp.png', 1),
(5, 'url_LOGO_B', 'Imagenes/logoApp.png', 1),
(48, 'usar_bsf', '0', 1),
(30, 'usar_costo_dcto', '1', 1),
(53, 'usar_costo_remi', '0', 1),
(18, 'usar_iva', '1', 1),
(49, 'usar_posFac', '0', 1),
(46, 'usar_remision', '0', 1),
(21, 'usar_ubica', '0', 1),
(7, 'Variable_size_imp_carta', '1', 1),
(84, 'VEHICULOS', '1', 1),
(32, 'vende_sin_cant', '0', 1),
(51, 'ventas_credito', '0', 1),
(44, 'ver_pvp_sin_iva', '0', 1),
(20, 'ver_util', '1', 1),
(52, 'vista_remi', 'A', 1),
(8, 'x', '130px', 1),
(10, 'X', '250px', 1),
(6, 'X_fac', '27.9cm', 1),
(11, 'Y', '100px', 1),
(9, 'y', '50px', 1);



CREATE TABLE IF NOT EXISTS `art_fac_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_fac_com` varchar(30) NOT NULL,
  `cant` decimal(24,4) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `dcto` decimal(10,2) NOT NULL,
  `iva` tinyint(4) NOT NULL,
  `uti` decimal(10,2) NOT NULL,
  `pvp` decimal(24,2) NOT NULL,
  `pvp_cre` decimal(24,2) NOT NULL,
  `pvp_may` decimal(24,2) NOT NULL,
  `tot` decimal(24,2) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `nit_pro` varchar(20) NOT NULL,
  `tipo_dcto` varchar(15) NOT NULL,
  `dcto2` decimal(10,2) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(5) NOT NULL,
  `fabricante` varchar(30) NOT NULL,
  `clase` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL DEFAULT '1',
  `unidades_fraccion` bigint(20) NOT NULL DEFAULT '1',
  `ubicacion` varchar(30) NOT NULL,
  `fe_crea` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_src` varchar(30) NOT NULL,
  `aplica_vehi` varchar(100) NOT NULL,
  `des_full` varchar(100) NOT NULL,
  `serial_art` varchar(50) NOT NULL,
  `cert_importacion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `num_fac_com` (`num_fac_com`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `cod_su` (`cod_su`),
  KEY `nit_pro` (`nit_pro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `ajustes` (
  `num_ajuste` bigint(20) NOT NULL,
  `fecha` datetime NOT NULL,
  `anulado` varchar(30) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `fecha_anula` datetime NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT '''ABIERTA''',
  `nom_usu` varchar(50) NOT NULL,
  `id_usu` varchar(30) NOT NULL,
  `usu` varchar(30) NOT NULL,
  KEY `nit` (`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ajuste_cajas` (
  `fecha` date NOT NULL,
  `base_caja` decimal(24,2) NOT NULL,
  `valor_base` decimal(24,2) NOT NULL,
  `valor_entrega` decimal(24,2) NOT NULL,
  `valor_diferencia` decimal(22,2) NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fecha`,`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `art_ajuste` (
  `num_ajuste` bigint(20) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `iva` int(11) NOT NULL,
  `cant` decimal(24,4) NOT NULL,
  `precio` decimal(24,4) NOT NULL,
  `util` int(11) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `costo` decimal(24,4) NOT NULL,
  `motivo` varchar(100) NOT NULL,
  `cant_saldo` decimal(24,4) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `unidades_saldo` bigint(20) NOT NULL,
  `estado2` varchar(20) NOT NULL,
  PRIMARY KEY (`num_ajuste`,`ref`,`cod_barras`,`fecha_vencimiento`),
  KEY `num_fac_ven` (`num_ajuste`),
  KEY `nit` (`cod_su`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `art_devolucion_venta` (
  `num_fac_ven` bigint(20) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `imei` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `iva` int(11) NOT NULL,
  `cant` decimal(20,2) NOT NULL,
  `cant_dev` decimal(20,2) NOT NULL,
  `precio` double(24,2) NOT NULL,
  `sub_tot` double(24,2) NOT NULL,
  `dcto` double(24,2) NOT NULL,
  `nit` tinyint(4) NOT NULL,
  `devolucion` varchar(10) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(5) NOT NULL,
  `fabricante` varchar(30) NOT NULL,
  `clase` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `uni_dev` decimal(20,2) NOT NULL,
  `orden_in` int(11) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`ref`,`des`,`cant`,`precio`,`cod_barras`,`fecha_vencimiento`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `nit` (`nit`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `prefijo` (`prefijo`),
  KEY `serial` (`serial`),
  KEY `imei` (`imei`),
  KEY `des` (`des`),
  KEY `cod_barras` (`cod_barras`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `art_fac_dev` (
  `num_fac_com` varchar(30) NOT NULL,
  `cant` decimal(24,4) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `des` varchar(50) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `dcto` tinyint(4) NOT NULL,
  `iva` tinyint(4) NOT NULL,
  `uti` decimal(10,2) NOT NULL,
  `pvp` decimal(24,2) NOT NULL,
  `tot` decimal(24,2) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `nit_pro` varchar(20) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(30) NOT NULL,
  `talla` varchar(20) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `serial_dev` bigint(20) NOT NULL,
  PRIMARY KEY (`num_fac_com`,`ref`,`cod_su`,`nit_pro`,`serial_dev`),
  KEY `num_fac_com` (`num_fac_com`),
  KEY `ref` (`ref`),
  KEY `cod_su` (`cod_su`),
  KEY `nit_pro` (`nit_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `art_fac_remi` (
  `serial_art_fac` int(11) NOT NULL AUTO_INCREMENT,
  `serial_fac` bigint(20) NOT NULL,
  `num_fac_ven` bigint(20) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `imei` varchar(30) NOT NULL,
  `des` varchar(50) NOT NULL,
  `iva` int(11) NOT NULL,
  `cant` decimal(20,2) NOT NULL,
  `cant_dev` decimal(20,2) NOT NULL,
  `precio` double(24,2) NOT NULL,
  `sub_tot` double(24,2) NOT NULL,
  `dcto` double(24,2) NOT NULL,
  `nit` tinyint(4) NOT NULL,
  `devolucion` varchar(10) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(5) NOT NULL,
  `fabricante` varchar(30) NOT NULL,
  `clase` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `uni_dev` decimal(20,2) NOT NULL,
  `orden_in` int(11) NOT NULL,
  `cod_garantia` varchar(50) NOT NULL,
  PRIMARY KEY (`serial_art_fac`),
  UNIQUE KEY `serial_art_fac` (`serial_art_fac`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `nit` (`nit`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `prefijo` (`prefijo`),
  KEY `serial` (`serial`),
  KEY `imei` (`imei`),
  KEY `des` (`des`),
  KEY `cod_barras` (`cod_barras`),
  KEY `cod_garantia` (`cod_garantia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `art_fac_ven` (
  `serial_art_fac` int(11) NOT NULL AUTO_INCREMENT,
  `serial_fac` bigint(20) NOT NULL,
  `num_fac_ven` bigint(20) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `imei` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `iva` int(11) NOT NULL,
  `cant` decimal(20,2) NOT NULL,
  `cant_dev` decimal(20,2) NOT NULL,
  `precio` double(24,2) NOT NULL,
  `sub_tot` double(24,2) NOT NULL,
  `dcto` double(24,2) NOT NULL,
  `nit` tinyint(4) NOT NULL,
  `devolucion` varchar(10) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(5) NOT NULL,
  `fabricante` varchar(30) NOT NULL,
  `clase` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `uni_dev` decimal(20,2) NOT NULL,
  `orden_in` int(11) NOT NULL,
  `cod_garantia` varchar(50) NOT NULL,
  PRIMARY KEY (`serial_art_fac`),
  UNIQUE KEY `serial_art_fac` (`serial_art_fac`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `nit` (`nit`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `prefijo` (`prefijo`),
  KEY `serial` (`serial`),
  KEY `imei` (`imei`),
  KEY `des` (`des`),
  KEY `cod_garantia` (`cod_garantia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `bancos` (
  `id_banco` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_banco` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_banco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

 

INSERT INTO `bancos` (`id_banco`, `nombre_banco`) VALUES
(1, 'Bancolombia'),
(2, 'Banco de Bogota'),
(3, 'BBVA');


CREATE TABLE IF NOT EXISTS `bancos_cuentas` (
  `id_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `id_banco` int(11) NOT NULL,
  `tipo_cuenta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `num_cuenta` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `saldo_cuenta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_cuenta`),
  KEY `id_banco` (`id_banco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `cartera_mult_pago` (
  `num_comp` int(11) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `pre` varchar(10) NOT NULL,
  `id_cli` varchar(30) NOT NULL,
  `abono` decimal(24,2) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `cod_su` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`num_comp`,`num_fac`,`pre`,`id_cli`),
  KEY `id_cli` (`id_cli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `clases` (
  `id_clas` bigint(20) NOT NULL AUTO_INCREMENT,
  `des_clas` varchar(50) NOT NULL,
  PRIMARY KEY (`id_clas`),
  UNIQUE KEY `des_clas` (`des_clas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `colores` (
  `id_color` bigint(20) NOT NULL AUTO_INCREMENT,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id_color`),
  UNIQUE KEY `color` (`color`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `comprobante_ingreso` (
  `num_com` bigint(20) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_cuota` date NOT NULL,
  `valor` decimal(24,2) NOT NULL,
  `anulado` varchar(30) NOT NULL,
  `cajero` varchar(50) NOT NULL,
  `concepto` text NOT NULL,
  `pre` varchar(5) NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cod_caja` int(11) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `id_cli` varchar(30) NOT NULL,
  `forma_pago` varchar(30) NOT NULL DEFAULT 'Contado',
  `tipo_operacion` varchar(20) NOT NULL DEFAULT 'Individual',
  `fe_ini` date NOT NULL,
  `fe_fin` date NOT NULL,
  `r_fte` decimal(24,2) NOT NULL,
  `per_r_fte` decimal(5,2) NOT NULL,
  `r_ica` decimal(24,2) NOT NULL,
  `per_r_ica` decimal(5,2) NOT NULL,
  PRIMARY KEY (`num_com`,`num_fac`,`cod_su`,`fecha`,`valor`,`cajero`,`pre`,`cod_caja`),
  KEY `num_fac` (`num_fac`),
  KEY `cod_su` (`cod_su`),
  KEY `anulado` (`anulado`),
  KEY `num_com` (`num_com`),
  KEY `fecha_cuota` (`fecha_cuota`),
  KEY `id_cli` (`id_cli`),
  KEY `forma_pago` (`forma_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `comp_anti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comp` bigint(20) NOT NULL,
  `num_exp` bigint(20) NOT NULL,
  `num_com` bigint(20) NOT NULL,
  `valor` decimal(24,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cajero` varchar(50) NOT NULL,
  `concepto` text NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tipo_pago` varchar(15) NOT NULL DEFAULT 'Contado',
  `tipo_comprobante` varchar(15) NOT NULL DEFAULT 'Anticipo',
  `cod_caja` int(11) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  PRIMARY KEY (`fecha`,`valor`,`cod_su`),
  UNIQUE KEY `id` (`id`),
  KEY `num_exp` (`num_exp`),
  KEY `num_com` (`num_com`),
  KEY `estado` (`estado`),
  KEY `cajero` (`cajero`),
  KEY `fecha_anula` (`fecha_anula`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `comp_egreso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_com` bigint(20) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor` decimal(24,2) NOT NULL,
  `anulado` varchar(10) NOT NULL,
  `cajero` varchar(50) NOT NULL,
  `concepto` text NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tipo_gasto` varchar(50) NOT NULL,
  `banco` varchar(30) NOT NULL,
  `num_cheque` varchar(30) NOT NULL,
  `beneficiario` varchar(50) NOT NULL,
  `id_beneficiario` varchar(30) NOT NULL,
  `tipo_pago` varchar(30) NOT NULL,
  `cod_caja` int(11) NOT NULL,
  `serial_fac_com` varchar(30) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `r_fte` decimal(10,2) NOT NULL,
  `r_ica` decimal(10,2) NOT NULL,
  `r_iva` decimal(10,2) NOT NULL,
  `id_cuenta_trans` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `cod_su` (`cod_su`),
  KEY `anulado` (`anulado`),
  KEY `num_com` (`num_com`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `cuentas_dinero` (
  `id_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `nom_cta` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_cta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `clase_cta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cod_cta` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `saldo_cta` decimal(24,2) NOT NULL,
  `fechaI_extractos` date NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_cuenta`),
  UNIQUE KEY `cod_cta` (`cod_cta`),
  KEY `tipo_cta` (`tipo_cta`),
  KEY `clase_cta` (`clase_cta`),
  KEY `fechaI_extractos` (`fechaI_extractos`),
  KEY `cod_su` (`cod_su`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
INSERT INTO `cuentas_dinero` (`id_cuenta`, `nom_cta`, `tipo_cta`, `clase_cta`, `cod_cta`, `saldo_cta`, `fechaI_extractos`, `cod_su`) VALUES
(1, 'Caja General', 'Ingresos Ventas', 'Contado', '0001', '350000.00', '2016-04-29', 1),
(2, 'BBVA', 'Banco', 'AHORROS', '064-266562', '1691900.00', '2016-04-29', 1),
(3, 'CAJA MENOR', 'Banco', 'CORRIENTE', '102030', '0.00', '2016-05-28', 1);

CREATE TABLE IF NOT EXISTS `cuentas_mvtos` (
  `id_mov` int(11) NOT NULL AUTO_INCREMENT,
  `id_cuenta` int(11) NOT NULL,
  `tipo_mov` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `concepto_mov` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clase_mov` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `fecha_mov` datetime NOT NULL,
  `saldo` decimal(24,2) NOT NULL,
  PRIMARY KEY (`id_mov`),
  KEY `clase_mov` (`clase_mov`),
  KEY `id_cuenta` (`id_cuenta`),
  KEY `tipo_mov` (`tipo_mov`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `cuentas_mvtos` (`id_mov`, `id_cuenta`, `tipo_mov`, `concepto_mov`, `clase_mov`, `monto`, `fecha_mov`, `saldo`) VALUES
(1, 1, 'SALDO INICIO/CORTE', 'Saldo Inicial de Cuenta', 'Saldo Corte', '1245000.00', '2016-04-29 00:00:00', '1245000.00');

CREATE TABLE IF NOT EXISTS `exp_anticipo` (
  `id_anti` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_exp` bigint(20) NOT NULL,
  `id_cli` varchar(30) NOT NULL,
  `nom_cli` varchar(50) NOT NULL,
  `tel_cli` varchar(20) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `des` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cod_su` tinyint(4) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `cajero` varchar(50) NOT NULL,
  `usu` varchar(30) NOT NULL,
  `tot` decimal(24,2) NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `prefijo` varchar(5) NOT NULL,
  `tot_pa` bigint(20) NOT NULL,
  PRIMARY KEY (`id_anti`),
  KEY `num_exp` (`num_exp`),
  KEY `id_cli` (`id_cli`),
  KEY `nom_cli` (`nom_cli`),
  KEY `num_fac` (`num_fac`),
  KEY `prefijo` (`prefijo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `fabricantes` (
  `id_fab` bigint(20) NOT NULL AUTO_INCREMENT,
  `fabricante` varchar(50) NOT NULL,
  PRIMARY KEY (`id_fab`),
  UNIQUE KEY `fabricante` (`fabricante`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `fac_com` (
  `nom_pro` varchar(100) NOT NULL,
  `nit_pro` varchar(20) NOT NULL,
  `ciudad` varchar(30) NOT NULL,
  `dir` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `fax` varchar(30) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `num_fac_com` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `cod_su` int(11) NOT NULL,
  `subtot` decimal(24,2) NOT NULL,
  `descuento` decimal(24,2) NOT NULL,
  `flete` decimal(24,2) NOT NULL,
  `iva` decimal(24,2) NOT NULL,
  `tot` decimal(24,2) NOT NULL,
  `val_letras` varchar(100) NOT NULL,
  `fecha_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_crea` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `serial_fac_com` bigint(20) NOT NULL,
  `tipo_fac` varchar(30) NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'ABIERTA',
  `dcto2` decimal(10,2) NOT NULL,
  `pago` varchar(20) NOT NULL DEFAULT 'PENDIENTE',
  `fecha_pago` date NOT NULL DEFAULT '0000-00-00',
  `id_banco` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `kardex` int(11) NOT NULL DEFAULT '1',
  `r_fte` decimal(10,2) NOT NULL,
  `r_ica` decimal(10,2) NOT NULL,
  `r_iva` decimal(10,2) NOT NULL,
  `feVen` date NOT NULL,
  `sede_origen` int(11) NOT NULL DEFAULT '1',
  `sede_destino` int(11) NOT NULL,
  `perflete` decimal(5,2) NOT NULL,
  `calc_dcto` varchar(5) NOT NULL DEFAULT 'per',
  `serial_tras` bigint(20) NOT NULL,
  `calc_pvp` varchar(20) NOT NULL DEFAULT 'CALCULAR',
  PRIMARY KEY (`num_fac_com`,`nit_pro`,`cod_su`),
  KEY `fk_fac_com` (`cod_su`),
  KEY `tipo_fac` (`tipo_fac`),
  KEY `kardex` (`kardex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `fac_dev` (
  `nom_pro` varchar(30) NOT NULL,
  `nit_pro` varchar(20) NOT NULL,
  `ciudad` varchar(30) NOT NULL,
  `dir` varchar(100) NOT NULL,
  `tel` varchar(30) NOT NULL,
  `fax` varchar(30) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `num_fac_com` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  `cod_su` int(11) NOT NULL,
  `subtot` decimal(24,2) NOT NULL,
  `descuento` decimal(24,2) NOT NULL,
  `flete` decimal(24,2) NOT NULL,
  `iva` decimal(24,2) NOT NULL,
  `tot` decimal(24,2) NOT NULL,
  `val_letras` varchar(100) NOT NULL,
  `fecha_mod` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_crea` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `serial_fac_dev` bigint(20) NOT NULL,
  `nota_dev` text NOT NULL,
  `kardex` int(11) NOT NULL DEFAULT '1',
  `anulado` varchar(15) NOT NULL,
  `fecha_anula` date NOT NULL,
  PRIMARY KEY (`nit_pro`,`num_fac_com`,`cod_su`,`serial_fac_dev`),
  KEY `kardex` (`kardex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `presentacion` (
  `presentacion` varchar(30) NOT NULL,
  `pre_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pre_id`),
  UNIQUE KEY `presentacion` (`presentacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `tallas` (
  `id_talla` bigint(20) NOT NULL AUTO_INCREMENT,
  `talla` varchar(10) NOT NULL,
  PRIMARY KEY (`id_talla`),
  UNIQUE KEY `talla` (`talla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
























CREATE TABLE IF NOT EXISTS `fac_ven_cambios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_fac` bigint(20) NOT NULL,
  `pre` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `nf_o` bigint(20) NOT NULL,
  `pre_o` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `id_fac` int(11) NOT NULL,
  `id_usu` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom_usu` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cod_su` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE  `fac_ven_cambios` ADD  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;


 

CREATE TABLE IF NOT EXISTS `x_material_query` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `seccion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last` date NOT NULL,
  `reset_days` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `rotacion_inv` (
  `ref` varchar(30) NOT NULL COMMENT 'Ref.',
  `cod_bar` varchar(30) NOT NULL COMMENT 'Cod.',
  `des` varchar(200) NOT NULL COMMENT 'Nombre',
  `clase` varchar(30) NOT NULL COMMENT 'Clase',
  `fab` varchar(30) NOT NULL COMMENT 'Marca/Fabricante',
  `costo` decimal(24,2) NOT NULL COMMENT 'Costo',
  `pvp` decimal(24,2) NOT NULL COMMENT 'PVP',
  `iva` tinyint(4) NOT NULL COMMENT 'IVA',
  `cod_su` int(11) NOT NULL COMMENT 'cod_su',
  `proveedor` varchar(30) NOT NULL COMMENT 'Proveedor',
  `color` varchar(30) NOT NULL COMMENT 'Color',
  `talla` varchar(10) NOT NULL COMMENT 'Talla',
  `exist` decimal(24,0) NOT NULL COMMENT 'Existencia',
  `min` decimal(24,0) NOT NULL COMMENT 'Min.',
  `max` decimal(24,0) NOT NULL COMMENT 'Max.',
  `CP` decimal(24,0) NOT NULL COMMENT 'Cant. Pedido',
  `Pp` decimal(24,0) NOT NULL COMMENT 'Punto Pedido',
  `tot_ventas` decimal(24,0) NOT NULL COMMENT 'Tot. Vendidos',
  `fecha_i` date NOT NULL COMMENT 'Fecha Inicial',
  `fecha_f` date NOT NULL COMMENT 'Fecha Final',
  PRIMARY KEY (`ref`,`cod_bar`,`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `seriales_inv` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `min` bigint(20) NOT NULL DEFAULT '1',
  `max` bigint(20) NOT NULL DEFAULT '1000',
  `current` bigint(20) NOT NULL DEFAULT '1',
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;


CREATE TABLE IF NOT EXISTS `orden_garantia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cli` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `cod_garantia` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_pro` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_venta` date NOT NULL,
  `id_pro_prestamo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `num_gui` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `transportadora` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_envio` date NOT NULL,
  `fecha` datetime NOT NULL,
  `placa_vehi` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



INSERT INTO  `secciones` (
`id_secc` ,
`des_secc`
)
VALUES (
'',  'Arqueos e Informes'
);




ALTER TABLE  `fac_com` CHANGE  `fecha_mod`  `fecha_mod` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;




ALTER TABLE  `art_ajuste` ADD  `cant_saldo` DECIMAL( 24, 4 ) NOT NULL;




alter table `art_fac_com` drop primary key, add primary key(num_fac_com,nit_pro,cod_barras,ref)

ALTER TABLE  `art_fac_com` ADD  `fabricante` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `art_fac_com` ADD  `clase` VARCHAR( 30 ) NOT NULL;






INSERT INTO `secciones` (`id_secc`, `des_secc`) VALUES ('descuento_fac', 'Descuentos en Facturacion');

INSERT INTO  `secciones` (
`id_secc` ,
`des_secc`
)
VALUES (
'crea_recibo_caja',  'Crear Recibos Caja (Cartera)'
);


ALTER TABLE  `art_fac_dev` ADD  `cod_barras` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `art_ajuste` ADD  `cod_barras` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `art_tras` ADD  `cod_barras` VARCHAR( 30 ) NOT NULL;

ALTER TABLE  `art_fac_com` ADD  `presentacion` VARCHAR( 30 ) NOT NULL;

ALTER TABLE  `productos` ADD  `presentacion` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `inv_inter` ADD  `presentacion` VARCHAR( 30 ) NOT NULL;

INSERT INTO `secciones` (
`id_secc` ,
`des_secc`
)
VALUES (
'crear_anticipo',  'Crear Anticipos/Abonos'
);
CREATE TABLE IF NOT EXISTS `exp_anticipo` (
  `id_anti` bigint(20) NOT NULL AUTO_INCREMENT,
  `num_exp` bigint(20) NOT NULL,
  `id_cli` varchar(30) NOT NULL,
  `nom_cli` varchar(50) NOT NULL,
  `tel_cli` varchar(20) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `des` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cod_su` tinyint(4) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `cajero` varchar(50) NOT NULL,
  `usu` varchar(30) NOT NULL,
  `tot` decimal(10,2) NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `prefijo` varchar(5) NOT NULL,
  PRIMARY KEY (`id_anti`),
  KEY `num_exp` (`num_exp`),
  KEY `id_cli` (`id_cli`),
  KEY `nom_cli` (`nom_cli`),
  KEY `num_fac` (`num_fac`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;
CREATE TABLE IF NOT EXISTS `comp_anti` (
  `id_comp` bigint(20) NOT NULL,
  `num_exp` bigint(20) NOT NULL,
  `num_com` bigint(20) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cajero` varchar(50) NOT NULL,
  `concepto` text NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha_anula` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`fecha`,`valor`,`cod_su`),
  KEY `num_exp` (`num_exp`),
  KEY `num_com` (`num_com`),
  KEY `estado` (`estado`),
  KEY `cajero` (`cajero`),
  KEY `fecha_anula` (`fecha_anula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `secciones` (`id_secc`, `des_secc`) VALUES ('anula_comp_egreso', 'Anular Comprobante de Egreso'), ('fac_com_anula', 'Anular Factura de Compra');


ALTER TABLE  `art_fac_com` ADD  `fecha_vencimiento` DATE NOT NULL;
ALTER TABLE  `art_fac_dev` ADD  `color` VARCHAR( 30 ) NOT NULL ,
ADD  `talla` VARCHAR( 20 ) NOT NULL ,
ADD  `presentacion` VARCHAR( 30 ) NOT NULL ,
ADD  `fecha_vencimiento` DATE NOT NULL;


INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comp_egreso',  '1',  '10000',  '1'
);
ALTER TABLE  `fac_com` ADD  `pago` VARCHAR( 20 ) NOT NULL DEFAULT  'PENDIENTE',
ADD  `fecha_pago` DATE NOT NULL DEFAULT  '0000-00-00';





INSERT INTO `secciones` (`id_secc`, `des_secc`) VALUES ('dcto_per', 'Descuento Fac. Venta (porcentaje)');
ALTER TABLE  `secciones` ADD  `modulo` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `secciones` ADD  `habilita` INT NOT NULL DEFAULT  '1';

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'ref',  '1',  '100000',  '1'
);


ALTER TABLE `productos` DROP PRIMARY KEY;

ALTER TABLE productos DROP column serial_pro ;

ALTER TABLE productos
ADD COLUMN serial_pro INT NOT NULL AUTO_INCREMENT ,
ADD UNIQUE INDEX serial_pro_UNIQUE (serial_pro ASC);


INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'anular_comp_ingreso',  'Anular Comprobante de Ingreso',  'Cartera Clientes',  '1'
), (
'anular_comp_anticipo',  'Anular Comprobante Anticipo',  'Ventas',  '1'
);


ALTER TABLE  `comprobante_ingreso` CHANGE  `anulado`  `anulado` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `comprobante_ingreso` ADD  `fecha_anula` TIMESTAMP NOT NULL ;









ALTER TABLE  `comp_egreso` ADD  `cod_caja` INT NOT NULL;
ALTER TABLE  `comp_anti` ADD  `cod_caja` INT NOT NULL;
ALTER TABLE  `comprobante_ingreso` ADD  `cod_caja` INT NOT NULL;


ALTER TABLE  `exp_anticipo` ADD  `tot_pa` BIGINT NOT NULL;







INSERT INTO `secciones` (`id_secc`, `des_secc`, `modulo`, `habilita`) VALUES ('crear_nota_credito', 'Crear Notas Credito (Devoluciones de Compras)', 'Compras', '1'), ('ver_nota_credito', 'Ver Crear Notas Credito', 'Compras', '1');





INSERT INTO `secciones` (`id_secc`, `des_secc`, `modulo`, `habilita`) VALUES ('ver_tot_compras', 'Ver costos Totales en Compras', 'Compras', '1');


ALTER TABLE  `inv_inter` ADD  `certificado_importacion` VARCHAR( 255 ) NOT NULL;


ALTER TABLE  `inv_inter` ADD  `pvp_credito` DECIMAL( 10, 2 ) NOT NULL;




INSERT INTO `seriales` (`id_serial`, `seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES (NULL, 'resol_papel', '1', '1000', '1');













ALTER TABLE  `comp_egreso` ADD  `num_fac_com` VARCHAR( 30 ) NOT NULL ,
ADD  `id_banco` INT NOT NULL ,
ADD  `id_cuenta` INT NOT NULL;

ALTER TABLE  `comp_egreso` CHANGE  `num_fac_com`  `serial_fac_com` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;









ALTER TABLE  `comp_egreso` ADD  `r_fte` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `r_ica` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `r_iva` DECIMAL( 10, 2 ) NOT NULL;



ALTER TABLE  `comprobante_ingreso` ADD  `id_banco` INT NOT NULL ,ADD  `id_cuenta` INT NOT NULL;

ALTER TABLE  `comp_anti` ADD  `id_banco` INT NOT NULL ,ADD  `id_cuenta` INT NOT NULL;

ALTER TABLE  `fac_com` ADD  `id_banco` INT NOT NULL ,
ADD  `id_cuenta` INT NOT NULL ,
ADD  `kardex` INT NOT NULL DEFAULT  '1';
ALTER TABLE  `fac_com` ADD INDEX (  `kardex` );

ALTER TABLE  `fac_dev` ADD  `kardex` INT NOT NULL DEFAULT  '1';ALTER TABLE  `fac_dev` ADD INDEX (  `kardex` );



ALTER TABLE  `fac_com` ADD  `r_fte` DECIMAL( 10 ) NOT NULL ,
ADD  `r_ica` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `r_iva` DECIMAL( 10, 2 ) NOT NULL;


ALTER TABLE  `fac_com` ADD  `feVen` DATE NOT NULL ;







ALTER TABLE `comprobante_ingreso`
  DROP PRIMARY KEY,
   ADD PRIMARY KEY(
     `num_com`,
     `num_fac`,
     `cod_su`,
     `fecha`,
     `valor`,
     `cajero`,
     `pre`,
     `cod_caja`);
ALTER TABLE  `fac_com` CHANGE  `r_fte`  `r_fte` DECIMAL( 10, 2 ) NOT NULL;


INSERT INTO `seriales` (`id_serial`, `seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES (NULL, 'remision', '1', '100000', '1'), (NULL, 'remision', '1', '100000', '2');

INSERT INTO `seriales` (`id_serial`, `seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES (NULL, 'remision_com', '1', '100000', '1'), (NULL, 'remision_com', '1', '100000', '2');






INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'remi_lista',  'Lista Remisiones',  'Ventas',  '1'
);

INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'remi_crea',  'Crear Remisiones',  'Ventas',  '1'
);
INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'inventario_on_off',  'Modificar Cantidades y Kardex de Productos',  'Manejo de Inventario',  '1'
);



INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'fac_mod',  'MODIFICAR Facturas de Venta',  'Ventas',  '1'
);







UPDATE `fac_venta` SET anulado='CERRADA' WHERE anulado!='ANULADO' AND num_fac_ven NOT IN (SELECT num_fac_ven FROM fac_venta WHERE anulado='CERRADA');

ALTER TABLE  `fac_dev` CHANGE  `num_fac_com`  `num_fac_com` VARCHAR( 30 ) NOT NULL;

ALTER TABLE  `fac_com` ADD  `sede_origen` INT NOT NULL DEFAULT  '1',
ADD  `sede_destino` INT NOT NULL;






ALTER TABLE  `inv_inter` ADD  `marcas` VARCHAR( 100 ) NOT NULL ,
ADD INDEX (  `marcas` );





ALTER TABLE  `fac_remi` ADD  `nota` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `fac_remi` ADD  `fecha_recibe` DATE NOT NULL;



ALTER TABLE  `productos` ADD  `id_sub_clase` VARCHAR( 30 ) NOT NULL;





ALTER TABLE  `fac_com` ADD  `serial_tras` BIGINT(20) NOT NULL;






ALTER TABLE  `fac_com` ADD  `calc_dcto` VARCHAR( 5 ) NOT NULL DEFAULT  'per';





ALTER TABLE  `inv_inter` ADD  `envase` INT( 2 ) NOT NULL;



ALTER TABLE  `fac_dev` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `nit_pro` ,  `num_fac_com` ,  `cod_su` ,  `serial_fac_dev` ) ;

ALTER TABLE  `art_fac_dev` ADD  `serial_dev` BIGINT NOT NULL;

UPDATE `art_fac_dev` a INNER JOIN (select serial_fac_dev,num_fac_com,nit_pro,cod_su from fac_dev) b ON a.num_fac_com=b.num_fac_com  SET a.serial_dev=b.serial_fac_dev  WHERE a.cod_su=b.cod_su AND a.nit_pro=b.nit_pro;

ALTER TABLE  `art_fac_dev` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `num_fac_com` ,  `ref` ,  `cod_su` ,  `nit_pro` ,  `serial_dev` ) ;



ALTER TABLE  `art_fac_com` ADD  `ubicacion` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `inv_inter` ADD  `ubicacion` VARCHAR( 30 ) NOT NULL;










ALTER TABLE  `fac_com` ADD  `perflete` DECIMAL( 5, 2 ) NOT NULL;



ALTER TABLE  `comprobante_ingreso` ADD  `id_cli` VARCHAR( 30 ) NOT NULL;
UPDATE `comprobante_ingreso` a INNER JOIN (SELECT * FROM fac_venta ) b ON a.num_fac=b.num_fac_ven SET a.id_cli=b.id_cli WHERE a.pre=b.prefijo;



CREATE TABLE IF NOT EXISTS `cartera_mult_pago` (
  `num_comp` int(11) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `pre` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `id_cli` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `abono` decimal(10,2) NOT NULL,
  `estado` varchar(15) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`num_comp`,`num_fac`,`pre`,`id_cli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `cartera_mult_pago`(`num_comp`,`num_fac`,`pre`,`id_cli`,`abono`,`estado`) SELECT `num_com`,`num_fac`,`pre`,`id_cli`,`valor`,`anulado` FROM `comprobante_ingreso` WHERE `num_fac`!=0 AND `pre`!='' ;


CREATE TABLE IF NOT EXISTS `vehiculo` (
  `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT,
  `placa` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `modelo` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `cc` decimal(10,1) NOT NULL,
  `color` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `km` int(11) NOT NULL,
  `cod_su` int(4) NOT NULL,
  `id_propietario` varchar(30) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `placa` (`placa`),
  KEY `modelo` (`modelo`,`cod_su`),
  KEY `id_propietario` (`id_propietario`),
  KEY `color` (`color`),
  KEY `cc` (`cc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=3 ;
ALTER TABLE  `productos` ADD  `nit_proveedor` VARCHAR( 30 ) NOT NULL;
UPDATE `productos` a INNER JOIN (SELECT nit_pro,ref FROM art_fac_com WHERE nit_pro!='00000000-0') b ON a.id_pro=b.ref SET a.nit_proveedor=b.nit_pro WHERE 1;
CREATE TABLE IF NOT EXISTS `servicios` (
  `id_serv` int(11) NOT NULL AUTO_INCREMENT,
  `servicio` varchar(30) COLLATE utf8_general_ci NOT NULL,
  `des_serv` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `km_revisa` decimal(10,1) NOT NULL,
  `cod_su` int(4) NOT NULL,
  `cod_serv` bigint(20) NOT NULL,
  PRIMARY KEY (`id_serv`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=3 ;
CREATE TABLE IF NOT EXISTS `serv_fac_remi` (
  `num_fac_ven` bigint(20) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `id_serv` int(11) NOT NULL,
  `serv` varchar(30) NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `cod_serv` bigint(20) NOT NULL,
  `cod_su` int(4) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`prefijo`,`id_serv`,`cod_su`),
  KEY `serv` (`serv`),
  KEY `cod_serv` (`cod_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `serv_fac_ven` (
  `num_fac_ven` bigint(20) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `id_serv` int(11) NOT NULL,
  `serv` varchar(30) NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `cod_serv` bigint(20) NOT NULL,
  `cod_su` int(4) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`prefijo`,`id_serv`,`cod_su`),
  KEY `serv` (`serv`),
  KEY `cod_serv` (`cod_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




ALTER TABLE  `serv_fac_ven` ADD  `id_tec` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `serv_fac_remi` ADD  `id_tec` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `serv_fac_ven` ADD  `nota` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `serv_fac_remi` ADD  `nota` VARCHAR( 255 ) NOT NULL;


ALTER TABLE  `productos` ADD  `nit_proveedor` VARCHAR( 30 ) NOT NULL;
UPDATE `productos` a INNER JOIN (SELECT nit_pro,ref FROM art_fac_com WHERE nit_pro!='00000000-0') b ON a.id_pro=b.ref SET a.nit_proveedor=b.nit_pro WHERE 1;






ALTER TABLE  `art_fac_com` ADD  `id` BIGINT NOT NULL;
ALTER TABLE `art_fac_com` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
ALTER TABLE  `art_fac_com` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `id` ) ;


ALTER TABLE  `cartera_mult_pago` CHANGE  `pre`  `pre` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `cartera_mult_pago` CHANGE  `id_cli`  `id_cli` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `cartera_mult_pago` CHANGE  `estado`  `estado` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `cartera_mult_pago` CHANGE  `abono`  `abono` DECIMAL( 24, 2 ) NOT NULL;
ALTER TABLE  `comprobante_ingreso` CHANGE  `valor`  `valor` DECIMAL( 24, 2 ) NOT NULL;
ALTER TABLE  `comp_anti` CHANGE  `valor`  `valor` DECIMAL( 24, 2 ) NOT NULL;
ALTER TABLE  `comp_egreso` CHANGE  `valor`  `valor` DECIMAL( 24, 2 ) NOT NULL;
ALTER TABLE  `exp_anticipo` CHANGE  `tot`  `tot` DECIMAL( 24, 2 ) NOT NULL;
INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'mod_comp_egreso',  'Modificar Comprobante de Egreso',  'Egresos',  '1'
);


INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'cotizacion',  'Cotizaciones',  'Ventas',  '1'
);



ALTER TABLE  `comp_anti` ADD  `id` BIGINT NOT NULL;
ALTER TABLE `comp_anti` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;



 
ALTER TABLE  `art_fac_com` ADD INDEX (  `cod_barras` );


ALTER TABLE  `comprobante_ingreso` ADD  `forma_pago` VARCHAR( 30 ) NOT NULL DEFAULT  'Contado',ADD INDEX (  `forma_pago` );

ALTER TABLE  `exp_anticipo` ADD INDEX (  `prefijo` );

ALTER TABLE  `art_ajuste` ADD  `estado2` VARCHAR( 20 ) NOT NULL;


ALTER TABLE  `art_tras` DROP FOREIGN KEY  `art_tras_ibfk_2` ;

INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'vende_credito',  'Vender a Credito',  'Ventas',  '1'
);


ALTER TABLE  `art_ajuste` CHANGE  `des`  `des` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;





ALTER TABLE  `comp_egreso` ADD INDEX (  `tipo_pago` );
ALTER TABLE  `comp_egreso` ADD INDEX (  `cajero` );
ALTER TABLE  `comp_egreso` ADD INDEX (  `fecha_anula` );

ALTER TABLE  `comp_anti` ADD INDEX (  `tipo_pago` );
ALTER TABLE  `comp_anti` ADD INDEX (  `tipo_comprobante` );

ALTER TABLE  `comp_egreso` ADD INDEX (  `serial_fac_com` );
ALTER TABLE  `comp_egreso` ADD INDEX (  `cod_caja` );


ALTER TABLE comp_anti DROP PRIMARY KEY, ADD PRIMARY KEY(id);
ALTER TABLE `comp_anti` DROP `id_comp`;

CREATE TABLE IF NOT EXISTS `ajuste_cajas` (
  `fecha` date NOT NULL,
  `base_caja` decimal(24,2) NOT NULL,
  `valor_base` decimal(24,2) NOT NULL,
  `valor_entrega` decimal(24,2) NOT NULL,
  `valor_diferencia` decimal(22,2) NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fecha`,`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






ALTER TABLE  `cartera_mult_pago` ADD  `cod_su` INT( 4 ) NOT NULL DEFAULT  '1';
ALTER TABLE  `fac_com` CHANGE  `nom_pro`  `nom_pro` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `provedores` CHANGE  `nom_pro`  `nom_pro` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE  `comp_egreso` ADD  `id` BIGINT NOT NULL;
ALTER TABLE `comp_egreso` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
ALTER TABLE  `comp_egreso` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `id` ) ;

ALTER TABLE  `fac_com` DROP FOREIGN KEY  `fk_fac_com` ;


CREATE TABLE IF NOT EXISTS `cuentas_mvtos` (
  `id_mov` int(11) NOT NULL AUTO_INCREMENT,
  `id_cuenta` int(11) NOT NULL,
  `tipo_mov` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `concepto_mov` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clase_mov` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `fecha_mov` datetime NOT NULL,
  PRIMARY KEY (`id_mov`),
  KEY `clase_mov` (`clase_mov`),
  KEY `id_cuenta` (`id_cuenta`),
  KEY `tipo_mov` (`tipo_mov`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cuentas_dinero` (
  `id_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `nom_cta` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_cta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `clase_cta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cod_cta` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `saldo_cta` decimal(24,2) NOT NULL,
  `fechaI_extractos` date NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_cuenta`),
  UNIQUE KEY `cod_cta` (`cod_cta`),
  KEY `tipo_cta` (`tipo_cta`),
  KEY `clase_cta` (`clase_cta`),
  KEY `fechaI_extractos` (`fechaI_extractos`),
  KEY `cod_su` (`cod_su`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



INSERT INTO `cuentas_dinero` (`id_cuenta`, `nom_cta`, `tipo_cta`, `clase_cta`, `cod_cta`, `saldo_cta`, `fechaI_extractos`, `cod_su`) VALUES (NULL, 'Caja General', 'Ingresos Ventas', 'Contado', '0001', '0', '2016-05-03', '1');




ALTER TABLE  `productos` ADD  `url_img` VARCHAR( 100 ) NOT NULL;

ALTER TABLE  `comp_egreso` ADD  `id_cuenta_trans` INT NOT NULL;




ALTER TABLE  `servicios` CHANGE  `servicio`  `servicio` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;





ALTER TABLE  `art_fac_com` ADD  `pvp_cre` DECIMAL( 24, 2 ) NOT NULL AFTER  `pvp` ,
ADD  `pvp_may` DECIMAL( 24, 2 ) NOT NULL AFTER  `pvp_cre`;
ALTER TABLE  `inv_inter` ADD  `pvp_may` DECIMAL( 24, 2 ) NOT NULL;






INSERT INTO `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'usu_lim_fac',  'Establecer Limites en Facturacion y Remisiones',  'Clientes y Usuarios',  '1'
);




ALTER TABLE  `art_fac_com` ADD  `fe_crea` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE  `art_fac_com` ADD  `ip_src` VARCHAR( 30 ) NOT NULL;


ALTER TABLE  `serv_fac_ven` CHANGE  `cod_serv`  `cod_serv` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `serv_fac_remi` CHANGE  `cod_serv`  `cod_serv` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `servicios` CHANGE  `cod_serv`  `cod_serv` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `fac_com` ADD  `calc_pvp` VARCHAR( 20 ) NOT NULL DEFAULT  'CALCULAR';









ALTER TABLE  `art_fac_com` ADD  `aplica_vehi` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `inv_inter` ADD  `aplica_vehi` VARCHAR( 100 ) NOT NULL; 
ALTER TABLE  `productos` ADD  `des_full` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `art_fac_com` ADD  `des_full` VARCHAR( 100 ) NOT NULL;






INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'fac_dev_ven',  '1',  '50000',  '1'
);




CREATE TABLE IF NOT EXISTS `fac_dev_venta` (
  `num_fac_ven` bigint(20) NOT NULL,
  `id_cli` varchar(30) NOT NULL,
  `nom_cli` varchar(50) NOT NULL,
  `tel_cli` varchar(30) NOT NULL,
  `dir` varchar(50) NOT NULL,
  `ciudad` varchar(30) NOT NULL,
  `cajero` varchar(30) NOT NULL,
  `mecanico` varchar(60) NOT NULL,
  `tipo_venta` varchar(30) NOT NULL,
  `tipo_cli` varchar(30) NOT NULL,
  `fecha` datetime NOT NULL,
  `vendedor` varchar(60) NOT NULL,
  `sub_tot` decimal(24,4) NOT NULL,
  `iva` decimal(24,4) NOT NULL,
  `descuento` decimal(24,4) NOT NULL,
  `tot` decimal(24,4) NOT NULL,
  `entrega` decimal(24,4) NOT NULL,
  `cambio` decimal(24,4) NOT NULL,
  `val_letras` varchar(100) NOT NULL,
  `anulado` varchar(30) NOT NULL,
  `modificable` varchar(30) NOT NULL,
  `corte_cont` varchar(30) NOT NULL,
  `nit` tinyint(4) NOT NULL,
  `fecha_anula` datetime NOT NULL,
  `estado` varchar(20) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `fe_naci` date NOT NULL,
  `placa` varchar(20) NOT NULL,
  `porcentaje_cre` tinyint(2) NOT NULL,
  `bono` bigint(20) NOT NULL DEFAULT '0',
  `prefijo` varchar(5) NOT NULL,
  `usu` varchar(20) NOT NULL,
  `id_usu` varchar(20) NOT NULL,
  `modifica` varchar(50) NOT NULL,
  `fecha_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_pago` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `resolucion` varchar(30) NOT NULL,
  `fecha_resol` date NOT NULL,
  `rango_resol` varchar(30) NOT NULL,
  `num_exp` bigint(20) NOT NULL,
  `abono_anti` decimal(10,2) NOT NULL,
  `anticipo_bono` varchar(15) NOT NULL DEFAULT 'NO',
  `cod_caja` int(11) NOT NULL,
  `tot_bsf` decimal(10,2) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `r_fte` decimal(10,2) NOT NULL,
  `r_ica` decimal(10,2) NOT NULL,
  `r_iva` decimal(10,2) NOT NULL,
  `kardex` int(11) NOT NULL DEFAULT '1',
  `num_pagare` bigint(20) NOT NULL,
  `entrega_bsf` decimal(10,2) NOT NULL,
  `sede_destino` int(11) NOT NULL,
  `per_fte` decimal(10,2) NOT NULL,
  `per_iva` decimal(10,2) NOT NULL,
  `per_ica` decimal(10,2) NOT NULL,
  `km` int(11) NOT NULL,
  `tipo_fac` varchar(20) NOT NULL,
  `tasa_cam` decimal(10,4) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `tot_tarjeta` decimal(10,2) NOT NULL,
  `tec2` varchar(60) NOT NULL,
  `tec3` varchar(60) NOT NULL,
  `tec4` varchar(60) NOT NULL,
  `tot_cre` decimal(20,2) NOT NULL,
  `cod_comision` varchar(20) NOT NULL,
  PRIMARY KEY (`id_cli`,`nom_cli`,`tipo_venta`,`tipo_cli`,`fecha`,`vendedor`,`tot`,`anulado`,`fecha_anula`),
  KEY `placa` (`placa`),
  KEY `fecha` (`fecha`),
  KEY `tipo_venta` (`tipo_venta`),
  KEY `tipo_cli` (`tipo_cli`),
  KEY `vendedor` (`vendedor`),
  KEY `anulado` (`anulado`),
  KEY `fecha_anula` (`fecha_anula`),
  KEY `estado` (`estado`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `prefijo` (`prefijo`),
  KEY `kardex` (`kardex`),
  KEY `tot_tarjeta` (`tot_tarjeta`),
  KEY `tot_bsf` (`tot_bsf`),
  KEY `abono_anti` (`abono_anti`),
  KEY `tot_cre` (`tot_cre`),
  KEY `anticipo_bono` (`anticipo_bono`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `art_devolucion_venta` (
  `num_fac_ven` bigint(20) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `serial` varchar(30) NOT NULL,
  `imei` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `iva` int(11) NOT NULL,
  `cant` decimal(20,2) NOT NULL,
  `cant_dev` decimal(20,2) NOT NULL,
  `precio` double(24,2) NOT NULL,
  `sub_tot` double(24,2) NOT NULL,
  `dcto` double(24,2) NOT NULL,
  `nit` tinyint(4) NOT NULL,
  `devolucion` varchar(10) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `cod_barras` varchar(30) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(5) NOT NULL,
  `fabricante` varchar(30) NOT NULL,
  `clase` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fraccion` bigint(20) NOT NULL,
  `unidades_fraccion` bigint(20) NOT NULL,
  `uni_dev` decimal(20,2) NOT NULL,
  `orden_in` int(11) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`ref`,`des`,`cant`,`precio`,`cod_barras`,`fecha_vencimiento`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `nit` (`nit`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `prefijo` (`prefijo`),
  KEY `serial` (`serial`),
  KEY `imei` (`imei`),
  KEY `des` (`des`),
  KEY `cod_barras` (`cod_barras`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `seriales_arqueos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fechaI` date NOT NULL,
  `fechaF` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `sub_clase` (
  `id_clase` bigint(20) NOT NULL,
  `id_sub_clase` bigint(20) NOT NULL AUTO_INCREMENT,
  `des_sub_clase` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sub_clase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;



ALTER TABLE  `comprobante_ingreso` ADD  `tipo_operacion` VARCHAR( 20 ) NOT NULL DEFAULT  'Individual';
ALTER TABLE  `comprobante_ingreso` ADD  `fe_ini` DATE NOT NULL ,ADD  `fe_fin` DATE NOT NULL;









 




INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'clientes_eli',  'Eliminar Clientes',  'Clientes y Usuarios',  '1'
);



ALTER TABLE  `sucursal` ADD  `resol_papel` VARCHAR( 20 ) NOT NULL AFTER  `rango_credito` ,
ADD  `fecha_resol_papel` DATE NOT NULL AFTER  `resol_papel` ,
ADD  `rango_papel` VARCHAR( 30 ) NOT NULL AFTER  `fecha_resol_papel`;
ALTER TABLE  `sucursal` ADD  `precio_bsf` DECIMAL( 10, 6 ) NOT NULL;
ALTER TABLE  `sucursal` ADD  `resol_credito_ant` VARCHAR( 30 ) NOT NULL ,
ADD  `fecha_resol_credito_ant` DATE NOT NULL ,
ADD  `rango_credito_ant` VARCHAR( 30 ) NOT NULL;

UPDATE  `sucursal` SET  `resol_credito_ant` =  'CRE',
`fecha_resol_credito_ant` =  '2015-01-01',
`rango_credito_ant` =  '(1 - 10000)' WHERE  `sucursal`.`cod_su` =1;

INSERT INTO `seriales` (`id_serial`, `seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES (NULL, 'cartera_ant', '1', '10000', '1');

ALTER TABLE  `sucursal` ADD  `cod_credito_ant` VARCHAR( 5 ) NOT NULL;

UPDATE `sucursal` SET  `resol_credito_ant` =  '000000000000',
`cod_credito_ant` =  'CRE' WHERE  `sucursal`.`cod_su` =1;

ALTER TABLE  `sucursal` ADD  `cod_remi_pos` VARCHAR( 5 ) NOT NULL DEFAULT  'REMI',
ADD  `resol_remi_pos` VARCHAR( 30 ) NOT NULL DEFAULT  '1000100',
ADD  `fecha_remi_pos` DATE NOT NULL ,
ADD  `rango_remi_pos` VARCHAR( 30 ) NOT NULL DEFAULT  '(1 - 100000)',
ADD  `cod_remi_com` VARCHAR( 5 ) NOT NULL DEFAULT  'RCOM',
ADD  `resol_remi_com` VARCHAR( 30 ) NOT NULL DEFAULT  '1000200',
ADD  `fecha_remi_com` DATE NOT NULL ,
ADD  `rango_remi_com` VARCHAR( 30 ) NOT NULL DEFAULT  '(1 - 100000)';

ALTER TABLE  `sucursal` ADD  `licencia_key` VARCHAR(40) NOT NULL;
ALTER TABLE  `sucursal` ADD  `id_responsable` VARCHAR( 30 ) NOT NULL ,
ADD  `placa_vehiculo` VARCHAR( 10 ) NOT NULL;
UPDATE  `sucursal` SET  `resol_credito_ant` =  '11001000' WHERE  `sucursal`.`cod_su` =1;


ALTER TABLE  `sucursal` ADD  `nom_negocio` VARCHAR( 50 ) NOT NULL ,
ADD  `nit_negocio` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `sucursal` CHANGE  `nit_negocio`  `nit_negocio` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;



ALTER TABLE  `usuarios` ADD  `fecha_crea` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
UPDATE `usuarios` SET fecha_crea='2014-07-01 00:00:00' WHERE 1;
ALTER TABLE  `usuarios` CHANGE  `cliente`  `cliente` TINYINT( 4 ) NOT NULL DEFAULT  '1';
ALTER TABLE  `usuarios` ADD  `cod_caja` INT NOT NULL;
ALTER TABLE  `usuarios` ADD  `tope_credito` DECIMAL( 20, 2 ) NOT NULL ,
ADD  `auth_credito` INT( 2 ) NOT NULL DEFAULT  '1';




ALTER TABLE  `usuarios` ADD  `id` BIGINT NOT NULL;
ALTER TABLE `usuarios` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
ALTER TABLE  `usuarios` ADD  `tipo_usu` VARCHAR( 20 ) NOT NULL DEFAULT  'Particular';
ALTER TABLE `usuarios`
  DROP PRIMARY KEY,
   ADD PRIMARY KEY(
     `id_usu`,
     `nombre`);
ALTER TABLE  `usuarios` ADD  `fecha_ban` DATE NOT NULL ,
ADD  `monto_ban` DECIMAL( 24, 2 ) NOT NULL;


ALTER TABLE  `usuarios` ADD  `fecha_ban_remi` DATE NOT NULL ,
ADD  `monto_ban_remi` DECIMAL( 24, 2 ) NOT NULL;
ALTER TABLE  `usuarios` ADD  `cod_comision` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `usuarios` ADD  `alias` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `usuarios` CHANGE  `tipo_usu`  `tipo_usu` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'Particular';
ALTER TABLE  `usuarios` CHANGE  `cod_comision`  `cod_comision` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `usuarios` CHANGE  `alias`  `alias` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `usuarios` ADD  `chofer` INT( 4 ) NOT NULL DEFAULT  '0';





ALTER TABLE  `comp_egreso` CHANGE  `tipo_gasto`  `tipo_gasto` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `cuentas_mvtos` ADD  `saldo` DECIMAL( 24, 2 ) NOT NULL;

ALTER TABLE  `art_fac_com` ADD  `serial_art` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `art_fac_com` ADD  `cert_importacion` VARCHAR( 50 ) NOT NULL;






ALTER TABLE  `orden_garantia` ADD  `id_inter` VARCHAR( 30 ) NOT NULL , ADD  `id_inter2` VARCHAR( 30 ) NOT NULL;
ALTER TABLE  `art_devolucion_venta` ADD  `cod_garantia` VARCHAR( 50 ) NOT NULL;







ALTER TABLE  `fac_dev` ADD  `anulado` VARCHAR(15) NOT NULL;
ALTER TABLE  `fac_dev` ADD  `fecha_anula` DATE NOT NULL;





INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'adm_art',  'Administrar Ventas Repuestos',  'Ventas',  '1'
);






 
INSERT INTO `seriales_inv` (`id`, `label`, `min`, `max`, `current`, `cod_su`) VALUES
(1, 'GENERAL', 1, 1000, 3, 1);







ALTER TABLE  `vehiculo` CHANGE  `placa`  `placa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'Placa';
ALTER TABLE  `vehiculo` CHANGE  `modelo`  `modelo` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'Modelo';
ALTER TABLE  `vehiculo` CHANGE  `cc`  `cc` DECIMAL( 10, 1 ) NOT NULL COMMENT  'Cilindrada Motor';
ALTER TABLE  `vehiculo` CHANGE  `color`  `color` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'Color';
ALTER TABLE  `vehiculo` CHANGE  `km`  `km` INT( 11 ) NOT NULL COMMENT  'Recorrido (Km)';
ALTER TABLE  `vehiculo` CHANGE  `id_propietario`  `id_propietario` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'C.C Propietario';
ALTER TABLE  `vehiculo` ADD  `marca` VARCHAR( 20 ) NOT NULL COMMENT  'Marca';
ALTER TABLE  `vehiculo` CHANGE  `id_vehiculo`  `id_vehiculo` INT( 11 ) NOT NULL AUTO_INCREMENT COMMENT  'ID Interno';





INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'dev_ventas',  'Devoluciones',  'Ventas',  '1'
);












ALTER TABLE  `comprobante_ingreso` ADD  `r_fte` DECIMAL( 24, 2 ) NOT NULL ,
ADD  `per_r_fte` DECIMAL( 5, 2 ) NOT NULL ,
ADD  `r_ica` DECIMAL( 24, 2 ) NOT NULL ,
ADD  `per_r_ica` DECIMAL( 5, 2 ) NOT NULL;























INSERT INTO `x_material_query` (`id`, `seccion`, `last`, `reset_days`) VALUES
(1, 'Rotacion Inventario', '2016-08-01', 15);




ALTER TABLE  `orden_garantia` CHANGE  `id`  `id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT COMMENT  'ID';
ALTER TABLE  `orden_garantia` CHANGE  `id_cli`  `id_cli` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'C.C/NIT';
ALTER TABLE  `orden_garantia` CHANGE  `cod_garantia`  `cod_garantia` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'Tarj. Garantia';
ALTER TABLE  `orden_garantia` CHANGE  `id_pro`  `id_pro` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'REF. producto';
ALTER TABLE  `orden_garantia` CHANGE  `fecha_venta`  `fecha_venta` DATE NOT NULL COMMENT  'Fecha Venta';
ALTER TABLE  `orden_garantia` CHANGE  `id_pro_prestamo`  `id_pro_prestamo` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'REF. prestamo';
ALTER TABLE  `orden_garantia` CHANGE  `num_gui`  `num_gui` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'No. Guia';
ALTER TABLE  `orden_garantia` CHANGE  `transportadora`  `transportadora` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT  'Trasportadora';
ALTER TABLE  `orden_garantia` CHANGE  `fecha_envio`  `fecha_envio` DATE NOT NULL COMMENT  'Fecha Envio';
ALTER TABLE  `orden_garantia` CHANGE  `fecha`  `fecha` DATETIME NOT NULL COMMENT  'Fecha';
ALTER TABLE  `orden_garantia` CHANGE  `placa_vehi`  `placa_vehi` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Placa';
ALTER TABLE  `orden_garantia` ADD  `estado` VARCHAR( 20 ) NOT NULL;


INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'crear_garantias',  'Autorizar Garantias Productos',  'Manejo de Inventario',  '1'
);

INSERT INTO  `x_material_query` (
`id` ,
`seccion` ,
`last` ,
`reset_days`
)
VALUES (
NULL ,  'Ajuste Kardex',  '2017-01-02',  '1'
);
ALTER TABLE  `x_material_query` ADD  `state` VARCHAR( 40 ) NOT NULL ;



ALTER TABLE  `fac_venta` ADD  `serial_fac` BIGINT NOT NULL;  
ALTER TABLE `fac_venta` MODIFY COLUMN `serial_fac` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;

ALTER TABLE  `art_fac_ven` ADD  `serial_fac` BIGINT NOT NULL FIRST;
ALTER TABLE  `art_fac_ven` ADD  `serial_art_fac` BIGINT NOT NULL FIRST;
ALTER TABLE `art_fac_ven` MODIFY COLUMN `serial_art_fac` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
UPDATE art_fac_ven a INNER JOIN ( SELECT  serial_fac,num_fac_ven,prefijo,nit FROM fac_venta ) b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit
SET a.serial_fac=b.serial_fac;





ALTER TABLE  `fac_remi` ADD  `serial_fac` BIGINT NOT NULL;  
ALTER TABLE `fac_remi` MODIFY COLUMN `serial_fac` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;

ALTER TABLE  `art_fac_remi` ADD  `serial_fac` BIGINT NOT NULL FIRST;
ALTER TABLE  `art_fac_remi` ADD  `serial_art_fac` BIGINT NOT NULL FIRST;
ALTER TABLE `art_fac_remi` MODIFY COLUMN `serial_art_fac` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
UPDATE art_fac_remi a INNER JOIN ( SELECT  serial_fac,num_fac_ven,prefijo,nit FROM fac_remi ) b ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.nit
SET a.serial_fac=b.serial_fac;


ALTER TABLE `inv_inter` DROP `serial_inv`;
ALTER TABLE  `inv_inter` ADD  `serial_inv` BIGINT NOT NULL;  
ALTER TABLE `inv_inter` MODIFY COLUMN `serial_inv` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;


ALTER TABLE  `fac_venta` ADD  `estado_comi` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


ALTER TABLE  `fac_venta` ADD  `cod_comision` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `fac_remi` ADD  `cod_comision` VARCHAR( 20 ) NOT NULL;

ALTER TABLE  `fac_remi` ADD  `tec2` VARCHAR( 60 ) NOT NULL ,
ADD  `tec3` VARCHAR( 60 ) NOT NULL ,
ADD  `tec4` VARCHAR( 60 ) NOT NULL;

ALTER TABLE  `fac_venta` ADD  `tec2` VARCHAR( 60 ) NOT NULL ,
ADD  `tec3` VARCHAR( 60 ) NOT NULL ,
ADD  `tec4` VARCHAR( 60 ) NOT NULL;


ALTER TABLE  `fac_remi` ADD  `tipo_fac` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `fac_venta` ADD  `tipo_fac` VARCHAR( 20 ) NOT NULL;






ALTER TABLE  `fac_venta` ADD  `tasa_cam` DECIMAL( 10, 4 ) NOT NULL;
UPDATE fac_venta SET tasa_cam=0.95 WHERE 1;

ALTER TABLE  `fac_venta` ADD  `nota` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `fac_venta` ADD  `tot_tarjeta` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `fac_remi` CHANGE  `mecanico`  `mecanico` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `fac_venta` CHANGE  `mecanico`  `mecanico` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `fac_venta` CHANGE  `vendedor`  `vendedor` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `fac_remi` CHANGE  `vendedor`  `vendedor` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE  `fac_remi` ADD  `nf` BIGINT NOT NULL;
ALTER TABLE  `fac_remi` ADD  `pre` VARCHAR( 5 ) NOT NULL;
ALTER TABLE  `fac_remi` ADD  `tot_cre` DECIMAL( 20, 2 ) NOT NULL;
ALTER TABLE  `fac_venta` ADD  `tot_cre` DECIMAL( 20, 2 ) NOT NULL;


ALTER TABLE  `fac_venta` ADD  `per_fte` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `per_iva` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `per_ica` DECIMAL( 10, 2 ) NOT NULL;



ALTER TABLE  `fac_remi` ADD  `sede_destino` INT NOT NULL;

ALTER TABLE  `fac_venta` ADD  `sede_destino` INT NOT NULL;



ALTER TABLE  `fac_venta` ADD  `entrega_bsf` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `fac_remi` ADD  `entrega_bsf` DECIMAL( 10, 2 ) NOT NULL;


ALTER TABLE  `fac_venta` ADD  `num_pagare` BIGINT NOT NULL;
ALTER TABLE  `art_fac_ven` ADD  `orden_in` INT NOT NULL;

ALTER TABLE  `fac_venta` ADD  `r_fte` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `r_ica` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `r_iva` DECIMAL( 10, 2 ) NOT NULL ,
ADD  `kardex` INT NOT NULL DEFAULT  '1';
ALTER TABLE  `fac_venta` ADD INDEX (  `kardex` );


ALTER TABLE  `fac_venta` ADD  `id_banco` INT NOT NULL ,
ADD  `id_cuenta` INT NOT NULL;


ALTER TABLE  `fac_venta` ADD  `tot_bsf` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `fac_venta` ADD  `cod_caja` INT NOT NULL;

ALTER TABLE  `fac_venta` ADD  `resolucion` VARCHAR( 30 ) NOT NULL ,
ADD  `fecha_resol` DATE NOT NULL ,
ADD  `rango_resol` VARCHAR( 30 ) NOT NULL;

ALTER TABLE  `art_fac_ven` ADD  `cod_barras` VARCHAR( 30 ) NOT NULL ,
ADD  `color` VARCHAR( 10 ) NOT NULL ,
ADD  `talla` VARCHAR( 5 ) NOT NULL ,
ADD  `fabricante` VARCHAR( 30 ) NOT NULL ,
ADD  `clase` VARCHAR( 30 ) NOT NULL;


ALTER TABLE  `art_fac_ven` ADD  `cod_garantia` VARCHAR( 50 ) NOT NULL;
ALTER TABLE  `art_fac_ven` ADD INDEX (  `cod_garantia` );
ALTER TABLE  `art_fac_remi` ADD  `cod_garantia` VARCHAR( 50 ) NOT NULL ,
ADD INDEX (  `cod_garantia` );


ALTER TABLE  `art_fac_ven` CHANGE  `des`  `des` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `art_fac_ven` ADD  `fraccion` INT NOT NULL ,
ADD  `unidades_fraccion` INT NOT NULL


ALTER TABLE  `art_fac_ven` ADD  `cant_dev` DECIMAL( 20, 2 ) NOT NULL AFTER  `cant`;
ALTER TABLE  `art_fac_ven` ADD  `uni_dev` DECIMAL( 20, 2 ) NOT NULL AFTER  `unidades_fraccion`;


ALTER TABLE  `art_fac_remi` ADD  `cant_dev` DECIMAL( 20, 2 ) NOT NULL AFTER  `cant`;
ALTER TABLE  `art_fac_remi` ADD  `uni_dev` DECIMAL( 20, 2 ) NOT NULL AFTER  `unidades_fraccion`;

ALTER TABLE  `art_fac_ven` ADD  `fecha_vencimiento` DATE NOT NULL;

ALTER TABLE  `art_fac_ven` ADD  `presentacion` VARCHAR( 30 ) NOT NULL;

ALTER TABLE  `fac_venta` ADD  `km` BIGINT NOT NULL ;





ALTER TABLE  `fac_venta` ADD  `tipo_comi` VARCHAR( 20 ) NOT NULL ;











ALTER TABLE  `art_fac_remi` CHANGE  `des`  `des` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;


ALTER TABLE  `art_fac_remi` ADD UNIQUE (
`num_fac_ven` ,
`ref` ,
`sub_tot` ,
`nit` ,
`prefijo` ,
`cod_barras` ,
`fecha_vencimiento`
);

ALTER TABLE  `art_fac_com` CHANGE  `dcto`  `dcto` DECIMAL( 24, 20 ) NOT NULL ;



ALTER TABLE  `x_material_query` ADD  `cod_su` INT NOT NULL DEFAULT  '1';
ALTER TABLE  `x_material_query` ADD UNIQUE (
`seccion` ,
`cod_su`
);



ALTER TABLE  `comprobante_ingreso` ADD  `id` BIGINT NOT NULL;
ALTER TABLE `comprobante_ingreso` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
ALTER TABLE  `comprobante_ingreso` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `id` ) ;







-- INICIO X_CONFIG LAST VERSION
INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`) VALUES (NULL, 'usar_serial', '0', '1');
INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`) VALUES (NULL, 'IMG_PRODUCTO', '0', '1');



































ALTER TABLE  `sucursal` ADD  `cod_remi_com2` VARCHAR( 5 ) NOT NULL AFTER  `rango_remi_com` ,
ADD  `resol_remi_com2` VARCHAR( 30 ) NOT NULL AFTER  `cod_remi_com2` ,
ADD  `fecha_remi_com2` DATE NOT NULL AFTER  `resol_remi_com2` ,
ADD  `rango_remi_com2` VARCHAR( 20 ) NOT NULL AFTER  `fecha_remi_com2` ;

UPDATE  `sucursal` SET  `cod_remi_com2` =  'RE2',
`resol_remi_com2` =  '18000002',
`fecha_remi_com2` =  '2017-07-10',
`rango_remi_com2` =  '(1 - 100.000)' WHERE  `sucursal`.`cod_su` =1;

UPDATE  `sucursal` SET  `cod_remi_com2` =  'RE2',
`resol_remi_com2` =  '18000002',
`fecha_remi_com2` =  '2017-07-10',
`rango_remi_com2` =  '(1 - 100.000)' WHERE  `sucursal`.`cod_su` =2;

UPDATE  `sucursal` SET  `cod_remi_com2` =  'RE2',
`resol_remi_com2` =  '18000002',
`fecha_remi_com2` =  '2017-07-10',
`rango_remi_com2` =  '(1 - 100.000)' WHERE  `sucursal`.`cod_su` =3;

ALTER TABLE  `seriales` ADD UNIQUE (
`seccion` ,
`nit_sede`
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision_com2',  '1',  '100000',  '1'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision_com2',  '1',  '100000',  '2'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision_com2',  '1',  '100000',  '3'
);






INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'impuesto_consumo',  '0',  '1'
);

ALTER TABLE  `fac_venta` ADD  `imp_consumo` DECIMAL( 24, 2 ) NOT NULL ;
ALTER TABLE  `fac_remi` ADD  `imp_consumo` DECIMAL( 24, 2 ) NOT NULL ;

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'valor_impuesto_bolsas',  '20',  '1'
);

ALTER TABLE  `fac_venta` ADD  `num_bolsas` SMALLINT NOT NULL ,ADD  `impuesto_bolsas` INT NOT NULL ;

ALTER TABLE  `fac_remi` ADD  `num_bolsas` SMALLINT NOT NULL , ADD  `impuesto_bolsas` INT NOT NULL ;

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'impuesto_bolsas',  '0',  '1'
);











ALTER TABLE  `sucursal` ADD  `cod_papel` VARCHAR( 10 ) NOT NULL DEFAULT  'TALO' AFTER  `rango_credito` ;
INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'formatos_peluqueria',  '0',  '1'
);




INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'COMPRAS',  '1',  '1'
);

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'DEVOLUCIONES',  '1',  '1'
);
INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'VENTA_VEHICULOS',  '0',  '1'
);

ALTER TABLE  `fac_venta` ADD  `id_vendedor` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE  `fac_venta` ADD INDEX (  `id_vendedor` ) ;

ALTER TABLE  `usuarios` ADD  `nomina_1` TINYINT NOT NULL ,
ADD INDEX (  `nomina_1` ) ;



INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'ARQUEOS',  '1',  '1'
);

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'VALOR_CURSO_NOMINA',  '0',  '1'
);

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'INVENTARIO_PVP_UNIFICADO',  '0',  '1'
);

ALTER TABLE  `fac_venta` ADD  `marca_moto` VARCHAR( 20 ) NOT NULL ,
ADD INDEX (  `marca_moto` ) ;

ALTER TABLE `serv_fac_ven` CHANGE `serv` `serv` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;


ALTER TABLE  `fac_venta` ADD  `hash` VARCHAR( 150 ) NOT NULL ;
ALTER TABLE  `fac_venta` ADD INDEX (  `hash` ) ;
UPDATE fac_venta SET hash=serial_fac;
ALTER TABLE  `fac_venta` ADD UNIQUE (
`hash`
);


ALTER TABLE  `art_fac_ven` ADD  `hash` VARCHAR( 150 ) NOT NULL;
ALTER TABLE  `art_fac_ven` ADD INDEX (  `hash` ) ;
UPDATE art_fac_ven a INNER JOIN (SELECT num_fac_ven,nit,prefijo,hash FROM fac_venta) b ON a.num_fac_ven=b.num_fac_ven AND a.nit=b.nit AND a.prefijo=b.prefijo  SET a.hash=b.hash;

ALTER TABLE  `serv_fac_ven` ADD  `hash` VARCHAR( 150 ) NOT NULL;
ALTER TABLE  `serv_fac_ven` ADD INDEX (  `hash` ) ;
UPDATE serv_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.cod_su=b.nit AND a.prefijo=b.prefijo  SET a.hash=b.hash;




INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'NUM_NOTA_ENTREGA',  '0',  '1'
);

INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'informes_nomina',  'Informe Nomina',  'Informes',  '1'
);

INSERT INTO  `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'informes_nomina_tot',  'Informe Nomina Total Sedes',  'Informes',  '1'
);


ALTER TABLE  `comprobante_ingreso` ADD  `id_vendedor` VARCHAR( 20 ) NOT NULL ;

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'fecha_corte_cuentas_pagar',  '',  '1'
);

INSERT INTO `secciones` (
`id_secc` ,
`des_secc` ,
`modulo` ,
`habilita`
)
VALUES (
'lista_comp_egreso',  'Listado Egresos',  'Egresos',  '1'
);

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'planes_clientes',  '0',  '1'
);

ALTER TABLE  `usuarios` ADD  `estrato` SMALLINT NOT NULL ;

ALTER TABLE  `usuarios` CHANGE  `tope_credito`  `tope_credito` BIGINT NOT NULL;

ALTER TABLE  `fac_remi` ADD  `impuesto_bolsas` INT NOT NULL ;

ALTER TABLE  `ajustes` ADD PRIMARY KEY (  `num_ajuste` ,  `cod_su` ) ;
ALTER TABLE `art_ajuste` DROP PRIMARY KEY, ADD PRIMARY KEY( `num_ajuste`, `ref`, `cod_su`, `cod_barras`, `fecha_vencimiento`);

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'limitar_solo_contratos_credito',  '0',  '1'
);

ALTER TABLE  `fac_dev_venta` ADD  `imp_consumo` DECIMAL( 24, 2 ) NOT NULL ;
ALTER TABLE  `fac_dev_venta` ADD  `num_bolsas` SMALLINT NOT NULL ,ADD  `impuesto_bolsas` INT NOT NULL ;

--ALTER TABLE  `ajustes` ADD  `id` BIGINT NOT NULL;
--ALTER TABLE `ajustes` MODIFY COLUMN `id` INT NOT NULL UNIQUE AUTO_INCREMENT FIRST;
--ALTER TABLE  `ajustes`  ADD PRIMARY KEY (  `id` ) ;

--DELETE FROM ajustes WHERE id NOT IN (SELECT * FROM (SELECT MAX(n.id) FROM ajustes n GROUP BY n.`num_ajuste`,n.`cod_su`) x);
--ALTER TABLE `ajustes` ADD PRIMARY KEY( `num_ajuste`, `cod_su`);

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'usar_color',  '0',  '1'
);

ALTER TABLE  `art_fac_com` ADD  `linea` VARCHAR( 30 ) NOT NULL ,
ADD  `modelo` VARCHAR( 10 ) NOT NULL ,
ADD  `num_motor` VARCHAR( 50 ) NOT NULL ,
ADD  `num_chasis` VARCHAR( 50 ) NOT NULL ,
ADD  `cilindraje` VARCHAR( 10 ) NOT NULL ,
ADD  `consecutivo_proveedor` VARCHAR( 50 ) NOT NULL ,
ADD INDEX (  `linea` ,  `modelo` ,  `num_motor` ,  `num_chasis` ,  `cilindraje` ,  `consecutivo_proveedor` ) ;

ALTER TABLE  `art_fac_ven` ADD  `linea` VARCHAR( 30 ) NOT NULL ,
ADD  `modelo` VARCHAR( 10 ) NOT NULL ,
ADD  `num_motor` VARCHAR( 50 ) NOT NULL ,
ADD  `num_chasis` VARCHAR( 50 ) NOT NULL ,
ADD  `cilindraje` VARCHAR( 10 ) NOT NULL ,
ADD  `consecutivo_proveedor` VARCHAR( 50 ) NOT NULL ,
ADD INDEX (  `linea` ,  `modelo` ,  `num_motor` ,  `num_chasis` ,  `cilindraje` ,  `consecutivo_proveedor` ) ;

INSERT INTO  `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'usar_datos_motos',  '0',  '1'
);
INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'usar_fracciones_unidades',  '1',  '1'
);


ALTER TABLE  `serv_fac_remi` CHANGE  `serv`  `serv` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE  `serv_fac_ven` CHANGE  `serv`  `serv` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'mesas_pedidos',  '0',  '1'
);


ALTER TABLE  `fac_venta` ADD  `num_mesa` INT NOT NULL ,
ADD INDEX (  `num_mesa` ) ;
CREATE TABLE IF NOT EXISTS `mesas` (
  `id_mesa` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `num_mesa` int(11) NOT NULL COMMENT 'Num. Mesa',
  `valor` decimal(24,2) NOT NULL COMMENT 'Cuenta $',
  `estado` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Disponible' COMMENT 'Disponibilidad',
  `cod_su` int(11) NOT NULL DEFAULT '1' COMMENT 'Cod. Sede',
  `num_fac_ven` bigint(20) NOT NULL COMMENT 'Factura',
  `prefijo` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'PRE',
  `hash` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ID 2',
  PRIMARY KEY (`id_mesa`),
  KEY `estado` (`estado`),
  KEY `num_fac_ven` (`num_fac_ven`,`prefijo`,`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'ID_CLI_DCTO_ESPECIAL_1',  '0',  '1'
);

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'ID_CLI_DCTO_ESPECIAL_2',  '0',  '1'
);

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'fac_ven_font_size',  '10',  '1'
);

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'ropa_campos_extra',  '0',  '1'
);

INSERT INTO   `x_config` (
`id_config` ,
`des_config` ,
`val` ,
`cod_su`
)
VALUES (
NULL ,  'label_pvp_mayo',  'PvP Mayorista',  '1'
);




----- nov 2023

ALTER TABLE `inv_inter` ADD `impuesto_saludable` SMALLINT(2) NOT NULL DEFAULT '0' AFTER `iva`;


----- ENERO 2024

-- reducir espacio, aumentar velocidad consultas
ALTER TABLE `fac_venta` CHANGE `hash` `hash` VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;

ALTER TABLE `fac_venta` CHANGE `serial_fac` `serial_fac` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
ALTER TABLE `fac_venta` CHANGE `num_fac_ven` `num_fac_ven` BIGINT(20) NOT NULL COMMENT 'Num. Fac';
ALTER TABLE `fac_venta` CHANGE `id_cli` `id_cli` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'ID Cliente';
ALTER TABLE `fac_venta` CHANGE `tel_cli` `tel_cli` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Tel.';
ALTER TABLE `fac_venta` CHANGE `dir` `dir` VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Direccion';
ALTER TABLE `fac_venta` CHANGE `ciudad` `ciudad` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Ciudad';
ALTER TABLE `fac_venta` CHANGE `tot` `tot` DECIMAL(24,4) NOT NULL COMMENT 'Total';
ALTER TABLE `fac_venta` CHANGE `fecha` `fecha` DATETIME NOT NULL COMMENT 'Fecha';


ALTER TABLE `sucursal` ADD `frecuencia_pago` VARCHAR(20) NOT NULL DEFAULT 'ANUAL' COMMENT 'Frecuencia Pago' AFTER `valor_anual`;

ALTER TABLE `sucursal` ADD `fecha_corte` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `fecha_pago`;


ALTER TABLE `clases_favoritos` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', add PRIMARY KEY (`id`);

UPDATE `x_config` SET `nota_uso` = 'decimal, centena' WHERE `x_config`.`id_config` = 25;
ALTER TABLE `fac_venta` CHANGE `nota` `nota` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `art_fac_com` CHANGE `unidades_fraccion` `unidades_fraccion` BIGINT(20) NOT NULL DEFAULT '0';




ALTER TABLE `art_fac_ven` CHANGE `des` `des` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `productos` CHANGE `detalle` `detalle` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;



ALTER TABLE `inv_inter_alter` ADD `impuesto_saludable` SMALLINT(2) NOT NULL DEFAULT '0' AFTER `iva`, ADD INDEX `invAlterImpSalud` (`impuesto_saludable`);

ALTER TABLE `fac_venta` ADD `propina` TINYINT NOT NULL AFTER `DESCUENTO_IVA`;
ALTER TABLE `sucursal` ADD `resolPosElectronica` TINYINT NOT NULL DEFAULT '0' AFTER `frecuencia_pago`;

ALTER TABLE `fac_venta` ADD `tipo_resol` VARCHAR(3) NOT NULL AFTER `propina`, ADD INDEX `tipoResol` (`tipo_resol`);


ALTER TABLE `servicio_internet_planes` ADD `tipoPlan` VARCHAR(30) NOT NULL DEFAULT 'ANUAL' COMMENT 'Tipo Plan' AFTER `nombre_servicio`, 
ADD `fechaCobro` DATE NOT NULL COMMENT 'Fecha Cobro' AFTER `tipoPlan`, 
ADD `subDominioApp` VARCHAR(50) NOT NULL COMMENT 'Sub dominio' AFTER `fechaCobro`, 
ADD `nombreSucursal` VARCHAR(50) NOT NULL COMMENT 'Sucursal' AFTER `subDominioApp`;

ALTER TABLE `servicio_internet_planes` ADD `wasap` VARCHAR(20) NOT NULL COMMENT 'WhatsApp ' AFTER `nombreSucursal`;
ALTER TABLE `servicio_internet_planes` ADD `ultimoPago` DATE NOT NULL COMMENT 'Fecha Ult. pago' AFTER `fechaCobro`;

ALTER TABLE `servicios` ADD `clasificacion` VARCHAR(30) NOT NULL COMMENT 'Clasificacion Servicio' AFTER `cod_serv`;



INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'auditoria', '0', '1', '1', 'Activa o desactiva log de movimientos 1 | 0');
update  `auditoria` set seccion_afectada='Factura Venta' WHERE seccion_afectada='Facura Venta';
ALTER TABLE `auditoria` CHANGE `reg_new` `reg_new` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `auditoria` CHANGE `reg_ant` `reg_ant` LONGTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;



ALTER TABLE `fac_venta` ADD `idFacturaDian` MEDIUMINT(7) NOT NULL AFTER `tipo_resol`;

INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'modoPruebas', '0', '1', '0', '1 | 0 Activa/Desactiva modo de testeo, para que los usuarios aprendan a usar la App');

INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'fac_ven_etiqueta_nogravados', 'NO GRAVADO', '1', '0', 'NO GRAVADO | EXCLUIDO');
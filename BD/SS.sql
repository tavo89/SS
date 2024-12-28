-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 28-12-2017 a las 20:39:43
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ss`
--
CREATE DATABASE IF NOT EXISTS `ss` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `ss`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`127.0.0.1` PROCEDURE `FillCalendar`(start_date DATE, end_date DATE)
BEGIN
    DECLARE crt_date DATE;
    SET crt_date = start_date;
    WHILE crt_date <= end_date DO
        INSERT IGNORE INTO calendar VALUES(crt_date);
        SET crt_date = ADDDATE(crt_date, INTERVAL 1 DAY);
    END WHILE;
    END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `STRIP_NON_DIGIT`(input VARCHAR(255)) RETURNS varchar(255) CHARSET utf8 COLLATE utf8_unicode_ci
BEGIN
   DECLARE output   VARCHAR(255) DEFAULT '';
   DECLARE iterator INT          DEFAULT 1;
   WHILE iterator < (LENGTH(input) + 1) DO
      IF SUBSTRING(input, iterator, 1) IN (',', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ) THEN
         SET output = CONCAT(output, SUBSTRING(input, iterator, 1));
      END IF;
      SET iterator = iterator + 1;
   END WHILE;   
   RETURN output;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

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
  PRIMARY KEY (`num_ajuste`,`cod_su`),
  KEY `nit` (`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajuste_cajas`
--

CREATE TABLE IF NOT EXISTS `ajuste_cajas` (
  `fecha` date NOT NULL,
  `base_caja` decimal(24,2) NOT NULL,
  `valor_base` decimal(24,2) NOT NULL,
  `valor_entrega` decimal(24,2) NOT NULL,
  `valor_diferencia` decimal(22,2) NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fecha`,`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Disparadores `ajuste_cajas`
--
DROP TRIGGER IF EXISTS `diff_caja`;
DELIMITER //
CREATE TRIGGER `diff_caja` BEFORE UPDATE ON `ajuste_cajas`
 FOR EACH ROW IF NEW.valor_entrega=0 THEN
SET NEW.valor_diferencia=NEW.valor_base-OLD.valor_entrega;

ELSE
SET NEW.valor_diferencia=NEW.valor_base-NEW.valor_entrega;
END IF
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_ajuste`
--

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
  PRIMARY KEY (`num_ajuste`,`ref`,`cod_su`,`cod_barras`,`fecha_vencimiento`),
  KEY `num_fac_ven` (`num_ajuste`),
  KEY `nit` (`cod_su`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_devolucion_venta`
--

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
  `cod_garantia` varchar(50) NOT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_fac_com`
--

CREATE TABLE IF NOT EXISTS `art_fac_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_fac_com` varchar(30) NOT NULL,
  `cant` decimal(24,4) NOT NULL,
  `ref` varchar(30) NOT NULL,
  `des` varchar(100) NOT NULL,
  `costo` decimal(24,2) NOT NULL,
  `dcto` decimal(24,20) NOT NULL,
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
  UNIQUE KEY `id_2` (`id`),
  KEY `num_fac_com` (`num_fac_com`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `cod_su` (`cod_su`),
  KEY `nit_pro` (`nit_pro`),
  KEY `cod_barras` (`cod_barras`),
  KEY `cod_barras_2` (`cod_barras`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_fac_dev`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_fac_remi`
--

CREATE TABLE IF NOT EXISTS `art_fac_remi` (
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
  PRIMARY KEY (`num_fac_ven`,`ref`,`nit`,`prefijo`,`cod_barras`,`fecha_vencimiento`),
  UNIQUE KEY `serial_art_fac` (`serial_art_fac`),
  UNIQUE KEY `num_fac_ven_2` (`num_fac_ven`,`ref`,`sub_tot`,`nit`,`prefijo`,`cod_barras`,`fecha_vencimiento`),
  UNIQUE KEY `num_fac_ven_3` (`num_fac_ven`,`ref`,`sub_tot`,`nit`,`prefijo`,`cod_barras`,`fecha_vencimiento`),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `art_fac_ven`
--

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
  `hash` varchar(150) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`ref`,`nit`,`prefijo`,`cod_barras`,`fecha_vencimiento`),
  UNIQUE KEY `serial_art_fac` (`serial_art_fac`),
  KEY `num_fac_ven` (`num_fac_ven`),
  KEY `nit` (`nit`),
  KEY `ref` (`ref`),
  KEY `iva` (`iva`),
  KEY `prefijo` (`prefijo`),
  KEY `serial` (`serial`),
  KEY `imei` (`imei`),
  KEY `des` (`des`),
  KEY `cod_garantia` (`cod_garantia`),
  KEY `cod_garantia_2` (`cod_garantia`),
  KEY `cod_garantia_3` (`cod_garantia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE IF NOT EXISTS `auditoria` (
  `audit_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sql` varchar(50) NOT NULL,
  `usu` varchar(30) NOT NULL,
  `id_usu` varchar(30) NOT NULL,
  `ip_host` varchar(30) NOT NULL,
  `tipo_operacion` varchar(20) NOT NULL,
  `seccion_afectada` varchar(30) NOT NULL,
  `reg_ant` text NOT NULL,
  `reg_new` text NOT NULL,
  `reg_key` varchar(30) NOT NULL,
  `fecha_op` date NOT NULL,
  `hora_op` time NOT NULL,
  `id_sede` tinyint(4) NOT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `usu` (`usu`),
  KEY `id_usu` (`id_usu`),
  KEY `tipo_operacion` (`tipo_operacion`),
  KEY `seccion_afectada` (`seccion_afectada`),
  KEY `reg_key` (`reg_key`),
  KEY `fecha_op` (`fecha_op`),
  KEY `id_sede` (`id_sede`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`audit_id`, `sql`, `usu`, `id_usu`, `ip_host`, `tipo_operacion`, `seccion_afectada`, `reg_ant`, `reg_new`, `reg_key`, `fecha_op`, `hora_op`, `id_sede`) VALUES
(1, '', 'Administrador', '2222222222', '127.0.0.1', 'Modificar Registro', 'Clientes', '', '', '2222222222', '2017-10-20', '09:39:32', 1),
(2, '', 'Administrador', '2222222222', '127.0.0.1', 'Crear Registro', 'Facura Venta', 'NO APLICA', ' <table id="fac_venta_table2" border="0" align="center" cellspacing="0" cellpadding="0" class="round_table_white uk-form-small uk-table uk-table-striped" style="color:#000"><tbody><tr><td colspan=""><table cellspacing="0" cellpadding="0"><tbody><tr><td id="tipo_factura_td" colspan="" class="uk-text-bold uk-text-large" align="center"><h3 class="uk-text-primary uk-text-bold " style=" font-size:42px;"> FACTURA</h3></td><td id="resolucion_td" align="right"><select class="uk-text-primary" name="tipo_resol" id="tipo_resol" onchange="cambia_resol($(this),$(''#pos''),$(''#com''),$(''#papel''),$(''#com_ant''),$(''#papel_ant''),$(''#cre_ant''))"><option value="POS">POS</option><option value="COM" selected="">COM</option><option value="PAPEL">Papel</option></select></td><td id="consecutivo_td" colspan="" align="center" class="uk-text-bold uk-text-danger uk-text-large" style="font-size:42px;"><span id="pos" style="display: none;"></span><span id="com"><strong>---</strong>1<strong></strong></span><span id="papel" style="display: none;"></span><span id="com_ant" style="display: none;"></span><span id="papel_ant" style="display: none;"></span><span id="cre_ant" style="display: none;"></span></td><td colspan="2" style="font-size:16px;font-weight:bold;" id="fp_container"></td><td id="usu_venta_td" colspan="" align="left"><i class="uk-icon-user uk-icon-large"></i><select name="vendedor" id="vendedor" style="width:100px" class="uk-form-large"><option value="" selected="selected"></option><option value="Administrador-2222222222" selected="">Administrador</option><option value="ADMINISTRADOR-2222222222">ADMINISTRADOR</option><option value="ANDERSON GARCIA-1049396020">ANDERSON GARCIA</option><option value="BRENDA YASMARY PACHECO-1049395865">BRENDA YASMARY PACHECO</option><option value="FABIO NAVARRO-1018436284">FABIO NAVARRO</option><option value="FLOREIDY RANGEL MONTERREY-1115730377">FLOREIDY RANGEL MONTERREY</option><option value="VENTAS-444444444">VENTAS</option><option value="WILBER VILLAMIZAR-88034526">WILBER VILLAMIZAR</option></select></td><td colspan="2"><div class="uk-form-icon"><i class="uk-icon-calendar uk-icon-small uk-icon-justify"></i><input id="fecha" type="datetime-local" value="2017-11-03T01:23:36" name="fecha" class="uk-form-large" style="width:175px;"></div></td></tr><tr id="pagare" class="uk-hidden" valign="bottom"><td>NOTA ENGREGA:</td><td><input type="text" value="" name="num_pagare" id="num_pagare"></td></tr><tr class="clientes"><td colspan="8">Cliente: <input name="cli_lookup" type="text" id="cli_lookup" value="CLIENTE GENERAL" onkeyup="//get_nom(this,''add'');mover($(this),$(''#cod''),$(this),0);" onblur="busq_cli(this);" class="uk-form-large ac_input" autocomplete="off"><a href="#CREAR_CLI" data-uk-modal="" class="uk-badge uk-badge-success" style="font-size:16px;">REGISTRAR</a></td></tr></tbody></table><div id="CREAR_CLI" class="uk-modal"><div class="uk-modal-dialog"><a class="uk-modal-close uk-close"></a><h1 style="color:#000">REGISTRO CLIENTE</h1><table cellspacing="0" cellpadding="0"><tbody><tr class="clientes"><td>Cliente:</td><td><input name="cli" type="text" id="cli" value="CLIENTE GENERAL" onkeyup="//get_nom(this,''add'');mover($(this),$(''#cod''),$(this),0);" onblur="busq_cli(this);" class="uk-form-small"></td><td>C.C./NIT:</td><td><input name="ced" type="text" value="222222222" id="ced" onchange="busq_cli(this)" class="uk-form-small"></td></tr><tr class="clientes"><td>Dirección:</td><td><input name="dir" type="text" value="" id="dir" class="uk-form-small"></td><td>Ciudad:</td><td><input name="city" type="text" id="city" value="SARAVENA" onchange="valida_doc(''cliente'',this.value);" class="uk-form-small"></td></tr><tr><td>Tel.:</td><td><input name="tel" type="text" value="" id="tel" class="uk-form-small"></td><td>E-mail.:</td><td colspan=""><input name="mail" type="text" value="" id="mail" class="uk-form-small"></td></tr><tr><td colspan="4" align="center"><input type="button" value="Guardar" name="filtro" class="uk-button uk-button-success uk-modal-close " onclick=" "></td></tr></tbody></table></div></div><div id="OPC_FAC" class="uk-modal"><div class="uk-modal-dialog"><a class="uk-modal-close uk-close"></a><h1 style="color:#000">Opciones Adicionales Factura</h1><table cellspacing="0" cellpadding="0" class="uk-table"><tbody><tr><td valign="bottom" colspan=" "> APLICAR PRECIOS: </td><td><input type="button" value="CREDITO" onclick="" class="uk-button uk-button-primary"></td><td><input type="button" value="MAYORISA" onclick="" class="uk-button uk-button-primary"></td></tr><tr><td colspan="">Tipo de Cliente: <select name="tipo_cli" id="tipo_cli" class="uk-form-small"><option value="Mostrador" selected="">Mostrador (Público)</option><option value="Empresas">Empresas (+16%)</option><option value="Otros Talleres">Otros Talleres</option><option value="Mayoristas">Mayoristas</option></select></td></tr><tr class="uk-hidden"><td align="right" colspan="">R. FTE:<input placeholder="%" id="R_FTE_PER" type="text" value="" name="R_FTE_PER" style="width:50px" onkeyup="calc_per($(this),$(''#SUB''),$(''#R_FTE''));" class="save_fc uk-form-small"></td><td colspan="" align="right"><input id="R_FTE" type="text" value="" name="R_FTE" class="save_fc uk-form-small"></td></tr><tr class="uk-hidden"><td align="right" colspan="">R. IVA:<input placeholder="%" id="R_IVA_PER" type="text" value="" name="R_IVA_PER" style="width:50px" onkeyup="calc_per($(this),$(''#IVA''),$(''#R_IVA''));" class="uk-form-small"></td><td colspan="" align="right"><input id="R_IVA" type="text" value="" name="R_IVA" class="save_fc uk-form-small"></td></tr><tr class="uk-hidden"><td align="right" colspan="">R. ICA:<input placeholder="%" id="R_ICA_PER" type="text" value="" name="R_ICA_PER" style="width:50px" onkeyup="calc_per($(this),$(''#SUB''),$(''#R_ICA''));" class="save_fc uk-form-small"></td><td colspan="" align="right"><input id="R_ICA" type="text" value="" name="R_ICA" class="save_fc uk-form-small"></td></tr><tr class="uk-hidden"><td align="right" colspan="">IMP. Consumo<input placeholder="%" id="CONSUMO_PER" type="hidden" value="" name="CONSUMO_PER" style="width:50px" onkeyup="calc_per($(this),$(''#TOT''),$(''#IMP_CONSUMO''));" class="save_fc uk-form-small"></td><td colspan="" align="right"><input id="IMP_CONSUMO" type="text" value="" name="IMP_CONSUMO" class="save_fc uk-form-small"></td></tr></tbody></table></div></div></td></tr><tr class=""><td colspan=""><div class="uk-overflow-container" style=" border-style: double"><table id="articulos" width="100%" cellspacing="0" cellpadding="0" border="1px"><tbody><tr style="background-color: #000; color:#FFF"><td><div align="center"><strong>Referencia</strong></div></td><td><div align="center"><strong>Cod. Barras</strong></div></td><td><div align="center"><strong>Producto</strong></div></td><td><div align="center"><strong>Ubicación</strong></div></td><td><div align="center"><strong>I.V.A</strong></div></td><td height="28"><div align="center"><strong>Cant.</strong></div></td><td height="28"><div align="center"><strong>Fracción</strong></div></td><td><div align="center"><strong>Dcto</strong></div></td><td><div align="center"><strong>Precio</strong></div></td><td colspan="2"><div align="center"><strong>Subtotal</strong></div></td></tr></tbody></table></div></td></tr><tr><td colspan=""><div class="uk-overflow-container" style=""><table id="servicios" width="100%" cellspacing="0" cellpadding="0"><tbody><tr style="background-color: #000; color:#FFF"><th><i class="uk-icon uk-icon-medium uk-icon-wrench"></i>&nbsp;ID</th><th>Codigo</th><th>Servicio</th><th>Nota</th><th>IVA</th><th>PVP</th><th>Total</th><th colspan="3">Técnico</th></tr><tr id="tr_0" class="art0" style="background-color: rgb(255, 255, 255);"><td class="art0" align="center" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_id_serv" value="1" type="text" id="id_serv0" name="id_serv0" placeholder="" onchange="(0)" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="0cant_" type="hidden" name="cant_0" size="5" value="1" style="width: 50px; background-color: rgb(255, 255, 255);" readonly=""><input class="uk-form-small art0" id="0cant_L" type="hidden" name="cant_L0" size="5" maxlength="5" value="100" style="width: 50px; background-color: rgb(255, 255, 255);"></td><td class="art0" align="center" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_cod_serv" value="01" type="text" id="cod_serv0" name="cod_serv0" placeholder="" onchange="(0)" readonly="" style="background-color: rgb(255, 255, 255);"></td><td class="art0" align="center" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_serv" value="PLAN INTERNET 2M HOGAR" type="text" id="serv0" name="serv0" placeholder="" onchange="" readonly="" style="background-color: rgb(255, 255, 255);"></td><td class="art0" style="background-color: rgb(255, 255, 255);"><textarea style="width: 170px; background-color: rgb(255, 255, 255);" cols="10" rows="1" class="art0 col_nota_serv" name="nota0" id="nota0"></textarea></td><td class="art0" align="center" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_iva_serv" value="0" type="text" id="iva_0" name="iva_serv0" placeholder="" onchange="(0)" readonly="" style="background-color: rgb(255, 255, 255);"></td><td class="art0" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_pvp_serv" id="val_u0" name="val_serv0" type="text" onkeyup="puntoa($(this));keepVal(0);valor_t(0);" value="65,000" onblur="change16(0);valMin($(this),0,65,000,0);(0);" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_u0HH" name="val_u0" type="hidden" value="0" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_u0H" name="val_u0H" type="hidden" value="0" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="valMin0" type="hidden" name="valMin0" size="5" maxlength="5" value="undefined" style="width: 30px; background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_orig0" name="val_orig0" type="hidden" value="65" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_origb0" name="val_origb0" type="hidden" value="65" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_cre0H" name="val_cre0H" type="hidden" readonly="" value="65,000" style="background-color: rgb(255, 255, 255);"></td><td class="art0" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0 col_sub_tot_serv" id="val_t0" name="val_tot0" type="text" readonly="" value="0" style="width: 70px; background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_t0HH" name="val_t0" type="hidden" readonly="" value="0" style="background-color: rgb(255, 255, 255);"><input class="uk-form-small art0" id="val_t0H" name="val_t0H" type="hidden" readonly="" value="0" style="background-color: rgb(255, 255, 255);"></td><td class="art0" colspan="2" style="background-color: rgb(255, 255, 255);"><select class="art0 col_tec_serv" id="tec_serv0" name="tec_serv0" onchange="(0)" style="background-color: rgb(255, 255, 255);"><option value=""></option></select></td><td class="art0" style="background-color: rgb(255, 255, 255);"><img onmouseup="eli($(this).prop(''class''))" class="0" src="Imagenes/delete.png" width="31px" heigth="31px"></td></tr></tbody></table></div></td></tr><tr><td colspan="" align="center"><table align=" " frame="box" style="border-width:thick;" cellspacing="0" cellpadding="0" width="90%"><tbody><tr valign="middle" style=" font-size:24px; font-weight:bold;"><td colspan="" align="right"> SERVICIOS <select style="width: 150px; display: none;" name="servicios" id="servicios" onchange="serv($(this),'''',''eli'')" data-placeholder="Escriba un Código Servicio" class="chosen-select"><option value=""></option><option value="1|01|PLAN INTERNET 2M HOGAR|0|65,000">01-PLAN INTERNET 2M HOGAR: 65,000</option><option value="2|02|PLAN INTERNET 2M EMPRESARIAL|19|77,350">02-PLAN INTERNET 2M EMPRESARIAL: 77,350</option><option value="3|03|PLAN INTERNET 4M EMPRESARIAL|19|101,150">03-PLAN INTERNET 4M EMPRESARIAL: 101,150</option><option value="4|04|PLAN INTERNET 4M HOGAR|19|85,000">04-PLAN INTERNET 4M HOGAR: 85,000</option></select><div class="chosen-container chosen-container-single" style="width: 150px;" title="" id="servicios_chosen"><a class="chosen-single" tabindex="-1"><span>01-PLAN INTERNET 2M HOGAR: 65,000</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off"></div><ul class="chosen-results"><li class="active-result result-selected" data-option-array-index="1" style="">01-PLAN INTERNET 2M HOGAR: 65,000</li><li class="active-result" data-option-array-index="2" style="">02-PLAN INTERNET 2M EMPRESARIAL: 77,350</li><li class="active-result" data-option-array-index="3" style="">03-PLAN INTERNET 4M EMPRESARIAL: 101,150</li><li class="active-result" data-option-array-index="4" style="">04-PLAN INTERNET 4M HOGAR: 85,000</li></ul></div></div><div class="uk-form-icon"><i class="uk-icon-database uk-icon-small uk-icon-justify"></i><input type="text" name="cod" value="" id="cod" onkeypress="codx($(this),''add'');" onkeyup="mover($(this),$(''#entrega''),$(this),0);codx($(this),''add'');//mover($(this),$(''#entrega''),$(''#cli''),1);" class="uk-form-large " placeholder="BUSCAR PRODUCTO" style="border-width:4px;border-color:rgb(201, 0, 0);"></div><input type="hidden" value="" id="feVen" name="feVen"><input type="hidden" value="" id="Ref" name="Ref"><img style="cursor:pointer" data-uk-tooltip="" onmouseup="busq($(''#cod''));" src="Imagenes/search128x128.png" width="34" height="31"><div id="Qresp"></div></td><td><input id="butt_gfv" type="button" value="Cerrar Factura" name="boton" onclick="gfv($(this),''genera'',document.forms[''form_fac''])" class=" uk-button uk-button-success uk-button-large "></td></tr><tr class="uk-hiddens"><td colspan="">TOTAL REF: <input type="text" name="exi_ref" value="0" id="exi_ref" style="font-size:24px; font-weight:bold; width:50px;" readonly=""></td><td colspan="">TOTAL CANTIDADES: <input type="text" name="TOT_CANT" value="0" id="TOT_CANT" style="font-size:24px; font-weight:bold; width:50px;" readonly=""></td></tr></tbody></table></td></tr><tr><td colspan=""><table align="right" width="100%"><tbody><tr id="desc"><td colspan="" rowspan="13" align="center" width="100px" class=""><div align="left"><textarea name="vlr_let" id="vlr_let" readonly="readonly" cols="40" style="width:300px;" class="uk-hidden"></textarea><textarea name="nota_fac" id="nota_fac" cols="40" rows="6" placeholder="NOTAS" style="width:200px;-webkit-border-radius:19px;-moz-border-radius:19px;border-radius:19px;border:6px solid rgb(201, 38, 38);"></textarea></div></td><th style="" width="" colspan="" class="uk-hidden">Base IVA:</th><td align="right" colspan="" class="uk-hidden"><input id="SUB" type="text" readonly="" value="0" name="sub" class="uk-form-small"><input type="hidden" name="SUB" value="0" id="SUBH"><input id="EXCENTOS" type="hidden" readonly="" value="65,000" name="exc"><input readonly="" name="dcto" id="DESCUENTO" type="hidden" value="-64,935" onkeyup=""></td></tr><tr><td align="center" colspan="" class="uk-hidden">I.V.A: </td><td align="right" class="uk-hidden"><input name="iva" readonly="readonly" id="IVA" type="text" value="0" class="uk-form-small"><input id="IVAH" type="hidden" name="IVA" value="0"></td></tr><tr class="uk-hidden"><td align="center" colspan="" class="tot_fac">TOTAL (Pesos): <input class="uk-button uk-button-success uk-hidden" data-uk-toggle="{target:''.bsf''}" value="BsF" type="button" onmousedown="//change($(''#entrega''));"></td><td colspan="" align="right"><input id="TOTAL" type="text" readonly="" value="0" name="tot"></td></tr><tr id="bsf" class="uk-hidden bsf" aria-hidden="true"><td align="right" style="font-size:24px;font-family:Georgia,serif;color:rgb(255, 64, 0);font-weight:bold;font-style:italic;">TOTAL (BsF)</td><td align="right"><input id="TOTAL_BSF" type="text" readonly="" value="0" name="totB" class="uk-form-small"></td></tr><tr id="total_pagar_tr"><td align="right" colspan="2" style="font-size:42px;"><b>VALOR A PAGAR: </b><input style="font-size:30px;width:200px;" id="TOTAL_PAGAR" type="text" value="" name="TOTAL_PAGAR" readonly="" class="save_fc uk-form-large"></td></tr><tr id="pago_pesos_tr" class="PAGO_PESOS" style="font-size:18px;font-weight:bold; background-color: #6C6;"><td align="right" colspan=""><div class="uk-grid"><div class="uk-width-5-10"><i class="uk-icon-cc-mastercard uk-icon-large"></i><select id="form_pago" name="form_pago" onchange="creco(this.value,''credito'',''contado'');banDcto();call_tot();" class="uk-text-primary uk-text-bold uk-form-success uk-form-large" style="width:100px;"><option value=""></option><option value="Contado" selected="">Contado</option><option value="Cheque">Cheque</option><option value="Credito">Crédito</option><option value="Tarjeta Credito">Tarjeta Credito</option><option value="Tarjeta Debito">Tarjeta Debito</option><option value="Transferencia Bancaria">Transferencia Bancaria</option><option value="Credito">Credito</option></select></div><div class="uk-width-5-10 uk-hidden"><i class="uk-icon-shopping-bag uk-icon-large"></i><input style="font-size:30px; width:50px; " id="bolsas" type="text" value="" name="bolsas" onkeyup="call_tot();" class="uk-form-large uk-form-success "></div></div></td><td align="right"><div class="uk-grid"><div class="uk-width-4-10" style="font-size:38px;"> PAGO </div><div class="uk-width-6-10"><input style="font-size:30px; width:200px; " id="entrega" type="text" value="" name="entrega" onkeyup="mover($(this),$(''#cod''),$(this),0);change($(this));" onblur="change($(this));" class="uk-form-large uk-form-success "></div></div></td></tr><tr id="pago_tarjetas_pesos_tr" class="PAGO_PESOS2uk-hidden"><td style="font-size:24px;" align="right">Pago Tarjetas</td><td align="right" colspan="" class="PAGO_PESOS2" style="font-size:24px;"><input id="entrega3" type="text" value="" name="entrega3" onkeyup="change($(this));mover($(this),$(''#cod''),$(this),0);" onblur="//change($(this));" data-uk-tooltip="" title="SOLO APLICA PARA PAGO PARCIAL (La otra parte debe ser a acontado)" class="uk-form-large" style="font-size:30px;width:200px; "></td></tr><tr><td></td><td align="right" colspan="" style="font-size:40px;"><b>CAMBIO</b><input style="font-size:30px;width:200px;" id="cambio" type="text" value="0" name="cambio" readonly="readonly" class="uk-form-large uk-text-primary uk-text-bold "><div id="cambio_pesos" style="font-weight: bold; font-size:24px; color:#F00"></div></td></tr><tr class="uk-hidden bsf" aria-hidden="true"><td align="center" colspan="">Pago Efectivo(bsF):</td><td colspan="" align="right"><input id="entrega2" type="text" value="" name="entrega2" onkeyup="mover($(this),$(''#cod''),$(this),0);change($(this));" onblur="change($(this));"></td></tr><tr id="anticipo_bono_tr"><th style=" background-color: #000; color:#FFF;font-size:24px;">Dcto: <input placeholder="%" id="DCTO_PER" type="text" value="" name="DCTO_PER" style="width:50px" onkeyup="calc_per($(this),$(''#SUB''),$(''#DESCUENTO2''));" onblur="dctoB();"><input name="dcto2" id="DESCUENTO2" type="text" value="" onkeyup="dctoB();"></th><input id="anticipo" type="hidden" value="0" name="anticipo" onkeyup="change($(this));" onblur="change($(this));" readonly=""><input type="hidden" name="num_exp" id="num_exp" value="0"></tr></tbody></table></td></tr></tbody></table> ', '1', '2017-11-03', '01:24:02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancos`
--

CREATE TABLE IF NOT EXISTS `bancos` (
  `id_banco` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_banco` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_banco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `bancos`
--

INSERT INTO `bancos` (`id_banco`, `nombre_banco`) VALUES
(1, 'Bancolombia'),
(2, 'Banco de Bogota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancos_cuentas`
--

CREATE TABLE IF NOT EXISTS `bancos_cuentas` (
  `id_cuenta` int(11) NOT NULL AUTO_INCREMENT,
  `id_banco` int(11) NOT NULL,
  `tipo_cuenta` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `num_cuenta` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `saldo_cuenta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_cuenta`),
  KEY `id_banco` (`id_banco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `bancos_cuentas`
--

INSERT INTO `bancos_cuentas` (`id_cuenta`, `id_banco`, `tipo_cuenta`, `num_cuenta`, `saldo_cuenta`) VALUES
(1, 1, 'AHORROS', '0000000001', '5000000.00'),
(2, 1, 'CORRIENTE', '0000000002', '7000000.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE IF NOT EXISTS `cajas` (
  `cod_caja` bigint(20) NOT NULL AUTO_INCREMENT,
  `cod_su` tinyint(4) NOT NULL,
  `fecha` date NOT NULL,
  `estado_caja` varchar(10) NOT NULL,
  PRIMARY KEY (`cod_caja`),
  KEY `cod_su` (`cod_su`),
  KEY `estado_caja` (`estado_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajasb`
--

CREATE TABLE IF NOT EXISTS `cajasb` (
  `cod_caja` bigint(20) NOT NULL,
  `usu` varchar(30) NOT NULL,
  `id_usu` varchar(30) NOT NULL,
  `inicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cierre` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cod_su` tinyint(4) NOT NULL,
  `costos_ini` decimal(24,6) NOT NULL,
  `costos_fin` decimal(24,6) NOT NULL,
  `costo_bruto_ini` decimal(24,6) NOT NULL,
  `costo_bruto_fin` decimal(24,6) NOT NULL,
  KEY `cod_su` (`cod_su`),
  KEY `usu` (`usu`),
  KEY `id_usu` (`id_usu`),
  KEY `inicio` (`inicio`),
  KEY `cierre` (`cierre`),
  KEY `cod_su_2` (`cod_su`),
  KEY `cod_su_3` (`cod_su`),
  KEY `cod_caja` (`cod_caja`),
  KEY `usu_2` (`usu`),
  KEY `id_usu_2` (`id_usu`),
  KEY `cod_su_4` (`cod_su`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
  `calendar_date` date NOT NULL,
  PRIMARY KEY (`calendar_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `calendar`
--

INSERT INTO `calendar` (`calendar_date`) VALUES
('2017-01-31'),
('2017-02-01'),
('2017-02-02'),
('2017-02-03'),
('2017-02-04'),
('2017-02-05'),
('2017-02-06'),
('2017-02-07'),
('2017-02-08'),
('2017-02-09'),
('2017-02-10'),
('2017-02-11'),
('2017-02-12'),
('2017-02-13'),
('2017-02-14'),
('2017-02-15'),
('2017-02-16'),
('2017-02-17'),
('2017-02-18'),
('2017-02-19'),
('2017-02-20'),
('2017-02-21'),
('2017-02-22'),
('2017-02-23'),
('2017-02-24'),
('2017-02-25'),
('2017-02-26'),
('2017-02-27'),
('2017-02-28'),
('2017-03-01'),
('2017-03-02'),
('2017-03-03'),
('2017-03-04'),
('2017-03-05'),
('2017-03-06'),
('2017-03-07'),
('2017-03-08'),
('2017-03-09'),
('2017-03-10'),
('2017-03-11'),
('2017-03-12'),
('2017-03-13'),
('2017-03-14'),
('2017-03-15'),
('2017-03-16'),
('2017-03-17'),
('2017-03-18'),
('2017-03-19'),
('2017-03-20'),
('2017-03-21'),
('2017-03-22'),
('2017-03-23'),
('2017-03-24'),
('2017-03-25'),
('2017-03-26'),
('2017-03-27'),
('2017-03-28'),
('2017-03-29'),
('2017-03-30'),
('2017-03-31'),
('2017-04-01'),
('2017-04-02'),
('2017-04-03'),
('2017-04-04'),
('2017-04-05'),
('2017-04-06'),
('2017-04-07'),
('2017-04-08'),
('2017-04-09'),
('2017-04-10'),
('2017-04-11'),
('2017-04-12'),
('2017-04-13'),
('2017-04-14'),
('2017-04-15'),
('2017-04-16'),
('2017-04-17'),
('2017-04-18'),
('2017-04-19'),
('2017-04-20'),
('2017-04-21'),
('2017-04-22'),
('2017-04-23'),
('2017-04-24'),
('2017-04-25'),
('2017-04-26'),
('2017-04-27'),
('2017-04-28'),
('2017-04-29'),
('2017-04-30'),
('2017-05-01'),
('2017-05-02'),
('2017-05-03'),
('2017-05-04'),
('2017-05-05'),
('2017-05-06'),
('2017-05-07'),
('2017-05-08'),
('2017-05-09'),
('2017-05-10'),
('2017-05-11'),
('2017-05-12'),
('2017-05-13'),
('2017-05-14'),
('2017-05-15'),
('2017-05-16'),
('2017-05-17'),
('2017-05-18'),
('2017-05-19'),
('2017-05-20'),
('2017-05-21'),
('2017-05-22'),
('2017-05-23'),
('2017-05-24'),
('2017-05-25'),
('2017-05-26'),
('2017-05-27'),
('2017-05-28'),
('2017-05-29'),
('2017-05-30'),
('2017-05-31'),
('2017-06-01'),
('2017-06-02'),
('2017-06-03'),
('2017-06-04'),
('2017-06-05'),
('2017-06-06'),
('2017-06-07'),
('2017-06-08'),
('2017-06-09'),
('2017-06-10'),
('2017-06-11'),
('2017-06-12'),
('2017-06-13'),
('2017-06-14'),
('2017-06-15'),
('2017-06-16'),
('2017-06-17'),
('2017-06-18'),
('2017-06-19'),
('2017-06-20'),
('2017-06-21'),
('2017-06-22'),
('2017-06-23'),
('2017-06-24'),
('2017-06-25'),
('2017-06-26'),
('2017-06-27'),
('2017-06-28'),
('2017-06-29'),
('2017-06-30'),
('2017-07-01'),
('2017-07-02'),
('2017-07-03'),
('2017-07-04'),
('2017-07-05'),
('2017-07-06'),
('2017-07-07'),
('2017-07-08'),
('2017-07-09'),
('2017-07-10'),
('2017-07-11'),
('2017-07-12'),
('2017-07-13'),
('2017-07-14'),
('2017-07-15'),
('2017-07-16'),
('2017-07-17'),
('2017-07-18'),
('2017-07-19'),
('2017-07-20'),
('2017-07-21'),
('2017-07-22'),
('2017-07-23'),
('2017-07-24'),
('2017-07-25'),
('2017-07-26'),
('2017-07-27'),
('2017-07-28'),
('2017-07-29'),
('2017-07-30'),
('2017-07-31'),
('2017-08-01'),
('2017-08-02'),
('2017-08-03'),
('2017-08-04'),
('2017-08-05'),
('2017-08-06'),
('2017-08-07'),
('2017-08-08'),
('2017-08-09'),
('2017-08-10'),
('2017-08-11'),
('2017-08-12'),
('2017-08-13'),
('2017-08-14'),
('2017-08-15'),
('2017-08-16'),
('2017-08-17'),
('2017-08-18'),
('2017-08-19'),
('2017-08-20'),
('2017-08-21'),
('2017-08-22'),
('2017-08-23'),
('2017-08-24'),
('2017-08-25'),
('2017-08-26'),
('2017-08-27'),
('2017-08-28'),
('2017-08-29'),
('2017-08-30'),
('2017-08-31'),
('2017-09-01'),
('2017-09-02'),
('2017-09-03'),
('2017-09-04'),
('2017-09-05'),
('2017-09-06'),
('2017-09-07'),
('2017-09-08'),
('2017-09-09'),
('2017-09-10'),
('2017-09-11'),
('2017-09-12'),
('2017-09-13'),
('2017-09-14'),
('2017-09-15'),
('2017-09-16'),
('2017-09-17'),
('2017-09-18'),
('2017-09-19'),
('2017-09-20'),
('2017-09-21'),
('2017-09-22'),
('2017-09-23'),
('2017-09-24'),
('2017-09-25'),
('2017-09-26'),
('2017-09-27'),
('2017-09-28'),
('2017-09-29'),
('2017-09-30'),
('2017-10-01'),
('2017-10-02'),
('2017-10-03'),
('2017-10-04'),
('2017-10-05'),
('2017-10-06'),
('2017-10-07'),
('2017-10-08'),
('2017-10-09'),
('2017-10-10'),
('2017-10-11'),
('2017-10-12'),
('2017-10-13'),
('2017-10-14'),
('2017-10-15'),
('2017-10-16'),
('2017-10-17'),
('2017-10-18'),
('2017-10-19'),
('2017-10-20'),
('2017-10-21'),
('2017-10-22'),
('2017-10-23'),
('2017-10-24'),
('2017-10-25'),
('2017-10-26'),
('2017-10-27'),
('2017-10-28'),
('2017-10-29'),
('2017-10-30'),
('2017-10-31'),
('2017-11-01'),
('2017-11-02'),
('2017-11-03'),
('2017-11-04'),
('2017-11-05'),
('2017-11-06'),
('2017-11-07'),
('2017-11-08'),
('2017-11-09'),
('2017-11-10'),
('2017-11-11'),
('2017-11-12'),
('2017-11-13'),
('2017-11-14'),
('2017-11-15'),
('2017-11-16'),
('2017-11-17'),
('2017-11-18'),
('2017-11-19'),
('2017-11-20'),
('2017-11-21'),
('2017-11-22'),
('2017-11-23'),
('2017-11-24'),
('2017-11-25'),
('2017-11-26'),
('2017-11-27'),
('2017-11-28'),
('2017-11-29'),
('2017-11-30'),
('2017-12-01'),
('2017-12-02'),
('2017-12-03'),
('2017-12-04'),
('2017-12-05'),
('2017-12-06'),
('2017-12-07'),
('2017-12-08'),
('2017-12-09'),
('2017-12-10'),
('2017-12-11'),
('2017-12-12'),
('2017-12-13'),
('2017-12-14'),
('2017-12-15'),
('2017-12-16'),
('2017-12-17'),
('2017-12-18'),
('2017-12-19'),
('2017-12-20'),
('2017-12-21'),
('2017-12-22'),
('2017-12-23'),
('2017-12-24'),
('2017-12-25'),
('2017-12-26'),
('2017-12-27'),
('2017-12-28'),
('2017-12-29'),
('2017-12-30'),
('2017-12-31'),
('2018-01-01'),
('2018-01-02'),
('2018-01-03'),
('2018-01-04'),
('2018-01-05'),
('2018-01-06'),
('2018-01-07'),
('2018-01-08'),
('2018-01-09'),
('2018-01-10'),
('2018-01-11'),
('2018-01-12'),
('2018-01-13'),
('2018-01-14'),
('2018-01-15'),
('2018-01-16'),
('2018-01-17'),
('2018-01-18'),
('2018-01-19'),
('2018-01-20'),
('2018-01-21'),
('2018-01-22'),
('2018-01-23'),
('2018-01-24'),
('2018-01-25'),
('2018-01-26'),
('2018-01-27'),
('2018-01-28'),
('2018-01-29'),
('2018-01-30'),
('2018-01-31'),
('2018-02-01'),
('2018-02-02'),
('2018-02-03'),
('2018-02-04'),
('2018-02-05'),
('2018-02-06'),
('2018-02-07'),
('2018-02-08'),
('2018-02-09'),
('2018-02-10'),
('2018-02-11'),
('2018-02-12'),
('2018-02-13'),
('2018-02-14'),
('2018-02-15'),
('2018-02-16'),
('2018-02-17'),
('2018-02-18'),
('2018-02-19'),
('2018-02-20'),
('2018-02-21'),
('2018-02-22'),
('2018-02-23'),
('2018-02-24'),
('2018-02-25'),
('2018-02-26'),
('2018-02-27'),
('2018-02-28'),
('2018-03-01'),
('2018-03-02'),
('2018-03-03'),
('2018-03-04'),
('2018-03-05'),
('2018-03-06'),
('2018-03-07'),
('2018-03-08'),
('2018-03-09'),
('2018-03-10'),
('2018-03-11'),
('2018-03-12'),
('2018-03-13'),
('2018-03-14'),
('2018-03-15'),
('2018-03-16'),
('2018-03-17'),
('2018-03-18'),
('2018-03-19'),
('2018-03-20'),
('2018-03-21'),
('2018-03-22'),
('2018-03-23'),
('2018-03-24'),
('2018-03-25'),
('2018-03-26'),
('2018-03-27'),
('2018-03-28'),
('2018-03-29'),
('2018-03-30'),
('2018-03-31'),
('2018-04-01'),
('2018-04-02'),
('2018-04-03'),
('2018-04-04'),
('2018-04-05'),
('2018-04-06'),
('2018-04-07'),
('2018-04-08'),
('2018-04-09'),
('2018-04-10'),
('2018-04-11'),
('2018-04-12'),
('2018-04-13'),
('2018-04-14'),
('2018-04-15'),
('2018-04-16'),
('2018-04-17'),
('2018-04-18'),
('2018-04-19'),
('2018-04-20'),
('2018-04-21'),
('2018-04-22'),
('2018-04-23'),
('2018-04-24'),
('2018-04-25'),
('2018-04-26'),
('2018-04-27'),
('2018-04-28'),
('2018-04-29'),
('2018-04-30'),
('2018-05-01'),
('2018-05-02'),
('2018-05-03'),
('2018-05-04'),
('2018-05-05'),
('2018-05-06'),
('2018-05-07'),
('2018-05-08'),
('2018-05-09'),
('2018-05-10'),
('2018-05-11'),
('2018-05-12'),
('2018-05-13'),
('2018-05-14'),
('2018-05-15'),
('2018-05-16'),
('2018-05-17'),
('2018-05-18'),
('2018-05-19'),
('2018-05-20'),
('2018-05-21'),
('2018-05-22'),
('2018-05-23'),
('2018-05-24'),
('2018-05-25'),
('2018-05-26'),
('2018-05-27'),
('2018-05-28'),
('2018-05-29'),
('2018-05-30'),
('2018-05-31'),
('2018-06-01'),
('2018-06-02'),
('2018-06-03'),
('2018-06-04'),
('2018-06-05'),
('2018-06-06'),
('2018-06-07'),
('2018-06-08'),
('2018-06-09'),
('2018-06-10'),
('2018-06-11'),
('2018-06-12'),
('2018-06-13'),
('2018-06-14'),
('2018-06-15'),
('2018-06-16'),
('2018-06-17'),
('2018-06-18'),
('2018-06-19'),
('2018-06-20'),
('2018-06-21'),
('2018-06-22'),
('2018-06-23'),
('2018-06-24'),
('2018-06-25'),
('2018-06-26'),
('2018-06-27'),
('2018-06-28'),
('2018-06-29'),
('2018-06-30'),
('2018-07-01'),
('2018-07-02'),
('2018-07-03'),
('2018-07-04'),
('2018-07-05'),
('2018-07-06'),
('2018-07-07'),
('2018-07-08'),
('2018-07-09'),
('2018-07-10'),
('2018-07-11'),
('2018-07-12'),
('2018-07-13'),
('2018-07-14'),
('2018-07-15'),
('2018-07-16'),
('2018-07-17'),
('2018-07-18'),
('2018-07-19'),
('2018-07-20'),
('2018-07-21'),
('2018-07-22'),
('2018-07-23'),
('2018-07-24'),
('2018-07-25'),
('2018-07-26'),
('2018-07-27'),
('2018-07-28'),
('2018-07-29'),
('2018-07-30'),
('2018-07-31'),
('2018-08-01'),
('2018-08-02'),
('2018-08-03'),
('2018-08-04'),
('2018-08-05'),
('2018-08-06'),
('2018-08-07'),
('2018-08-08'),
('2018-08-09'),
('2018-08-10'),
('2018-08-11'),
('2018-08-12'),
('2018-08-13'),
('2018-08-14'),
('2018-08-15'),
('2018-08-16'),
('2018-08-17'),
('2018-08-18'),
('2018-08-19'),
('2018-08-20'),
('2018-08-21'),
('2018-08-22'),
('2018-08-23'),
('2018-08-24'),
('2018-08-25'),
('2018-08-26'),
('2018-08-27'),
('2018-08-28'),
('2018-08-29'),
('2018-08-30'),
('2018-08-31'),
('2018-09-01'),
('2018-09-02'),
('2018-09-03'),
('2018-09-04'),
('2018-09-05'),
('2018-09-06'),
('2018-09-07'),
('2018-09-08'),
('2018-09-09'),
('2018-09-10'),
('2018-09-11'),
('2018-09-12'),
('2018-09-13'),
('2018-09-14'),
('2018-09-15'),
('2018-09-16'),
('2018-09-17'),
('2018-09-18'),
('2018-09-19'),
('2018-09-20'),
('2018-09-21'),
('2018-09-22'),
('2018-09-23'),
('2018-09-24'),
('2018-09-25'),
('2018-09-26'),
('2018-09-27'),
('2018-09-28'),
('2018-09-29'),
('2018-09-30'),
('2018-10-01'),
('2018-10-02'),
('2018-10-03'),
('2018-10-04'),
('2018-10-05'),
('2018-10-06'),
('2018-10-07'),
('2018-10-08'),
('2018-10-09'),
('2018-10-10'),
('2018-10-11'),
('2018-10-12'),
('2018-10-13'),
('2018-10-14'),
('2018-10-15'),
('2018-10-16'),
('2018-10-17'),
('2018-10-18'),
('2018-10-19'),
('2018-10-20'),
('2018-10-21'),
('2018-10-22'),
('2018-10-23'),
('2018-10-24'),
('2018-10-25'),
('2018-10-26'),
('2018-10-27'),
('2018-10-28'),
('2018-10-29'),
('2018-10-30'),
('2018-10-31'),
('2018-11-01'),
('2018-11-02'),
('2018-11-03'),
('2018-11-04'),
('2018-11-05'),
('2018-11-06'),
('2018-11-07'),
('2018-11-08'),
('2018-11-09'),
('2018-11-10'),
('2018-11-11'),
('2018-11-12'),
('2018-11-13'),
('2018-11-14'),
('2018-11-15'),
('2018-11-16'),
('2018-11-17'),
('2018-11-18'),
('2018-11-19'),
('2018-11-20'),
('2018-11-21'),
('2018-11-22'),
('2018-11-23'),
('2018-11-24'),
('2018-11-25'),
('2018-11-26'),
('2018-11-27'),
('2018-11-28'),
('2018-11-29'),
('2018-11-30'),
('2018-12-01'),
('2018-12-02'),
('2018-12-03'),
('2018-12-04'),
('2018-12-05'),
('2018-12-06'),
('2018-12-07'),
('2018-12-08'),
('2018-12-09'),
('2018-12-10'),
('2018-12-11'),
('2018-12-12'),
('2018-12-13'),
('2018-12-14'),
('2018-12-15'),
('2018-12-16'),
('2018-12-17'),
('2018-12-18'),
('2018-12-19'),
('2018-12-20'),
('2018-12-21'),
('2018-12-22'),
('2018-12-23'),
('2018-12-24'),
('2018-12-25'),
('2018-12-26'),
('2018-12-27'),
('2018-12-28'),
('2018-12-29'),
('2018-12-30'),
('2018-12-31'),
('2019-01-01'),
('2019-01-02'),
('2019-01-03'),
('2019-01-04'),
('2019-01-05'),
('2019-01-06'),
('2019-01-07'),
('2019-01-08'),
('2019-01-09'),
('2019-01-10'),
('2019-01-11'),
('2019-01-12'),
('2019-01-13'),
('2019-01-14'),
('2019-01-15'),
('2019-01-16'),
('2019-01-17'),
('2019-01-18'),
('2019-01-19'),
('2019-01-20'),
('2019-01-21'),
('2019-01-22'),
('2019-01-23'),
('2019-01-24'),
('2019-01-25'),
('2019-01-26'),
('2019-01-27'),
('2019-01-28'),
('2019-01-29'),
('2019-01-30'),
('2019-01-31'),
('2019-02-01'),
('2019-02-02'),
('2019-02-03'),
('2019-02-04'),
('2019-02-05'),
('2019-02-06'),
('2019-02-07'),
('2019-02-08'),
('2019-02-09'),
('2019-02-10'),
('2019-02-11'),
('2019-02-12'),
('2019-02-13'),
('2019-02-14'),
('2019-02-15'),
('2019-02-16'),
('2019-02-17'),
('2019-02-18'),
('2019-02-19'),
('2019-02-20'),
('2019-02-21'),
('2019-02-22'),
('2019-02-23'),
('2019-02-24'),
('2019-02-25'),
('2019-02-26'),
('2019-02-27'),
('2019-02-28'),
('2019-03-01'),
('2019-03-02'),
('2019-03-03'),
('2019-03-04'),
('2019-03-05'),
('2019-03-06'),
('2019-03-07'),
('2019-03-08'),
('2019-03-09'),
('2019-03-10'),
('2019-03-11'),
('2019-03-12'),
('2019-03-13'),
('2019-03-14'),
('2019-03-15'),
('2019-03-16'),
('2019-03-17'),
('2019-03-18'),
('2019-03-19'),
('2019-03-20'),
('2019-03-21'),
('2019-03-22'),
('2019-03-23'),
('2019-03-24'),
('2019-03-25'),
('2019-03-26'),
('2019-03-27'),
('2019-03-28'),
('2019-03-29'),
('2019-03-30'),
('2019-03-31'),
('2019-04-01'),
('2019-04-02'),
('2019-04-03'),
('2019-04-04'),
('2019-04-05'),
('2019-04-06'),
('2019-04-07'),
('2019-04-08'),
('2019-04-09'),
('2019-04-10'),
('2019-04-11'),
('2019-04-12'),
('2019-04-13'),
('2019-04-14'),
('2019-04-15'),
('2019-04-16'),
('2019-04-17'),
('2019-04-18'),
('2019-04-19'),
('2019-04-20'),
('2019-04-21'),
('2019-04-22'),
('2019-04-23'),
('2019-04-24'),
('2019-04-25'),
('2019-04-26'),
('2019-04-27'),
('2019-04-28'),
('2019-04-29'),
('2019-04-30'),
('2019-05-01'),
('2019-05-02'),
('2019-05-03'),
('2019-05-04'),
('2019-05-05'),
('2019-05-06'),
('2019-05-07'),
('2019-05-08'),
('2019-05-09'),
('2019-05-10'),
('2019-05-11'),
('2019-05-12'),
('2019-05-13'),
('2019-05-14'),
('2019-05-15'),
('2019-05-16'),
('2019-05-17'),
('2019-05-18'),
('2019-05-19'),
('2019-05-20'),
('2019-05-21'),
('2019-05-22'),
('2019-05-23'),
('2019-05-24'),
('2019-05-25'),
('2019-05-26'),
('2019-05-27'),
('2019-05-28'),
('2019-05-29'),
('2019-05-30'),
('2019-05-31'),
('2019-06-01'),
('2019-06-02'),
('2019-06-03'),
('2019-06-04'),
('2019-06-05'),
('2019-06-06'),
('2019-06-07'),
('2019-06-08'),
('2019-06-09'),
('2019-06-10'),
('2019-06-11'),
('2019-06-12'),
('2019-06-13'),
('2019-06-14'),
('2019-06-15'),
('2019-06-16'),
('2019-06-17'),
('2019-06-18'),
('2019-06-19'),
('2019-06-20'),
('2019-06-21'),
('2019-06-22'),
('2019-06-23'),
('2019-06-24'),
('2019-06-25'),
('2019-06-26'),
('2019-06-27'),
('2019-06-28'),
('2019-06-29'),
('2019-06-30'),
('2019-07-01'),
('2019-07-02'),
('2019-07-03'),
('2019-07-04'),
('2019-07-05'),
('2019-07-06'),
('2019-07-07'),
('2019-07-08'),
('2019-07-09'),
('2019-07-10'),
('2019-07-11'),
('2019-07-12'),
('2019-07-13'),
('2019-07-14'),
('2019-07-15'),
('2019-07-16'),
('2019-07-17'),
('2019-07-18'),
('2019-07-19'),
('2019-07-20'),
('2019-07-21'),
('2019-07-22'),
('2019-07-23'),
('2019-07-24'),
('2019-07-25'),
('2019-07-26'),
('2019-07-27'),
('2019-07-28'),
('2019-07-29'),
('2019-07-30'),
('2019-07-31'),
('2019-08-01'),
('2019-08-02'),
('2019-08-03'),
('2019-08-04'),
('2019-08-05'),
('2019-08-06'),
('2019-08-07'),
('2019-08-08'),
('2019-08-09'),
('2019-08-10'),
('2019-08-11'),
('2019-08-12'),
('2019-08-13'),
('2019-08-14'),
('2019-08-15'),
('2019-08-16'),
('2019-08-17'),
('2019-08-18'),
('2019-08-19'),
('2019-08-20'),
('2019-08-21'),
('2019-08-22'),
('2019-08-23'),
('2019-08-24'),
('2019-08-25'),
('2019-08-26'),
('2019-08-27'),
('2019-08-28'),
('2019-08-29'),
('2019-08-30'),
('2019-08-31'),
('2019-09-01'),
('2019-09-02'),
('2019-09-03'),
('2019-09-04'),
('2019-09-05'),
('2019-09-06'),
('2019-09-07'),
('2019-09-08'),
('2019-09-09'),
('2019-09-10'),
('2019-09-11'),
('2019-09-12'),
('2019-09-13'),
('2019-09-14'),
('2019-09-15'),
('2019-09-16'),
('2019-09-17'),
('2019-09-18'),
('2019-09-19'),
('2019-09-20'),
('2019-09-21'),
('2019-09-22'),
('2019-09-23'),
('2019-09-24'),
('2019-09-25'),
('2019-09-26'),
('2019-09-27'),
('2019-09-28'),
('2019-09-29'),
('2019-09-30'),
('2019-10-01'),
('2019-10-02'),
('2019-10-03'),
('2019-10-04'),
('2019-10-05'),
('2019-10-06'),
('2019-10-07'),
('2019-10-08'),
('2019-10-09'),
('2019-10-10'),
('2019-10-11'),
('2019-10-12'),
('2019-10-13'),
('2019-10-14'),
('2019-10-15'),
('2019-10-16'),
('2019-10-17'),
('2019-10-18'),
('2019-10-19'),
('2019-10-20'),
('2019-10-21'),
('2019-10-22'),
('2019-10-23'),
('2019-10-24'),
('2019-10-25'),
('2019-10-26'),
('2019-10-27'),
('2019-10-28'),
('2019-10-29'),
('2019-10-30'),
('2019-10-31'),
('2019-11-01'),
('2019-11-02'),
('2019-11-03'),
('2019-11-04'),
('2019-11-05'),
('2019-11-06'),
('2019-11-07'),
('2019-11-08'),
('2019-11-09'),
('2019-11-10'),
('2019-11-11'),
('2019-11-12'),
('2019-11-13'),
('2019-11-14'),
('2019-11-15'),
('2019-11-16'),
('2019-11-17'),
('2019-11-18'),
('2019-11-19'),
('2019-11-20'),
('2019-11-21'),
('2019-11-22'),
('2019-11-23'),
('2019-11-24'),
('2019-11-25'),
('2019-11-26'),
('2019-11-27'),
('2019-11-28'),
('2019-11-29'),
('2019-11-30'),
('2019-12-01'),
('2019-12-02'),
('2019-12-03'),
('2019-12-04'),
('2019-12-05'),
('2019-12-06'),
('2019-12-07'),
('2019-12-08'),
('2019-12-09'),
('2019-12-10'),
('2019-12-11'),
('2019-12-12'),
('2019-12-13'),
('2019-12-14'),
('2019-12-15'),
('2019-12-16'),
('2019-12-17'),
('2019-12-18'),
('2019-12-19'),
('2019-12-20'),
('2019-12-21'),
('2019-12-22'),
('2019-12-23'),
('2019-12-24'),
('2019-12-25'),
('2019-12-26'),
('2019-12-27'),
('2019-12-28'),
('2019-12-29'),
('2019-12-30'),
('2019-12-31'),
('2020-01-01'),
('2020-01-02'),
('2020-01-03'),
('2020-01-04'),
('2020-01-05'),
('2020-01-06'),
('2020-01-07'),
('2020-01-08'),
('2020-01-09'),
('2020-01-10'),
('2020-01-11'),
('2020-01-12'),
('2020-01-13'),
('2020-01-14'),
('2020-01-15'),
('2020-01-16'),
('2020-01-17'),
('2020-01-18'),
('2020-01-19'),
('2020-01-20'),
('2020-01-21'),
('2020-01-22'),
('2020-01-23'),
('2020-01-24'),
('2020-01-25'),
('2020-01-26'),
('2020-01-27'),
('2020-01-28'),
('2020-01-29'),
('2020-01-30'),
('2020-01-31'),
('2020-02-01'),
('2020-02-02'),
('2020-02-03'),
('2020-02-04'),
('2020-02-05'),
('2020-02-06'),
('2020-02-07'),
('2020-02-08'),
('2020-02-09'),
('2020-02-10'),
('2020-02-11'),
('2020-02-12'),
('2020-02-13'),
('2020-02-14'),
('2020-02-15'),
('2020-02-16'),
('2020-02-17'),
('2020-02-18'),
('2020-02-19'),
('2020-02-20'),
('2020-02-21'),
('2020-02-22'),
('2020-02-23'),
('2020-02-24'),
('2020-02-25'),
('2020-02-26'),
('2020-02-27'),
('2020-02-28'),
('2020-02-29'),
('2020-03-01'),
('2020-03-02'),
('2020-03-03'),
('2020-03-04'),
('2020-03-05'),
('2020-03-06'),
('2020-03-07'),
('2020-03-08'),
('2020-03-09'),
('2020-03-10'),
('2020-03-11'),
('2020-03-12'),
('2020-03-13'),
('2020-03-14'),
('2020-03-15'),
('2020-03-16'),
('2020-03-17'),
('2020-03-18'),
('2020-03-19'),
('2020-03-20'),
('2020-03-21'),
('2020-03-22'),
('2020-03-23'),
('2020-03-24'),
('2020-03-25'),
('2020-03-26'),
('2020-03-27'),
('2020-03-28'),
('2020-03-29'),
('2020-03-30'),
('2020-03-31'),
('2020-04-01'),
('2020-04-02'),
('2020-04-03'),
('2020-04-04'),
('2020-04-05'),
('2020-04-06'),
('2020-04-07'),
('2020-04-08'),
('2020-04-09'),
('2020-04-10'),
('2020-04-11'),
('2020-04-12'),
('2020-04-13'),
('2020-04-14'),
('2020-04-15'),
('2020-04-16'),
('2020-04-17'),
('2020-04-18'),
('2020-04-19'),
('2020-04-20'),
('2020-04-21'),
('2020-04-22'),
('2020-04-23'),
('2020-04-24'),
('2020-04-25'),
('2020-04-26'),
('2020-04-27'),
('2020-04-28'),
('2020-04-29'),
('2020-04-30'),
('2020-05-01'),
('2020-05-02'),
('2020-05-03'),
('2020-05-04'),
('2020-05-05'),
('2020-05-06'),
('2020-05-07'),
('2020-05-08'),
('2020-05-09'),
('2020-05-10'),
('2020-05-11'),
('2020-05-12'),
('2020-05-13'),
('2020-05-14'),
('2020-05-15'),
('2020-05-16'),
('2020-05-17'),
('2020-05-18'),
('2020-05-19'),
('2020-05-20'),
('2020-05-21'),
('2020-05-22'),
('2020-05-23'),
('2020-05-24'),
('2020-05-25'),
('2020-05-26'),
('2020-05-27'),
('2020-05-28'),
('2020-05-29'),
('2020-05-30'),
('2020-05-31'),
('2020-06-01'),
('2020-06-02'),
('2020-06-03'),
('2020-06-04'),
('2020-06-05'),
('2020-06-06'),
('2020-06-07'),
('2020-06-08'),
('2020-06-09'),
('2020-06-10'),
('2020-06-11'),
('2020-06-12'),
('2020-06-13'),
('2020-06-14'),
('2020-06-15'),
('2020-06-16'),
('2020-06-17'),
('2020-06-18'),
('2020-06-19'),
('2020-06-20'),
('2020-06-21'),
('2020-06-22'),
('2020-06-23'),
('2020-06-24'),
('2020-06-25'),
('2020-06-26'),
('2020-06-27'),
('2020-06-28'),
('2020-06-29'),
('2020-06-30'),
('2020-07-01'),
('2020-07-02'),
('2020-07-03'),
('2020-07-04'),
('2020-07-05'),
('2020-07-06'),
('2020-07-07'),
('2020-07-08'),
('2020-07-09'),
('2020-07-10'),
('2020-07-11'),
('2020-07-12'),
('2020-07-13'),
('2020-07-14'),
('2020-07-15'),
('2020-07-16'),
('2020-07-17'),
('2020-07-18'),
('2020-07-19'),
('2020-07-20'),
('2020-07-21'),
('2020-07-22'),
('2020-07-23'),
('2020-07-24'),
('2020-07-25'),
('2020-07-26'),
('2020-07-27'),
('2020-07-28'),
('2020-07-29'),
('2020-07-30'),
('2020-07-31'),
('2020-08-01'),
('2020-08-02'),
('2020-08-03'),
('2020-08-04'),
('2020-08-05'),
('2020-08-06'),
('2020-08-07'),
('2020-08-08'),
('2020-08-09'),
('2020-08-10'),
('2020-08-11'),
('2020-08-12'),
('2020-08-13'),
('2020-08-14'),
('2020-08-15'),
('2020-08-16'),
('2020-08-17'),
('2020-08-18'),
('2020-08-19'),
('2020-08-20'),
('2020-08-21'),
('2020-08-22'),
('2020-08-23'),
('2020-08-24'),
('2020-08-25'),
('2020-08-26'),
('2020-08-27'),
('2020-08-28'),
('2020-08-29'),
('2020-08-30'),
('2020-08-31'),
('2020-09-01'),
('2020-09-02'),
('2020-09-03'),
('2020-09-04'),
('2020-09-05'),
('2020-09-06'),
('2020-09-07'),
('2020-09-08'),
('2020-09-09'),
('2020-09-10'),
('2020-09-11'),
('2020-09-12'),
('2020-09-13'),
('2020-09-14'),
('2020-09-15'),
('2020-09-16'),
('2020-09-17'),
('2020-09-18'),
('2020-09-19'),
('2020-09-20'),
('2020-09-21'),
('2020-09-22'),
('2020-09-23'),
('2020-09-24'),
('2020-09-25'),
('2020-09-26'),
('2020-09-27'),
('2020-09-28'),
('2020-09-29'),
('2020-09-30'),
('2020-10-01'),
('2020-10-02'),
('2020-10-03'),
('2020-10-04'),
('2020-10-05'),
('2020-10-06'),
('2020-10-07'),
('2020-10-08'),
('2020-10-09'),
('2020-10-10'),
('2020-10-11'),
('2020-10-12'),
('2020-10-13'),
('2020-10-14'),
('2020-10-15'),
('2020-10-16'),
('2020-10-17'),
('2020-10-18'),
('2020-10-19'),
('2020-10-20'),
('2020-10-21'),
('2020-10-22'),
('2020-10-23'),
('2020-10-24'),
('2020-10-25'),
('2020-10-26'),
('2020-10-27'),
('2020-10-28'),
('2020-10-29'),
('2020-10-30'),
('2020-10-31'),
('2020-11-01'),
('2020-11-02'),
('2020-11-03'),
('2020-11-04'),
('2020-11-05'),
('2020-11-06'),
('2020-11-07'),
('2020-11-08'),
('2020-11-09'),
('2020-11-10'),
('2020-11-11'),
('2020-11-12'),
('2020-11-13'),
('2020-11-14'),
('2020-11-15'),
('2020-11-16'),
('2020-11-17'),
('2020-11-18'),
('2020-11-19'),
('2020-11-20'),
('2020-11-21'),
('2020-11-22'),
('2020-11-23'),
('2020-11-24'),
('2020-11-25'),
('2020-11-26'),
('2020-11-27'),
('2020-11-28'),
('2020-11-29'),
('2020-11-30'),
('2020-12-01'),
('2020-12-02'),
('2020-12-03'),
('2020-12-04'),
('2020-12-05'),
('2020-12-06'),
('2020-12-07'),
('2020-12-08'),
('2020-12-09'),
('2020-12-10'),
('2020-12-11'),
('2020-12-12'),
('2020-12-13'),
('2020-12-14'),
('2020-12-15'),
('2020-12-16'),
('2020-12-17'),
('2020-12-18'),
('2020-12-19'),
('2020-12-20'),
('2020-12-21'),
('2020-12-22'),
('2020-12-23'),
('2020-12-24'),
('2020-12-25'),
('2020-12-26'),
('2020-12-27'),
('2020-12-28'),
('2020-12-29'),
('2020-12-30'),
('2020-12-31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cartera_mult_pago`
--

CREATE TABLE IF NOT EXISTS `cartera_mult_pago` (
  `num_comp` int(11) NOT NULL,
  `num_fac` bigint(20) NOT NULL,
  `pre` varchar(10) CHARACTER SET utf8 NOT NULL,
  `id_cli` varchar(30) CHARACTER SET utf8 NOT NULL,
  `abono` decimal(24,2) NOT NULL,
  `estado` varchar(15) CHARACTER SET utf8 NOT NULL,
  `cod_su` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`num_comp`,`num_fac`,`pre`,`id_cli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE IF NOT EXISTS `clases` (
  `id_clas` bigint(20) NOT NULL AUTO_INCREMENT,
  `des_clas` varchar(100) NOT NULL,
  PRIMARY KEY (`id_clas`),
  UNIQUE KEY `des_clas` (`des_clas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE IF NOT EXISTS `colores` (
  `id_color` bigint(20) NOT NULL AUTO_INCREMENT,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id_color`),
  UNIQUE KEY `color` (`color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante_ingreso`
--

CREATE TABLE IF NOT EXISTS `comprobante_ingreso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_vendedor` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `num_fac` (`num_fac`),
  KEY `cod_su` (`cod_su`),
  KEY `anulado` (`anulado`),
  KEY `num_com` (`num_com`),
  KEY `fecha_cuota` (`fecha_cuota`),
  KEY `forma_pago` (`forma_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_anti`
--

CREATE TABLE IF NOT EXISTS `comp_anti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `num_exp` (`num_exp`),
  KEY `num_com` (`num_com`),
  KEY `estado` (`estado`),
  KEY `cajero` (`cajero`),
  KEY `fecha_anula` (`fecha_anula`),
  KEY `tipo_pago` (`tipo_pago`),
  KEY `tipo_comprobante` (`tipo_comprobante`),
  KEY `tipo_pago_2` (`tipo_pago`),
  KEY `tipo_comprobante_2` (`tipo_comprobante`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `comp_anti`
--

INSERT INTO `comp_anti` (`id`, `num_exp`, `num_com`, `valor`, `fecha`, `cajero`, `concepto`, `cod_su`, `estado`, `fecha_anula`, `tipo_pago`, `tipo_comprobante`, `cod_caja`, `id_banco`, `id_cuenta`) VALUES
(1, 1, 1, '65000.00', '2017-05-30 19:56:55', 'Anderson Garcia', 'abono para bota caterpillar', 1, 'SIN COBRAR', '0000-00-00 00:00:00', 'Contado', 'Anticipo', 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_egreso`
--

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
  UNIQUE KEY `id_2` (`id`),
  KEY `cod_su` (`cod_su`),
  KEY `anulado` (`anulado`),
  KEY `num_com` (`num_com`),
  KEY `tipo_pago` (`tipo_pago`),
  KEY `cajero` (`cajero`),
  KEY `fecha_anula` (`fecha_anula`),
  KEY `serial_fac_com` (`serial_fac_com`),
  KEY `cod_caja` (`cod_caja`),
  KEY `tipo_pago_2` (`tipo_pago`),
  KEY `cajero_2` (`cajero`),
  KEY `fecha_anula_2` (`fecha_anula`),
  KEY `serial_fac_com_2` (`serial_fac_com`),
  KEY `cod_caja_2` (`cod_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_dinero`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `cuentas_dinero`
--

INSERT INTO `cuentas_dinero` (`id_cuenta`, `nom_cta`, `tipo_cta`, `clase_cta`, `cod_cta`, `saldo_cta`, `fechaI_extractos`, `cod_su`) VALUES
(1, 'Caja General', 'Ingresos Ventas', 'Contado', '0001', '7823112.00', '2016-05-03', 1);

--
-- Disparadores `cuentas_dinero`
--
DROP TRIGGER IF EXISTS `set_corte_saldo`;
DELIMITER //
CREATE TRIGGER `set_corte_saldo` AFTER INSERT ON `cuentas_dinero`
 FOR EACH ROW INSERT INTO cuentas_mvtos(id_cuenta,tipo_mov,concepto_mov,clase_mov,monto,fecha_mov) VALUES(NEW.id_cuenta,'SALDO INICIO/CORTE','Saldo Inicial de Cuenta','Saldo Corte',NEW.saldo_cta,NEW.fechaI_extractos)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_mvtos`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dcto_fab`
--

CREATE TABLE IF NOT EXISTS `dcto_fab` (
  `id_cli` varchar(20) NOT NULL,
  `fabricante` varchar(20) NOT NULL,
  `dcto` double(24,20) NOT NULL,
  `usu` varchar(20) NOT NULL,
  `id_usu` varchar(20) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  `fecha_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_dcto` varchar(10) NOT NULL,
  PRIMARY KEY (`id_cli`,`fabricante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `id_dep` bigint(20) NOT NULL AUTO_INCREMENT,
  `departamento` varchar(50) NOT NULL,
  PRIMARY KEY (`id_dep`),
  KEY `departamento` (`departamento`),
  KEY `departamento_2` (`departamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_dep`, `departamento`) VALUES
(1, 'ARAUCA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

CREATE TABLE IF NOT EXISTS `descuentos` (
  `id_cli` varchar(15) NOT NULL,
  `id_pro` varchar(30) NOT NULL,
  `dcto` double(20,16) NOT NULL,
  `fecha_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usu` varchar(15) NOT NULL,
  `id_usu` varchar(15) NOT NULL,
  `cod_su` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_cli`,`id_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exp_anticipo`
--

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
  KEY `prefijo` (`prefijo`),
  KEY `prefijo_2` (`prefijo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `exp_anticipo`
--

INSERT INTO `exp_anticipo` (`id_anti`, `num_exp`, `id_cli`, `nom_cli`, `tel_cli`, `num_fac`, `des`, `fecha`, `cod_su`, `estado`, `cajero`, `usu`, `tot`, `fecha_anula`, `prefijo`, `tot_pa`) VALUES
(1, 1, '1005078014', 'LAURA SILVA', '3202937947', 0, 'abono para bota caterpillar', '2017-05-30 19:57:52', 1, 'ABIERTO', 'Anderson Garcia', '1049396020', '65000.00', '0000-00-00 00:00:00', '', 130000);

--
-- Disparadores `exp_anticipo`
--
DROP TRIGGER IF EXISTS `update_exp`;
DELIMITER //
CREATE TRIGGER `update_exp` BEFORE UPDATE ON `exp_anticipo`
 FOR EACH ROW BEGIN

IF NEW.tot<0 THEN
SET NEW.tot=0;



END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fabricantes`
--

CREATE TABLE IF NOT EXISTS `fabricantes` (
  `id_fab` bigint(20) NOT NULL AUTO_INCREMENT,
  `fabricante` varchar(50) NOT NULL,
  PRIMARY KEY (`id_fab`),
  UNIQUE KEY `fabricante` (`fabricante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_com`
--

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
  `fecha_mod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `serial_tras` bigint(20) NOT NULL,
  `calc_dcto` varchar(5) NOT NULL DEFAULT 'per',
  `perflete` decimal(5,2) NOT NULL,
  `calc_pvp` varchar(20) NOT NULL DEFAULT 'CALCULAR',
  PRIMARY KEY (`num_fac_com`,`nit_pro`,`cod_su`),
  KEY `fk_fac_com` (`cod_su`),
  KEY `tipo_fac` (`tipo_fac`),
  KEY `kardex` (`kardex`),
  KEY `kardex_2` (`kardex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_dev`
--

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
  KEY `kardex` (`kardex`),
  KEY `kardex_2` (`kardex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_dev_venta`
--

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
  `imp_consumo` decimal(24,2) NOT NULL,
  `num_bolsas` smallint(6) NOT NULL,
  `impuesto_bolsas` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_remi`
--

CREATE TABLE IF NOT EXISTS `fac_remi` (
  `serial_fac` int(11) NOT NULL AUTO_INCREMENT,
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
  `sede_destino` int(11) NOT NULL,
  `entrega_bsf` decimal(10,2) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `fecha_recibe` date NOT NULL,
  `nf` bigint(20) NOT NULL,
  `pre` varchar(5) NOT NULL,
  `tot_cre` decimal(20,2) NOT NULL,
  `km` int(11) NOT NULL,
  `tec2` varchar(60) NOT NULL,
  `tec3` varchar(60) NOT NULL,
  `tec4` varchar(60) NOT NULL,
  `tipo_fac` varchar(20) NOT NULL,
  `cod_comision` varchar(20) NOT NULL,
  `tipo_comi` varchar(20) NOT NULL,
  `imp_consumo` decimal(24,2) NOT NULL,
  `num_bolsas` smallint(6) NOT NULL,
  `impuesto_bolsas` int(11) NOT NULL,
  PRIMARY KEY (`id_cli`,`nom_cli`,`tipo_venta`,`tipo_cli`,`fecha`,`vendedor`,`tot`,`anulado`,`fecha_anula`),
  UNIQUE KEY `serial_fac` (`serial_fac`),
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
  KEY `tipo_comi` (`tipo_comi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_venta`
--

CREATE TABLE IF NOT EXISTS `fac_venta` (
  `serial_fac` int(11) NOT NULL AUTO_INCREMENT,
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
  `tot_cre` decimal(20,2) NOT NULL,
  `per_fte` decimal(10,2) NOT NULL,
  `per_iva` decimal(10,2) NOT NULL,
  `per_ica` decimal(10,2) NOT NULL,
  `km` int(11) NOT NULL,
  `tasa_cam` decimal(10,4) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `tot_tarjeta` decimal(10,2) NOT NULL,
  `tec2` varchar(60) NOT NULL,
  `tec3` varchar(60) NOT NULL,
  `tec4` varchar(60) NOT NULL,
  `tipo_fac` varchar(20) NOT NULL,
  `cod_comision` varchar(20) NOT NULL,
  `tipo_comi` varchar(20) NOT NULL,
  `estado_comi` varchar(15) NOT NULL,
  `imp_consumo` decimal(24,2) NOT NULL,
  `num_bolsas` smallint(6) NOT NULL,
  `impuesto_bolsas` int(11) NOT NULL,
  `id_vendedor` varchar(20) NOT NULL,
  `marca_moto` varchar(20) NOT NULL,
  `hash` varchar(150) NOT NULL,
  PRIMARY KEY (`id_cli`,`nom_cli`,`tipo_venta`,`tipo_cli`,`fecha`,`vendedor`,`tot`,`anulado`,`fecha_anula`),
  UNIQUE KEY `serial_fac` (`serial_fac`),
  UNIQUE KEY `hash_2` (`hash`),
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
  KEY `anticipo_bono` (`anticipo_bono`),
  KEY `tipo_comi` (`tipo_comi`),
  KEY `kardex_2` (`kardex`),
  KEY `kardex_3` (`kardex`),
  KEY `id_vendedor` (`id_vendedor`),
  KEY `marca_moto` (`marca_moto`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fac_ven_cambios`
--

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
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_inter`
--

CREATE TABLE IF NOT EXISTS `inv_inter` (
  `serial_inv` int(11) NOT NULL AUTO_INCREMENT,
  `id_pro` varchar(30) NOT NULL,
  `nit_scs` int(11) NOT NULL,
  `id_inter` varchar(30) NOT NULL,
  `exist` decimal(24,0) NOT NULL,
  `max` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `costo` decimal(24,1) NOT NULL,
  `precio_v` decimal(10,2) NOT NULL,
  `fraccion` int(11) NOT NULL DEFAULT '1',
  `gana` decimal(24,2) NOT NULL,
  `tipo_dcto` varchar(15) NOT NULL,
  `iva` varchar(10) NOT NULL DEFAULT 'Aplica',
  `dcto2` decimal(10,2) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(10) NOT NULL,
  `dcto3` decimal(10,2) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `unidades_frac` bigint(20) NOT NULL DEFAULT '0',
  `certificado_importacion` varchar(255) NOT NULL,
  `pvp_credito` decimal(10,2) NOT NULL,
  `marcas` varchar(30) NOT NULL,
  `envase` int(2) NOT NULL,
  `ubicacion` varchar(30) NOT NULL,
  `pvp_may` decimal(24,2) NOT NULL,
  `aplica_vehi` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pro`,`nit_scs`,`id_inter`,`fecha_vencimiento`),
  UNIQUE KEY `serial_inv` (`serial_inv`),
  KEY `nit_scs` (`nit_scs`),
  KEY `id_pro` (`id_pro`),
  KEY `exist` (`exist`),
  KEY `tipo_dcto` (`tipo_dcto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Disparadores `inv_inter`
--
DROP TRIGGER IF EXISTS `update_exist`;
DELIMITER //
CREATE TRIGGER `update_exist` BEFORE UPDATE ON `inv_inter`
 FOR EACH ROW BEGIN
DECLARE tipo_util VARCHAR(50);
SELECT val INTO tipo_util FROM x_config WHERE id_config=1;


IF OLD.fraccion !=0 THEN
WHILE NEW.unidades_frac >= OLD.fraccion DO

IF NEW.unidades_frac>=OLD.fraccion THEN
SET NEW.unidades_frac=NEW.unidades_frac - OLD.fraccion, NEW.exist=NEW.exist+1;
END IF;

END WHILE;

END IF;

WHILE NEW.unidades_frac < 0 DO

IF NEW.unidades_frac < 0 THEN
SET NEW.unidades_frac=NEW.unidades_frac+OLD.fraccion, NEW.exist=NEW.exist-1;
END IF;

END WHILE;



END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_inter2`
--

CREATE TABLE IF NOT EXISTS `inv_inter2` (
  `serial_inv` int(11) NOT NULL AUTO_INCREMENT,
  `id_pro` varchar(30) NOT NULL,
  `nit_scs` int(11) NOT NULL,
  `id_inter` varchar(30) NOT NULL,
  `exist` decimal(24,0) NOT NULL,
  `max` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `costo` decimal(24,1) NOT NULL,
  `precio_v` decimal(10,2) NOT NULL,
  `fraccion` int(11) NOT NULL DEFAULT '1',
  `gana` decimal(24,2) NOT NULL,
  `tipo_dcto` varchar(15) NOT NULL,
  `iva` varchar(10) NOT NULL DEFAULT 'Aplica',
  `dcto2` decimal(10,2) NOT NULL,
  `color` varchar(20) NOT NULL,
  `talla` varchar(10) NOT NULL,
  `dcto3` decimal(10,2) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `unidades_frac` bigint(20) NOT NULL DEFAULT '0',
  `certificado_importacion` varchar(255) NOT NULL,
  `pvp_credito` decimal(10,2) NOT NULL,
  `marcas` varchar(30) NOT NULL,
  `envase` int(2) NOT NULL,
  `ubicacion` varchar(30) NOT NULL,
  `pvp_may` decimal(24,2) NOT NULL,
  `aplica_vehi` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pro`,`nit_scs`,`id_inter`,`fecha_vencimiento`),
  UNIQUE KEY `serial_inv` (`serial_inv`),
  KEY `nit_scs` (`nit_scs`),
  KEY `id_pro` (`id_pro`),
  KEY `exist` (`exist`),
  KEY `tipo_dcto` (`tipo_dcto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE IF NOT EXISTS `municipio` (
  `id_mun` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_dep` bigint(20) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  PRIMARY KEY (`id_mun`),
  KEY `id_dep` (`id_dep`),
  KEY `municipio` (`municipio`),
  KEY `municipio_2` (`municipio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id_mun`, `id_dep`, `municipio`) VALUES
(1, 1, 'ARAUCA'),
(2, 1, 'SARAVENA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_garantia`
--

CREATE TABLE IF NOT EXISTS `orden_garantia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_cli` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'C.C/NIT',
  `cod_garantia` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Tarj. Garantia',
  `id_pro` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'REF. producto',
  `fecha_venta` date NOT NULL COMMENT 'Fecha Venta',
  `id_pro_prestamo` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'REF. prestamo',
  `num_gui` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'No. Guia',
  `transportadora` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Trasportadora',
  `fecha_envio` date NOT NULL COMMENT 'Fecha Envio',
  `fecha` datetime NOT NULL COMMENT 'Fecha',
  `placa_vehi` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Placa',
  `cod_su` int(11) NOT NULL DEFAULT '1',
  `id_inter` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `id_inter2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `id_usu` varchar(30) NOT NULL,
  `id_secc` varchar(30) NOT NULL,
  `permite` varchar(4) NOT NULL,
  PRIMARY KEY (`id_usu`,`id_secc`),
  KEY `permite` (`permite`),
  KEY `id_secc` (`id_secc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_clientes`
--

CREATE TABLE IF NOT EXISTS `planes_clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Sistema',
  `id_cli` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'CC Cliente',
  `id_serv` int(11) NOT NULL COMMENT 'ID Plan',
  `serv` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Plan',
  `estado_plan` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Estado Plan',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE IF NOT EXISTS `presentacion` (
  `presentacion` varchar(30) NOT NULL,
  `pre_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pre_id`),
  UNIQUE KEY `presentacion` (`presentacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`presentacion`, `pre_id`) VALUES
('UNIDAD', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `id_pro` varchar(30) NOT NULL,
  `detalle` varchar(300) NOT NULL,
  `id_clase` varchar(30) NOT NULL,
  `frac` int(11) NOT NULL,
  `fab` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `id_sub_clase` varchar(30) NOT NULL,
  `nit_proveedor` varchar(30) NOT NULL,
  `url_img` varchar(100) NOT NULL,
  `des_full` varchar(100) NOT NULL,
  `serial_pro` int(11) NOT NULL AUTO_INCREMENT,
  UNIQUE KEY `id_pro` (`id_pro`),
  UNIQUE KEY `serial_pro_UNIQUE` (`serial_pro`),
  KEY `detalle` (`detalle`(255)),
  KEY `fab` (`fab`),
  KEY `id_clase` (`id_clase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos2`
--

CREATE TABLE IF NOT EXISTS `productos2` (
  `id_pro` varchar(30) NOT NULL,
  `detalle` varchar(300) NOT NULL,
  `id_clase` varchar(30) NOT NULL,
  `frac` int(11) NOT NULL,
  `fab` varchar(30) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `id_sub_clase` varchar(30) NOT NULL,
  `nit_proveedor` varchar(30) NOT NULL,
  `url_img` varchar(100) NOT NULL,
  `des_full` varchar(100) NOT NULL,
  `serial_pro` int(11) NOT NULL AUTO_INCREMENT,
  UNIQUE KEY `id_pro` (`id_pro`),
  UNIQUE KEY `serial_pro_UNIQUE` (`serial_pro`),
  KEY `detalle` (`detalle`(255)),
  KEY `fab` (`fab`),
  KEY `id_clase` (`id_clase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provedores`
--

CREATE TABLE IF NOT EXISTS `provedores` (
  `nit` varchar(30) NOT NULL DEFAULT '',
  `nom_pro` varchar(100) DEFAULT NULL,
  `dir` varchar(100) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `mail` varchar(30) DEFAULT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`nit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `repetidos_inv`
--
CREATE TABLE IF NOT EXISTS `repetidos_inv` (
`serial_inv` int(11)
,`id_pro` varchar(30)
,`nit_scs` int(11)
,`id_inter` varchar(30)
,`exist` decimal(24,0)
,`max` int(11)
,`min` int(11)
,`costo` decimal(24,1)
,`precio_v` decimal(10,2)
,`fraccion` int(11)
,`gana` decimal(24,2)
,`tipo_dcto` varchar(15)
,`iva` varchar(10)
,`dcto2` decimal(10,2)
,`color` varchar(20)
,`talla` varchar(10)
,`dcto3` decimal(10,2)
,`presentacion` varchar(30)
,`fecha_vencimiento` date
,`unidades_frac` bigint(20)
,`certificado_importacion` varchar(255)
,`pvp_credito` decimal(10,2)
,`marcas` varchar(30)
,`ubicacion` varchar(30)
,`envase` int(2)
,`aplica_vehi` varchar(100)
,`pvp_may` decimal(24,2)
,`n` bigint(21)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rotacion_inv`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE IF NOT EXISTS `secciones` (
  `id_secc` varchar(30) NOT NULL,
  `des_secc` varchar(50) NOT NULL,
  `modulo` varchar(30) NOT NULL,
  `habilita` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_secc`),
  KEY `des_secc` (`des_secc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_secc`, `des_secc`, `modulo`, `habilita`) VALUES
('', 'Arqueos e Informes', '', 1),
('adm_art', 'Administrar Ventas Repuestos', 'Ventas', 1),
('adm_serv', 'Administrar Servicios', 'Ventas', 1),
('anular_comp_anticipo', 'Anular Comprobante Anticipo', 'Ventas', 1),
('anular_comp_ingreso', 'Anular Comprobante de Ingreso', 'Cartera Clientes', 1),
('anula_comp_egreso', 'Anular Comprobante de Egreso', 'Egresos', 1),
('anula_tras', 'Anular Traslados', 'Traslados', 1),
('arqueos_informes', 'Arqueos e Informes', 'Informes', 1),
('caja_centro', 'Manejo de Caja', 'Ventas', 1),
('clientes', 'Lista de Clientes', 'Clientes y Usuarios', 1),
('clientes_add', 'Crear Clientes', 'Clientes y Usuarios', 1),
('clientes_eli', 'Eliminar Clientes', 'Clientes y Usuarios', 1),
('clientes_mod', 'Modificar Clientes', 'Clientes y Usuarios', 1),
('compras', 'Lista de Facturas de Compra', 'Compras', 1),
('compras_add', 'Crear Compras', 'Compras', 1),
('compras_mod', 'Modificar Compras', 'Compras', 1),
('cotizacion', 'Cotizaciones', 'Ventas', 1),
('crear_anticipo', 'Crear Anticipos/Abonos', 'Ventas', 1),
('crear_comp_egreso', 'Crear Comprobante de Egreso', 'Egresos', 1),
('crear_garantias', 'Autorizar Garantias Productos', 'Manejo de Inventario', 1),
('crear_nota_credito', 'Crear Notas Credito (Devoluciones de Compras)', 'Compras', 1),
('crea_recibo_caja', 'Crear Recibos Caja (Cartera)', 'Cartera Clientes', 1),
('creditos_empleados', 'Créditos Empleados', 'Cartera Clientes', 1),
('creditos_publico', 'Créditos - Publico', 'Cartera Clientes', 1),
('dcto_indi', 'Descuentos por Producto Individual', 'Ventas', 1),
('dcto_per', 'Descuento Fac. Venta (porcentaje)', 'Ventas', 1),
('del_serv', 'ELIMINAR SERVICIOS', 'Ventas', 1),
('descuento_fac', 'Descuentos en Facturacion (Precio Libre)', 'Ventas', 1),
('dev_ventas', 'Devoluciones', 'Ventas', 1),
('envia_tras', 'Enviar Traslado', 'Traslados', 1),
('fac_anula', 'Anular Facturas de Venta', 'Ventas', 1),
('fac_com_anula', 'Anular Factura de Compra', 'Compras', 1),
('fac_crea', 'Crear Facturas de Venta', 'Ventas', 1),
('fac_lista', 'Lista Facturas de Venta', 'Ventas', 1),
('fac_mod', 'MODIFICAR Facturas de Venta', 'Ventas', 1),
('fac_serv', 'Facturar Servicios', 'Ventas', 1),
('informes_kardex', 'Kardex de Inventario', 'Informes', 1),
('informes_kardex_fecha', 'Kardex por Fecha', 'Informes', 1),
('informes_listados', 'Informes - Listados ', 'Informes', 1),
('informes_nomina', 'Informe Nomina', 'Informes', 1),
('informes_nomina_tot', 'Informe Nomina Total Sedes', 'Informes', 1),
('informes_ventas_cli', 'Informe de Ventas por Cliente', 'Informes', 1),
('inventario', 'Ver Inventario', 'Manejo de Inventario', 1),
('inventario_add', 'Crear Producto Nuevo', 'Manejo de Inventario', 1),
('inventario_ajustes', 'Ajustes de inventario', 'Manejo de Inventario', 1),
('inventario_mod', 'Modificar Datos Productos', 'Manejo de Inventario', 1),
('inventario_on_off', 'Modificar Cantidades y Kardex de Productos', 'Manejo de Inventario', 1),
('lista_comp_egreso', 'Listado Egresos', 'Egresos', 1),
('mod_comp_egreso', 'Modificar Comprobante de Egreso', 'Egresos', 1),
('mod_ingre_vehi', 'Modificar Ingreso Carros', 'Ventas', 1),
('mod_serv', 'Modificar Servicios', 'Ventas', 1),
('mod_tras', 'Modificar Traslados', 'Traslados', 1),
('recibe_tras', 'Recibir Traslado', 'Traslados', 1),
('remi_crea', 'Crear Remisiones', 'Ventas', 1),
('remi_lista', 'Lista Remisiones', 'Ventas', 1),
('usuarios', 'Lista de Usuarios de Sistema', 'Clientes y Usuarios', 1),
('usuarios_add', 'Otorgar Permisos a Usuarios', 'Clientes y Usuarios', 1),
('usu_lim_fac', 'Establecer Limites en Facturacion y Remisiones', 'Clientes y Usuarios', 1),
('vende_credito', 'Vender a Credito', 'Ventas', 1),
('ver_ingre_vehi', 'Ver Ingresos Carros', 'Ventas', 1),
('ver_nota_credito', 'Ver Crear Notas Credito', 'Compras', 1),
('ver_sedes', 'Ver otras sedes', 'Traslados', 1),
('ver_tot_compras', 'Ver costos Totales en Compras', 'Compras', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriales`
--

CREATE TABLE IF NOT EXISTS `seriales` (
  `id_serial` bigint(20) NOT NULL AUTO_INCREMENT,
  `seccion` varchar(30) NOT NULL,
  `serial_inf` bigint(20) NOT NULL,
  `serial_sup` bigint(20) NOT NULL,
  `nit_sede` int(11) NOT NULL,
  PRIMARY KEY (`id_serial`),
  UNIQUE KEY `seccion_2` (`seccion`,`nit_sede`),
  KEY `nit_sede` (`nit_sede`),
  KEY `seccion` (`seccion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=137 ;

--
-- Volcado de datos para la tabla `seriales`
--

INSERT INTO `seriales` (`id_serial`, `seccion`, `serial_inf`, `serial_sup`, `nit_sede`) VALUES
(2, 'comprobante ingreso', 1, 10000, 1),
(4, 'factura taller', 1, 10000, 1),
(9, 'factura compra', 1, 40000, 1),
(11, 'factura dev', 1, 10000000, 1),
(12, 'credito', 1, 20000, 1),
(13, 'traslado', 1, 100000, 1),
(104, 'expedientes', 1, 100000, 1),
(105, 'comprobante anticipo', 1, 100000, 1),
(112, 'ajustes', 1, 10000, 1),
(113, 'Inventario Inicial', 1, 10000, 1),
(114, 'ref', 1, 1000000000, 1),
(117, 'resol_papel', 322, 1000, 1),
(122, 'fac_dev_ven', 1, 50000, 1),
(124, 'comp_egreso', 1, 10000, 1),
(128, 'remision', 1, 100000, 2),
(130, 'remision_com', 1, 100000, 2),
(132, 'cartera_ant', 1, 10000, 1),
(133, 'factura venta', 1, 10000, 1),
(134, 'remision_com2', 1, 100000, 1),
(135, 'remision_com2', 1, 100000, 2),
(136, 'remision_com2', 1, 100000, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriales_arqueos`
--

CREATE TABLE IF NOT EXISTS `seriales_arqueos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fechaI` date NOT NULL,
  `fechaF` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriales_inv`
--

CREATE TABLE IF NOT EXISTS `seriales_inv` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `min` bigint(20) NOT NULL DEFAULT '1',
  `max` bigint(20) NOT NULL DEFAULT '1000',
  `current` bigint(20) NOT NULL DEFAULT '1',
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `seriales_inv`
--

INSERT INTO `seriales_inv` (`id`, `label`, `min`, `max`, `current`, `cod_su`) VALUES
(1, 'GENERAL', 1, 1000, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE IF NOT EXISTS `servicios` (
  `id_serv` int(11) NOT NULL AUTO_INCREMENT,
  `servicio` varchar(50) CHARACTER SET utf8 NOT NULL,
  `des_serv` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `km_revisa` decimal(10,1) NOT NULL,
  `cod_su` int(4) NOT NULL,
  `cod_serv` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_serv`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_serv`, `servicio`, `des_serv`, `iva`, `pvp`, `km_revisa`, `cod_su`, `cod_serv`) VALUES
(1, 'PLAN INTERNET 2M HOGAR', '', 0, '65000.00', '0.0', 1, '01'),
(2, 'PLAN INTERNET 2M EMPRESARIAL', '', 19, '77350.00', '0.0', 1, '02'),
(3, 'PLAN INTERNET 4M EMPRESARIAL', '', 19, '101150.00', '0.0', 1, '03'),
(4, 'PLAN INTERNET 4M HOGAR', '', 19, '85000.00', '0.0', 1, '04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serv_fac_remi`
--

CREATE TABLE IF NOT EXISTS `serv_fac_remi` (
  `num_fac_ven` bigint(20) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `id_serv` int(11) NOT NULL,
  `serv` varchar(30) NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `cod_serv` varchar(20) NOT NULL,
  `cod_su` int(4) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `id_tec` varchar(20) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`prefijo`,`id_serv`,`cod_su`),
  KEY `serv` (`serv`),
  KEY `cod_serv` (`cod_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serv_fac_ven`
--

CREATE TABLE IF NOT EXISTS `serv_fac_ven` (
  `num_fac_ven` bigint(20) NOT NULL,
  `prefijo` varchar(5) NOT NULL,
  `id_serv` int(11) NOT NULL,
  `serv` varchar(40) NOT NULL,
  `iva` int(11) NOT NULL,
  `pvp` decimal(20,2) NOT NULL,
  `cod_serv` varchar(20) NOT NULL,
  `cod_su` int(4) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `id_tec` varchar(20) NOT NULL,
  `hash` varchar(150) NOT NULL,
  PRIMARY KEY (`num_fac_ven`,`prefijo`,`id_serv`,`cod_su`),
  KEY `serv` (`serv`),
  KEY `cod_serv` (`cod_serv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `serv_fac_ven`
--

INSERT INTO `serv_fac_ven` (`num_fac_ven`, `prefijo`, `id_serv`, `serv`, `iva`, `pvp`, `cod_serv`, `cod_su`, `nota`, `id_tec`, `hash`) VALUES
(1, '---', 1, 'PLAN INTERNET 2M HOGAR', 0, '65000.00', '01', 1, '', '', '192.168.100.5 or perhaps 10.3.0.1 or perhaps 192.168.56.1 / 2017-11-03 01:23:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_clase`
--

CREATE TABLE IF NOT EXISTS `sub_clase` (
  `id_sub_clase` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_clase` bigint(20) NOT NULL,
  `des_sub_clase` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sub_clase`),
  UNIQUE KEY `des_sub_clase` (`des_sub_clase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sub_clase`
--

INSERT INTO `sub_clase` (`id_sub_clase`, `id_clase`, `des_sub_clase`) VALUES
(1, 158, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE IF NOT EXISTS `sucursal` (
  `cod_su` int(11) NOT NULL,
  `nombre_su` varchar(50) NOT NULL,
  `dir_su` varchar(100) NOT NULL,
  `tel1` varchar(60) NOT NULL,
  `tel2` varchar(30) NOT NULL,
  `email_su` varchar(50) NOT NULL,
  `representante_se` varchar(100) NOT NULL,
  `id_dep` bigint(20) NOT NULL,
  `id_mun` bigint(20) NOT NULL,
  `alas` int(11) NOT NULL,
  `cod_contado` varchar(5) NOT NULL,
  `cod_credito` varchar(5) NOT NULL,
  `cod_papel` varchar(5) NOT NULL,
  `resol_contado` varchar(20) NOT NULL,
  `fecha_resol_contado` date NOT NULL,
  `resol_credito` bigint(20) NOT NULL,
  `fecha_resol_credito` date NOT NULL,
  `rango_contado` varchar(30) NOT NULL,
  `rango_credito` varchar(30) NOT NULL,
  `resol_papel` varchar(20) NOT NULL,
  `fecha_resol_papel` date NOT NULL,
  `rango_papel` varchar(30) NOT NULL,
  `precio_bsf` decimal(10,6) NOT NULL,
  `resol_credito_ant` varchar(30) NOT NULL,
  `fecha_resol_credito_ant` date NOT NULL,
  `rango_credito_ant` varchar(30) NOT NULL,
  `cod_credito_ant` varchar(5) NOT NULL,
  `cod_remi_pos` varchar(5) NOT NULL DEFAULT 'REMI',
  `resol_remi_pos` varchar(30) NOT NULL DEFAULT '1000100',
  `fecha_remi_pos` date NOT NULL,
  `rango_remi_pos` varchar(30) NOT NULL DEFAULT '(1 - 100000)',
  `cod_remi_com` varchar(5) NOT NULL DEFAULT 'RCOM',
  `resol_remi_com` varchar(30) NOT NULL DEFAULT '1000200',
  `fecha_remi_com` date NOT NULL,
  `rango_remi_com` varchar(30) NOT NULL DEFAULT '(1 - 100000)',
  `cod_remi_com2` varchar(5) NOT NULL,
  `resol_remi_com2` varchar(30) NOT NULL,
  `fecha_remi_com2` date NOT NULL,
  `rango_remi_com2` varchar(20) NOT NULL,
  `id_responsable` varchar(30) NOT NULL,
  `placa_vehiculo` varchar(10) NOT NULL,
  `licencia_key` varchar(40) NOT NULL,
  `nom_negocio` varchar(50) NOT NULL,
  `nit_negocio` varchar(70) NOT NULL,
  PRIMARY KEY (`cod_su`),
  KEY `id_dep` (`id_dep`),
  KEY `id_mun` (`id_mun`),
  KEY `cod_contado` (`cod_contado`),
  KEY `cod_credito` (`cod_credito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`cod_su`, `nombre_su`, `dir_su`, `tel1`, `tel2`, `email_su`, `representante_se`, `id_dep`, `id_mun`, `alas`, `cod_contado`, `cod_credito`, `cod_papel`, `resol_contado`, `fecha_resol_contado`, `resol_credito`, `fecha_resol_credito`, `rango_contado`, `rango_credito`, `resol_papel`, `fecha_resol_papel`, `rango_papel`, `precio_bsf`, `resol_credito_ant`, `fecha_resol_credito_ant`, `rango_credito_ant`, `cod_credito_ant`, `cod_remi_pos`, `resol_remi_pos`, `fecha_remi_pos`, `rango_remi_pos`, `cod_remi_com`, `resol_remi_com`, `fecha_remi_com`, `rango_remi_com`, `cod_remi_com2`, `resol_remi_com2`, `fecha_remi_com2`, `rango_remi_com2`, `id_responsable`, `placa_vehiculo`, `licencia_key`, `nom_negocio`, `nit_negocio`) VALUES
(1, 'SMART SELLING', '', '', '', '', ' ', 1, 2, 0, 'POS', '---', '----', '18762002416203', '2017-03-01', 18762002398631, '2017-02-28', '(1 - 2000)', '(1 - 2000)', '340000010524', '2016-05-07', '(0001 - 1000)', '4.500000', '11001000', '2015-01-01', '(1 - 10000)', 'CRE', 'REMI', '1000100', '0000-00-00', '(1 - 100000)', 'RCOM', '1000200', '0000-00-00', '(1 - 100000)', 'RE2', '18000002', '2017-07-10', '(1 - 100.000)', '', '', '52555-555', 'WIRPAT LTDA.', '900970501-4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE IF NOT EXISTS `tallas` (
  `id_talla` bigint(20) NOT NULL AUTO_INCREMENT,
  `talla` varchar(10) NOT NULL,
  PRIMARY KEY (`id_talla`),
  UNIQUE KEY `talla` (`talla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usu`
--

CREATE TABLE IF NOT EXISTS `tipo_usu` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `des` varchar(30) NOT NULL,
  `id_usu` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usu` (`id_usu`),
  KEY `des` (`des`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Volcado de datos para la tabla `tipo_usu`
--

INSERT INTO `tipo_usu` (`id`, `des`, `id_usu`) VALUES
(45, 'Administrador', '2222222222');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslados`
--

CREATE TABLE IF NOT EXISTS `traslados` (
  `cod_tras` bigint(20) NOT NULL,
  `cod_su` int(11) NOT NULL,
  `cod_su_dest` int(11) NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_recibo` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `estado` varchar(30) NOT NULL,
  `subtot` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `flete` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `tot` decimal(10,2) NOT NULL,
  `nota_tras` text NOT NULL,
  PRIMARY KEY (`cod_tras`,`cod_su`),
  KEY `cod_su` (`cod_su`),
  KEY `cod_su_dest` (`cod_su_dest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usu` varchar(30) CHARACTER SET utf8 NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dir` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tel` varchar(30) CHARACTER SET utf8 NOT NULL,
  `cuidad` varchar(30) CHARACTER SET utf8 NOT NULL,
  `cod_su` int(11) NOT NULL,
  `cliente` tinyint(4) NOT NULL DEFAULT '1',
  `mail_cli` varchar(30) CHARACTER SET utf8 NOT NULL,
  `fe_naci` date NOT NULL,
  `sim` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_crea` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cod_caja` int(11) NOT NULL,
  `chofer` int(4) NOT NULL DEFAULT '0',
  `tope_credito` bigint(20) NOT NULL,
  `auth_credito` int(2) NOT NULL DEFAULT '1',
  `tipo_usu` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT 'Particular',
  `fecha_ban` date NOT NULL,
  `monto_ban` decimal(24,2) NOT NULL,
  `fecha_ban_remi` date NOT NULL,
  `monto_ban_remi` decimal(24,2) NOT NULL,
  `cod_comision` varchar(20) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(20) CHARACTER SET utf8 NOT NULL,
  `nomina_1` tinyint(4) NOT NULL,
  `estrato` smallint(6) NOT NULL,
  PRIMARY KEY (`id_usu`,`nombre`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `cliente` (`cliente`),
  KEY `cod_su` (`cod_su`),
  KEY `nombre` (`nombre`),
  KEY `dir` (`dir`),
  KEY `cuidad` (`cuidad`),
  KEY `mail_cli` (`mail_cli`),
  KEY `sim` (`sim`),
  KEY `nomina_1` (`nomina_1`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=498 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `id_usu`, `nombre`, `dir`, `tel`, `cuidad`, `cod_su`, `cliente`, `mail_cli`, `fe_naci`, `sim`, `fecha_crea`, `cod_caja`, `chofer`, `tope_credito`, `auth_credito`, `tipo_usu`, `fecha_ban`, `monto_ban`, `fecha_ban_remi`, `monto_ban_remi`, `cod_comision`, `alias`, `nomina_1`, `estrato`) VALUES
(497, '2222222222', 'ADMINISTRADOR', '------------', '3208109739', 'ARAUCA', 1, 0, '---------', '0000-00-00', '', '2014-07-01 05:00:00', 0, 0, 0, 1, 'Particular', '0000-00-00', '0.00', '0000-00-00', '0.00', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usu_login`
--

CREATE TABLE IF NOT EXISTS `usu_login` (
  `id_usu` varchar(30) NOT NULL,
  `usu` varchar(30) NOT NULL,
  `cla` varchar(30) NOT NULL,
  `rol_lv` tinyint(4) NOT NULL,
  `estado` varchar(10) NOT NULL DEFAULT 'ACTIVO',
  PRIMARY KEY (`usu`),
  KEY `id_usu` (`id_usu`),
  KEY `cla` (`cla`),
  KEY `cla_2` (`cla`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usu_login`
--

INSERT INTO `usu_login` (`id_usu`, `usu`, `cla`, `rol_lv`, `estado`) VALUES
('2222222222', 'admin', '123', 10, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE IF NOT EXISTS `vehiculo` (
  `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Interno',
  `placa` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Placa',
  `modelo` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Modelo',
  `cc` decimal(10,1) NOT NULL COMMENT 'Cilindrada Motor',
  `color` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Color',
  `km` int(11) NOT NULL COMMENT 'Recorrido (Km)',
  `cod_su` int(4) NOT NULL,
  `id_propietario` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'C.C Propietario',
  `marca` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Marca',
  PRIMARY KEY (`id_vehiculo`),
  UNIQUE KEY `placa` (`placa`),
  KEY `modelo` (`modelo`,`cod_su`),
  KEY `id_propietario` (`id_propietario`),
  KEY `color` (`color`),
  KEY `cc` (`cc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `ventas_x_pro`
--
CREATE TABLE IF NOT EXISTS `ventas_x_pro` (
`nit` tinyint(4)
,`ref` varchar(30)
,`cod_barras` varchar(30)
,`prefijo` varchar(5)
,`num_fac_ven` bigint(20)
,`des` varchar(100)
,`iva` int(11)
,`precio` double(24,2)
,`sub_tot` double(24,2)
,`fab` varchar(30)
,`cant` decimal(20,2)
,`fraccion` bigint(20)
,`unidades_fraccion` bigint(20)
,`dcto` double(24,2)
,`costo` decimal(24,2)
,`id_clase` varchar(30)
,`id_sub_clase` varchar(30)
,`nit_proveedor` varchar(30)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_arqueo_pro`
--
CREATE TABLE IF NOT EXISTS `vista_arqueo_pro` (
`fecha` datetime
,`fecha_anula` datetime
,`anulado` varchar(30)
,`nit` tinyint(4)
,`anticipo_bono` varchar(15)
,`tot_tarjeta` decimal(10,2)
,`entrega_bsf` decimal(10,2)
,`num_fac_ven` bigint(20)
,`precio` double(24,2)
,`des` varchar(100)
,`sub_tot` double(24,2)
,`iva` int(11)
,`cant` decimal(20,2)
,`ref` varchar(30)
,`hora` time
,`fe` date
,`tipo_venta` varchar(30)
,`tipo_cli` varchar(30)
,`vendedor` varchar(60)
,`prefijo` varchar(5)
,`tot_bsf` decimal(10,2)
,`cod_caja` int(11)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_inventario`
--
CREATE TABLE IF NOT EXISTS `vista_inventario` (
`id_glo` varchar(30)
,`id_sede` varchar(30)
,`detalle` varchar(300)
,`id_clase` varchar(30)
,`fraccion` int(11)
,`fab` varchar(30)
,`max` int(11)
,`min` int(11)
,`costo` decimal(24,1)
,`precio_v` decimal(10,2)
,`exist` decimal(24,0)
,`iva` varchar(10)
,`gana` decimal(24,2)
,`nit_scs` int(11)
,`presentacion` varchar(30)
,`nit_proveedor` varchar(30)
,`id_sub_clase` varchar(30)
,`color` varchar(20)
,`talla` varchar(10)
,`ubicacion` varchar(30)
,`aplica_vehi` varchar(100)
,`pvp_may` decimal(24,2)
,`pvp_credito` decimal(10,2)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_resoluciones_dian`
--
CREATE TABLE IF NOT EXISTS `vista_resoluciones_dian` (
`prefijo` varchar(5)
,`resolucion` varchar(30)
,`fecha_resol` date
,`rango_resol` varchar(30)
,`nit` tinyint(4)
,`lastN` bigint(20)
);
-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ventas_rotacion`
--
CREATE TABLE IF NOT EXISTS `vista_ventas_rotacion` (
`num_fac_ven` bigint(20)
,`preArt` varchar(5)
,`preFac` varchar(5)
,`ref` varchar(30)
,`cod_barras` varchar(30)
,`des` varchar(100)
,`cant` decimal(20,2)
,`costo` decimal(24,2)
,`fecha` datetime
,`anulado` varchar(30)
,`facNit` tinyint(4)
,`artNit` tinyint(4)
,`tot_dia` decimal(42,2)
,`prom_dia` decimal(24,6)
,`min` decimal(20,2)
,`max` decimal(20,2)
);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `x_config`
--

CREATE TABLE IF NOT EXISTS `x_config` (
  `id_config` int(11) NOT NULL AUTO_INCREMENT,
  `des_config` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `val` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `des_config` (`des_config`,`val`,`cod_su`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=128 ;

--
-- Volcado de datos para la tabla `x_config`
--

INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`) VALUES
(29, '_descuentosFabricante', '0', 1),
(58, 'Adminlvl', '10', 1),
(96, 'ALIAS_CLI', '0', 1),
(69, 'ANTICIPOS', '1', 1),
(94, 'APLICA_VEHI', '0', 1),
(106, 'ARQ_VEN_RESOL', '0', 1),
(121, 'ARQUEOS', '1', 1),
(80, 'AUTO_BAN_CLI', '0', 1),
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
(99, 'COD_GARANTIA', '0', 1),
(93, 'COMI_VENTAS', '0', 1),
(118, 'COMPRAS', '1', 1),
(66, 'confirmar_tras', 'manual', 1),
(77, 'COTIZACIONES', '0', 1),
(47, 'cross_fac', '0', 1),
(83, 'CUENTAS_BANCOS', '0', 1),
(87, 'CUENTAS_PAGAR', '1', 1),
(95, 'DES_FULL', '0', 1),
(119, 'DEVOLUCIONES', '1', 1),
(33, 'dias_anula_comps', '500', 1),
(89, 'EGRESOS_2', '1', 1),
(125, 'fecha_corte_cuentas_pagar', '', 1),
(34, 'fecha_lim_anulaCompra', '', 1),
(35, 'fecha_lim_anulaVenta', 'AND ( MONTH(fecha)=MONTH(NOW()) AND YEAR(fecha)=YEAR(NOW()) )', 1),
(74, 'FLUJO_KARDEX', '0', 1),
(117, 'formatos_peluqueria', '0', 1),
(73, 'GASTOS', '1', 1),
(28, 'global_text_fabricante', 'Marca', 1),
(12, 'horaApertura', '00:00:00 am', 1),
(13, 'horaCierre', '19:29:59 pm', 1),
(101, 'IGLESIAS', '0', 1),
(113, 'IMG_PRODUCTO', '0', 1),
(24, 'imp_solo_pos', '1', 1),
(65, 'impCompra', 'A', 1),
(45, 'impFacVen_mini', '1', 1),
(100, 'IMPORT_CSV', '0', 1),
(116, 'impuesto_bolsas', '0', 1),
(114, 'impuesto_consumo', '0', 1),
(39, 'ImpURLcomprobantesCOM', 'imp_comp_ingre.php', 1),
(38, 'ImpURLcomprobantesPOS', 'imp_comp_ingre_pos.php', 1),
(40, 'ImpURLcontado', 'imp_fac_ven.php', 1),
(41, 'ImpURLcredito', 'imp_fac_ven_cre.php', 1),
(90, 'INFORMES_VENTAS_2', '1', 1),
(123, 'INVENTARIO_PVP_UNIFICADO', '0', 1),
(54, 'lim_dcto', '30.5', 1),
(86, 'LIM_FAC_REMI', '0', 1),
(127, 'limitar_solo_contratos_credito', '0', 1),
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
(124, 'NUM_NOTA_ENTREGA', '0', 1),
(16, 'OPC_COMPRAS_REPLACEREF', '0', 1),
(17, 'OPC_FACVEN_BUSQ_STOCK', '1', 1),
(79, 'PAGO_EFECTIVO_TARJETA', '0', 1),
(55, 'per_credito', '5', 1),
(56, 'per_mayo', '0.25', 1),
(97, 'PLAN_AMOR', '0', 1),
(126, 'planes_clientes', '0', 1),
(43, 'precioBonoCasco', '50000', 1),
(31, 'promediar_costos', '0', 1),
(3, 'PUBLICO_GENERAL', 'CLIENTE GENERAL', 1),
(15, 'PUNTUACION_DECIMALES', ',', 1),
(14, 'PUNTUACION_MILES', '.', 1),
(105, 'PVP_COTIZA', '0', 1),
(76, 'PVP_CREDITO', '0', 1),
(85, 'PVP_MAYORISTA', '0', 1),
(82, 'QUICK_FAC_INPUT', '1', 1),
(26, 'redondear_pvp_costo', 's', 1),
(27, 'redondear_util', 's', 1),
(2, 'REGIMEN', 'SIMPLIFICADO', 1),
(71, 'REMISIONES', '0', 1),
(103, 'RES_VEN', '0', 1),
(102, 'RETENCIONES', '0', 1),
(91, 'ROTACION_INV', '0', 1),
(75, 'SERVICIOS', '0', 1),
(1, 'Tipo Utilidad', 'A', 1),
(22, 'TIPO_CHUZO', 'ROPA', 1),
(50, 'tipo_fac_default', 'COM', 1),
(37, 'tipo_imp_comprobantes', 'COM', 1),
(42, 'tipo_impresion', 'COM', 1),
(25, 'tipo_redondeo', 'decimal', 1),
(70, 'TRASLADOS', '0', 1),
(81, 'UN_BAN_CLI2', '0', 1),
(4, 'url_LOGO_A', 'Imagenes/logoApp.png', 1),
(5, 'url_LOGO_B', 'Imagenes/logoApp.png', 1),
(48, 'usar_bsf', '0', 1),
(30, 'usar_costo_dcto', '1', 1),
(53, 'usar_costo_remi', '0', 1),
(18, 'usar_iva', '1', 1),
(49, 'usar_posFac', '0', 1),
(46, 'usar_remision', '0', 1),
(112, 'usar_serial', '0', 1),
(21, 'usar_ubica', '1', 1),
(122, 'VALOR_CURSO_NOMINA', '0', 1),
(115, 'valor_impuesto_bolsas', '20', 1),
(7, 'Variable_size_imp_carta', '0', 1),
(84, 'VEHICULOS', '0', 1),
(32, 'vende_sin_cant', '0', 1),
(120, 'VENTA_VEHICULOS', '0', 1),
(51, 'ventas_credito', '0', 1),
(44, 'ver_pvp_sin_iva', '0', 1),
(20, 'ver_util', '1', 1),
(52, 'vista_remi', 'A', 1),
(8, 'x', '130px', 1),
(10, 'X', '250px', 1),
(6, 'X_fac', '27.9cm', 1),
(11, 'Y', '100px', 1),
(9, 'y', '50px', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `x_material_query`
--

CREATE TABLE IF NOT EXISTS `x_material_query` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `seccion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last` date NOT NULL,
  `reset_days` int(11) NOT NULL,
  `state` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `cod_su` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `seccion` (`seccion`,`cod_su`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `x_material_query`
--

INSERT INTO `x_material_query` (`id`, `seccion`, `last`, `reset_days`, `state`, `cod_su`) VALUES
(1, 'Rotacion Inventario', '2017-11-14', 15, '', 1),
(2, 'Ajuste Kardex', '2017-02-24', 100000, 'Done At: 2017-02-24 08:24:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `x_opt_egresos`
--

CREATE TABLE IF NOT EXISTS `x_opt_egresos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `des` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `sub_grupo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=54 ;

--
-- Volcado de datos para la tabla `x_opt_egresos`
--

INSERT INTO `x_opt_egresos` (`id`, `des`, `sub_grupo`) VALUES
(1, 'Proveedores', 'Gastos Almac&eacute;n'),
(2, 'Anticipos de Compras', 'Gastos Almac&eacute;n'),
(3, 'Compras', 'Gastos Almac&eacute;n'),
(4, 'Flete Compras', 'Gastos Almac&eacute;n'),
(5, 'Flete Ventas', 'Gastos Almac&eacute;n'),
(6, 'Descargue', 'Gastos Almac&eacute;n'),
(7, 'Gastos Vehiculo', 'Gastos Almac&eacute;n'),
(8, 'Gastos Moto', 'Gastos Almac&eacute;n'),
(9, 'Gastos Moto Carga', 'Gastos Almac&eacute;n'),
(10, 'Regalos y Obsequios', 'Gastos Almac&eacute;n'),
(11, 'Otros gastos de almacen', 'Gastos Almac&eacute;n'),
(12, 'Gastos Personales MFC', 'Gastos Personales MFC'),
(13, 'Sueldos', 'Gastos de N&oacute;mina'),
(14, 'Vales Nomina', 'Gastos de N&oacute;mina'),
(15, 'Seguridad social', 'Gastos de N&oacute;mina'),
(16, 'Liquidaciones', 'Gastos de N&oacute;mina'),
(17, 'Dotacion', 'Gastos de N&oacute;mina'),
(18, 'Profesional Externo', 'Gastos de N&oacute;mina'),
(19, 'Subsidio Nestor Paez', 'Subsidios Comfiar'),
(20, 'Subsidio Auxiliares', 'Subsidios Comfiar'),
(21, 'Subsidio Gastos  Viajes', 'Subsidios Comfiar'),
(22, 'Subsidio Beneficiario', 'Subsidios Comfiar'),
(23, 'Subsidio Compra, Proveedor, Anticipo', 'Subsidios Comfiar'),
(24, 'Subsidio Papeleria', 'Subsidios Comfiar'),
(25, 'Subsidio Tramite y Documentos', 'Subsidios Comfiar'),
(26, 'Viaticos', 'Gastos de Marketing'),
(27, 'Cafeteria', 'Gastos de Marketing'),
(28, 'Costes de publicidad', 'Gastos de Marketing'),
(29, 'Costes de representacion', 'Gastos de Marketing'),
(30, 'Suministros de oficina y Papeleria', 'Gastos de Oficina'),
(31, 'Software', 'Gastos de Oficina'),
(32, 'Hardware', 'Gastos de Oficina'),
(33, 'Otro material de oficina', 'Gastos de Oficina'),
(34, 'Consignacion Ventas', 'Bancos'),
(35, 'Consignacion Cuentas Bancarias', 'Bancos'),
(36, 'Pagos Financieros', 'Bancos'),
(37, 'Intereses Varios', 'Bancos'),
(38, 'Arriendo', 'Instalaciones y edificios'),
(39, 'Celular', 'Instalaciones y edificios'),
(40, 'Telefono e Internet', 'Instalaciones y edificios'),
(41, 'Recargas Celulares', 'Instalaciones y edificios'),
(42, 'Electricidad', 'Instalaciones y edificios'),
(43, 'Agua, limpieza, basura', 'Instalaciones y edificios'),
(44, 'Seguridad y Vigilancia', 'Instalaciones y edificios'),
(45, 'Predial', 'Impuestos y Contabilidad'),
(46, 'Pago de IVA', 'Impuestos y Contabilidad'),
(47, 'ICA', 'Impuestos y Contabilidad'),
(48, 'Industria y Comercio', 'Impuestos y Contabilidad'),
(49, 'Retefuente', 'Impuestos y Contabilidad'),
(50, 'Renta', 'Impuestos y Contabilidad'),
(51, 'Camara de Comercio', 'Impuestos y Contabilidad'),
(52, 'Devolucion de IVA', 'Impuestos y Contabilidad'),
(53, 'Otros Impuestos', 'Impuestos y Contabilidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `x_updates`
--

CREATE TABLE IF NOT EXISTS `x_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_exe` date NOT NULL,
  `sql_updated` text COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `hora_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `repetidos_inv`
--
DROP TABLE IF EXISTS `repetidos_inv`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`127.0.0.1` SQL SECURITY DEFINER VIEW `repetidos_inv` AS select `inv_inter`.`serial_inv` AS `serial_inv`,`inv_inter`.`id_pro` AS `id_pro`,`inv_inter`.`nit_scs` AS `nit_scs`,`inv_inter`.`id_inter` AS `id_inter`,`inv_inter`.`exist` AS `exist`,`inv_inter`.`max` AS `max`,`inv_inter`.`min` AS `min`,`inv_inter`.`costo` AS `costo`,`inv_inter`.`precio_v` AS `precio_v`,`inv_inter`.`fraccion` AS `fraccion`,`inv_inter`.`gana` AS `gana`,`inv_inter`.`tipo_dcto` AS `tipo_dcto`,`inv_inter`.`iva` AS `iva`,`inv_inter`.`dcto2` AS `dcto2`,`inv_inter`.`color` AS `color`,`inv_inter`.`talla` AS `talla`,`inv_inter`.`dcto3` AS `dcto3`,`inv_inter`.`presentacion` AS `presentacion`,`inv_inter`.`fecha_vencimiento` AS `fecha_vencimiento`,`inv_inter`.`unidades_frac` AS `unidades_frac`,`inv_inter`.`certificado_importacion` AS `certificado_importacion`,`inv_inter`.`pvp_credito` AS `pvp_credito`,`inv_inter`.`marcas` AS `marcas`,`inv_inter`.`ubicacion` AS `ubicacion`,`inv_inter`.`envase` AS `envase`,`inv_inter`.`aplica_vehi` AS `aplica_vehi`,`inv_inter`.`pvp_may` AS `pvp_may`,count(`inv_inter`.`id_inter`) AS `n` from `inv_inter` group by `inv_inter`.`id_inter`,`inv_inter`.`fecha_vencimiento`,`inv_inter`.`nit_scs` having (`n` > 1);

-- --------------------------------------------------------

--
-- Estructura para la vista `ventas_x_pro`
--
DROP TABLE IF EXISTS `ventas_x_pro`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ventas_x_pro` AS select `a`.`nit` AS `nit`,`a`.`ref` AS `ref`,`a`.`cod_barras` AS `cod_barras`,`a`.`prefijo` AS `prefijo`,`a`.`num_fac_ven` AS `num_fac_ven`,`a`.`des` AS `des`,`a`.`iva` AS `iva`,`a`.`precio` AS `precio`,`a`.`sub_tot` AS `sub_tot`,`b`.`fab` AS `fab`,`a`.`cant` AS `cant`,`a`.`fraccion` AS `fraccion`,`a`.`unidades_fraccion` AS `unidades_fraccion`,`a`.`dcto` AS `dcto`,`a`.`costo` AS `costo`,`b`.`id_clase` AS `id_clase`,`b`.`id_sub_clase` AS `id_sub_clase`,`b`.`nit_proveedor` AS `nit_proveedor` from (`art_fac_ven` `a` join `productos` `b` on((`a`.`ref` = `b`.`id_pro`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_arqueo_pro`
--
DROP TABLE IF EXISTS `vista_arqueo_pro`;

CREATE ALGORITHM=MERGE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_arqueo_pro` AS select `fac_venta`.`fecha` AS `fecha`,`fac_venta`.`fecha_anula` AS `fecha_anula`,`fac_venta`.`anulado` AS `anulado`,`art_fac_ven`.`nit` AS `nit`,`fac_venta`.`anticipo_bono` AS `anticipo_bono`,`fac_venta`.`tot_tarjeta` AS `tot_tarjeta`,`fac_venta`.`entrega_bsf` AS `entrega_bsf`,`art_fac_ven`.`num_fac_ven` AS `num_fac_ven`,`art_fac_ven`.`precio` AS `precio`,`art_fac_ven`.`des` AS `des`,`art_fac_ven`.`sub_tot` AS `sub_tot`,`art_fac_ven`.`iva` AS `iva`,`art_fac_ven`.`cant` AS `cant`,`art_fac_ven`.`ref` AS `ref`,cast(`fac_venta`.`fecha` as time) AS `hora`,cast(`fac_venta`.`fecha` as date) AS `fe`,`fac_venta`.`tipo_venta` AS `tipo_venta`,`fac_venta`.`tipo_cli` AS `tipo_cli`,`fac_venta`.`vendedor` AS `vendedor`,`art_fac_ven`.`prefijo` AS `prefijo`,`fac_venta`.`tot_bsf` AS `tot_bsf`,`fac_venta`.`cod_caja` AS `cod_caja` from (`fac_venta` join `art_fac_ven` on(((`fac_venta`.`num_fac_ven` = `art_fac_ven`.`num_fac_ven`) and (`fac_venta`.`prefijo` = `art_fac_ven`.`prefijo`) and (`fac_venta`.`nit` = `art_fac_ven`.`nit`))));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_inventario`
--
DROP TABLE IF EXISTS `vista_inventario`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inventario` AS select `productos`.`id_pro` AS `id_glo`,`inv_inter`.`id_inter` AS `id_sede`,`productos`.`detalle` AS `detalle`,`productos`.`id_clase` AS `id_clase`,`inv_inter`.`fraccion` AS `fraccion`,`productos`.`fab` AS `fab`,`inv_inter`.`max` AS `max`,`inv_inter`.`min` AS `min`,`inv_inter`.`costo` AS `costo`,`inv_inter`.`precio_v` AS `precio_v`,`inv_inter`.`exist` AS `exist`,`inv_inter`.`iva` AS `iva`,`inv_inter`.`gana` AS `gana`,`inv_inter`.`nit_scs` AS `nit_scs`,`productos`.`presentacion` AS `presentacion`,`productos`.`nit_proveedor` AS `nit_proveedor`,`productos`.`id_sub_clase` AS `id_sub_clase`,`inv_inter`.`color` AS `color`,`inv_inter`.`talla` AS `talla`,`inv_inter`.`ubicacion` AS `ubicacion`,`inv_inter`.`aplica_vehi` AS `aplica_vehi`,`inv_inter`.`pvp_may` AS `pvp_may`,`inv_inter`.`pvp_credito` AS `pvp_credito` from (`productos` join `inv_inter` on((`productos`.`id_pro` = `inv_inter`.`id_pro`)));

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resoluciones_dian`
--
DROP TABLE IF EXISTS `vista_resoluciones_dian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resoluciones_dian` AS select `fac_venta`.`prefijo` AS `prefijo`,`fac_venta`.`resolucion` AS `resolucion`,`fac_venta`.`fecha_resol` AS `fecha_resol`,`fac_venta`.`rango_resol` AS `rango_resol`,`fac_venta`.`nit` AS `nit`,max(`fac_venta`.`num_fac_ven`) AS `lastN` from `fac_venta` group by `fac_venta`.`resolucion`,`fac_venta`.`prefijo`,`fac_venta`.`rango_resol`,`fac_venta`.`nit`;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ventas_rotacion`
--
DROP TABLE IF EXISTS `vista_ventas_rotacion`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ventas_rotacion` AS select `a`.`num_fac_ven` AS `num_fac_ven`,`a`.`prefijo` AS `preArt`,`b`.`prefijo` AS `preFac`,`a`.`ref` AS `ref`,`a`.`cod_barras` AS `cod_barras`,`a`.`des` AS `des`,`a`.`cant` AS `cant`,`a`.`costo` AS `costo`,`b`.`fecha` AS `fecha`,`b`.`anulado` AS `anulado`,`b`.`nit` AS `facNit`,`a`.`nit` AS `artNit`,sum(`a`.`cant`) AS `tot_dia`,avg(`a`.`cant`) AS `prom_dia`,min(`a`.`cant`) AS `min`,max(`a`.`cant`) AS `max` from (`art_fac_ven` `a` join `fac_venta` `b` on((`a`.`num_fac_ven` = `b`.`num_fac_ven`))) where ((`a`.`prefijo` = `b`.`prefijo`) and (`a`.`nit` = `b`.`nit`)) group by `a`.`cod_barras`,cast(`b`.`fecha` as date);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bancos_cuentas`
--
ALTER TABLE `bancos_cuentas`
  ADD CONSTRAINT `bancos_cuentas_ibfk_1` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id_banco`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `fk_mun` FOREIGN KEY (`id_dep`) REFERENCES `departamento` (`id_dep`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `fk_su` FOREIGN KEY (`id_dep`) REFERENCES `departamento` (`id_dep`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_su2` FOREIGN KEY (`id_mun`) REFERENCES `municipio` (`id_mun`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipo_usu`
--
ALTER TABLE `tipo_usu`
  ADD CONSTRAINT `fk_tipousu` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usu_login`
--
ALTER TABLE `usu_login`
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id_usu`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

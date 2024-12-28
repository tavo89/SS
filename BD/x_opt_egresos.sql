-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 13-05-2017 a las 05:14:57
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `metalhierro`
--

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
(1, 'Facturas Proveedores', 'Gastos Almacen'),
(2, 'Anticipos de Compras', 'Gastos Almacen'),
(3, 'Compras', 'Gastos Almacen'),
(4, 'Envios, transportes y logistica', 'Gastos Almacen'),
 
(6, 'Pago Vigilancia', 'Gastos Almacen'),
(7, 'Gastos Vehiculo', 'Gastos Almacen'),
(10, 'Regalos y Obsequios', 'Gastos Almacen'),
(11, 'Otros gastos de almacen', 'Gastos Almacen'),
(13, 'Sueldo de los empleados', 'Gastos de Nomina'),
(14, 'Vales Nomina', 'Gastos de Nomina'),
(15, 'Seguridad social', 'Gastos de Nomina'),
(16, 'Liquidaciones', 'Gastos de Nomina'),
(17, 'Dotacion', 'Gastos de Nomina'),
(18, 'Profesional Externo', 'Gastos de Nomina'),
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

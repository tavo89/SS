-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-01-2016 a las 18:50:33
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `frenopartes`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

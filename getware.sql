-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-05-2016 a las 23:51:06
-- Versión del servidor: 5.6.15-log
-- Versión de PHP: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `getware`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin`
--

CREATE TABLE IF NOT EXISTS `sys_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '' COMMENT 'FILE(admin.php)',
  `access` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_admin_access.name)',
  `group` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_admin_groups.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `sys_admin`
--

INSERT INTO `sys_admin` (`id`, `name`, `file`, `access`, `group`) VALUES
(1, 'Modules', 'sys_admin', 1, 1),
(2, 'Users', 'sys_users', 1, 1),
(3, 'Privileges', 'sys_privileges', 1, 1),
(4, 'Groups', 'sys_groups', 1, 1),
(5, 'Settings', 'sys_settings', 1, 1),
(6, 'Demons', 'sys_demons', 1, 1),
(7, 'Blocks', 'sys_blocks', 1, 1),
(8, 'General', 'general', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin_access`
--

CREATE TABLE IF NOT EXISTS `sys_admin_access` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_admin_access`
--

INSERT INTO `sys_admin_access` (`id`, `name`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin_groups`
--

CREATE TABLE IF NOT EXISTS `sys_admin_groups` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `maximize` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'REFERENCE(sys_boolean.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_admin_groups`
--

INSERT INTO `sys_admin_groups` (`id`, `name`, `maximize`) VALUES
(1, 'SYSTEM', 2),
(2, 'GENERAL', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin_privileges`
--

CREATE TABLE IF NOT EXISTS `sys_admin_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_users.username)',
  `module` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_admin.name)',
  `privileges` tinyint(1) unsigned NOT NULL COMMENT 'REFERENCES(sys_admin_privileges_types_data#privilege#type#sys_admin_privileges_types.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`user`,`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin_privileges_types`
--

CREATE TABLE IF NOT EXISTS `sys_admin_privileges_types` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `sys_admin_privileges_types`
--

INSERT INTO `sys_admin_privileges_types` (`id`, `name`) VALUES
(1, 'view'),
(2, 'edit'),
(3, 'add'),
(4, 'remove'),
(5, 'print'),
(6, 'upload');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_admin_privileges_types_data`
--

CREATE TABLE IF NOT EXISTS `sys_admin_privileges_types_data` (
  `privilege` int(11) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`privilege`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_blocks`
--

CREATE TABLE IF NOT EXISTS `sys_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `file` varchar(25) NOT NULL DEFAULT '' COMMENT 'FILE(blocks.php)',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_blocks_access.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `sys_blocks`
--

INSERT INTO `sys_blocks` (`id`, `name`, `file`, `access`) VALUES
(1, 'HOME', 'home', 2),
(2, 'LOGIN', 'login', 2),
(3, 'Administracion', 'admin', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_blocks_access`
--

CREATE TABLE IF NOT EXISTS `sys_blocks_access` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `sys_blocks_access`
--

INSERT INTO `sys_blocks_access` (`id`, `name`) VALUES
(1, 'Usuarios'),
(2, 'Invitados'),
(3, 'Todos'),
(4, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_boolean`
--

CREATE TABLE IF NOT EXISTS `sys_boolean` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_boolean`
--

INSERT INTO `sys_boolean` (`id`, `name`) VALUES
(1, 'SI'),
(2, 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_demons`
--

CREATE TABLE IF NOT EXISTS `sys_demons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `file` varchar(25) NOT NULL DEFAULT '' COMMENT 'FILE(demons/.php)',
  `access` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_demons_access.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sys_demons`
--

INSERT INTO `sys_demons` (`id`, `name`, `file`, `access`) VALUES
(1, 'User LOG', 'users_log', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_demons_access`
--

CREATE TABLE IF NOT EXISTS `sys_demons_access` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_demons_access`
--

INSERT INTO `sys_demons_access` (`id`, `name`) VALUES
(1, 'Acivo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_modules`
--

CREATE TABLE IF NOT EXISTS `sys_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `file` varchar(25) NOT NULL DEFAULT '',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_modules_access.name)',
  `shadow` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`file`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `sys_modules`
--

INSERT INTO `sys_modules` (`id`, `name`, `file`, `access`, `shadow`) VALUES
(1, 'Administracion', 'admin', 1, 0),
(2, 'Home', 'home', 3, 0),
(3, 'Login', 'login', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_modules_access`
--

CREATE TABLE IF NOT EXISTS `sys_modules_access` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `sys_modules_access`
--

INSERT INTO `sys_modules_access` (`id`, `name`) VALUES
(1, 'Usuarios'),
(2, 'Invitados'),
(3, 'Todos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_months`
--

CREATE TABLE IF NOT EXISTS `sys_months` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `sys_months`
--

INSERT INTO `sys_months` (`id`, `name`) VALUES
(1, 'ENERO'),
(2, 'FEBRERO'),
(3, 'MARZO'),
(4, 'ABRIL'),
(5, 'MAYO'),
(6, 'JUNIO'),
(7, 'JULIO'),
(8, 'AGOSTO'),
(9, 'SEPTIEMBRE'),
(10, 'OCTUBRE'),
(11, 'NOVIEMBRE'),
(12, 'DICIEMBRE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_sessions`
--

CREATE TABLE IF NOT EXISTS `sys_sessions` (
  `user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_users.username)',
  `key` varchar(40) NOT NULL DEFAULT '',
  `time` bigint(20) NOT NULL,
  `ip` varchar(48) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_settings`
--

CREATE TABLE IF NOT EXISTS `sys_settings` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) NOT NULL DEFAULT 'Getware',
  `slogan` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(150) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(40) NOT NULL DEFAULT '' COMMENT 'FILE(images/logos/.png)',
  `date` date NOT NULL,
  `language` char(2) NOT NULL DEFAULT '',
  `theme` varchar(25) NOT NULL DEFAULT '' COMMENT 'FILE(themes/.php)',
  `footer` text,
  `module` varchar(25) NOT NULL DEFAULT '' COMMENT 'FILE(modules/.php)',
  `description` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sys_settings`
--

INSERT INTO `sys_settings` (`id`, `sitename`, `slogan`, `keywords`, `url`, `email`, `logo`, `date`, `language`, `theme`, `footer`, `module`, `description`) VALUES
(1, 'KEBLAR S.A.', 'Sistema de Administracion', 'getware, ultra-secure', 'http://www.mrbconsultora.com.ar', 'mrb@mrbconsultora.com.ar', 'logo', '2004-04-15', 'es', 'green', 'Copyright {year} Keblar S.A.', 'home', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_users`
--

CREATE TABLE IF NOT EXISTS `sys_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT 'REFERENCE(sys_users_types.name)',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'REFERENCE(sys_users_access.name)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `sys_users`
--

INSERT INTO `sys_users` (`id`, `username`, `password`, `type`, `access`) VALUES
(1, 'root', '63a9f0ea7bb98050796b649e85481845', 1, 1),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 1),
(3, 'mrb', '13eed552a284d37f230c63de3263cf9b', 1, 1);

--
-- Disparadores `sys_users`
--
DROP TRIGGER IF EXISTS `sys_users_U_BEFORE`;
DELIMITER //
CREATE TRIGGER `sys_users_U_BEFORE` BEFORE UPDATE ON `sys_users`
 FOR EACH ROW BEGIN
  IF NEW.password!=OLD.password THEN
   SET NEW.password=md5(NEW.password);
  ELSE
    SET NEW.password=OLD.password;
  END IF;
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_users_access`
--

CREATE TABLE IF NOT EXISTS `sys_users_access` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_users_access`
--

INSERT INTO `sys_users_access` (`id`, `name`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_users_types`
--

CREATE TABLE IF NOT EXISTS `sys_users_types` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sys_users_types`
--

INSERT INTO `sys_users_types` (`id`, `name`) VALUES
(1, 'Super'),
(2, 'Comun');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_years`
--

CREATE TABLE IF NOT EXISTS `sys_years` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` year(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `SECONDARY` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2112 ;

--
-- Volcado de datos para la tabla `sys_years`
--

INSERT INTO `sys_years` (`id`, `name`) VALUES
(2012, 2012),
(2013, 2013),
(2014, 2014),
(2015, 2015),
(2016, 2016),
(2017, 2017),
(2018, 2018),
(2019, 2019),
(2020, 2020),
(2021, 2021),
(2022, 2022),
(2023, 2023),
(2024, 2024),
(2025, 2025),
(2026, 2026),
(2027, 2027),
(2028, 2028),
(2029, 2029),
(2030, 2030),
(2031, 2031),
(2032, 2032),
(2033, 2033),
(2034, 2034),
(2035, 2035),
(2036, 2036),
(2037, 2037),
(2038, 2038),
(2039, 2039),
(2040, 2040),
(2041, 2041),
(2042, 2042),
(2043, 2043),
(2044, 2044),
(2045, 2045),
(2046, 2046),
(2047, 2047),
(2048, 2048),
(2049, 2049),
(2050, 2050),
(2051, 2051),
(2052, 2052),
(2053, 2053),
(2054, 2054),
(2055, 2055),
(2056, 2056),
(2057, 2057),
(2058, 2058),
(2059, 2059),
(2060, 2060),
(2061, 2061),
(2062, 2062),
(2063, 2063),
(2064, 2064),
(2065, 2065),
(2066, 2066),
(2067, 2067),
(2068, 2068),
(2069, 2069),
(2070, 2070),
(2071, 2071),
(2072, 2072),
(2073, 2073),
(2074, 2074),
(2075, 2075),
(2076, 2076),
(2077, 2077),
(2078, 2078),
(2079, 2079),
(2080, 2080),
(2081, 2081),
(2082, 2082),
(2083, 2083),
(2084, 2084),
(2085, 2085),
(2086, 2086),
(2087, 2087),
(2088, 2088),
(2089, 2089),
(2090, 2090),
(2091, 2091),
(2092, 2092),
(2093, 2093),
(2094, 2094),
(2095, 2095),
(2096, 2096),
(2097, 2097),
(2098, 2098),
(2099, 2099),
(2100, 2100),
(2101, 2101),
(2102, 2102),
(2103, 2103),
(2104, 2104),
(2105, 2105),
(2106, 2106),
(2107, 2107),
(2108, 2108),
(2109, 2109),
(2110, 2110),
(2111, 2111);
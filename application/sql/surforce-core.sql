/*
MySQL Data Transfer
Source Host: localhost
Source Database: surforce-core
Target Host: localhost
Target Database: surforce-core
Date: 03/05/2009 06:52:05 p.m.
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for access_site
-- ----------------------------
CREATE TABLE `access_site` (
  `access_site_id` int(10) NOT NULL auto_increment,
  `remote_address` varchar(100) collate utf8_spanish_ci default NULL,
  `remote_address_real` varchar(100) collate utf8_spanish_ci default NULL,
  `url_referer` varchar(200) collate utf8_spanish_ci default NULL,
  `domain` varchar(200) collate utf8_spanish_ci default NULL,
  `date` timestamp NULL default NULL,
  `usuario` varchar(200) collate utf8_spanish_ci default NULL,
  `usuario_module` varchar(200) collate utf8_spanish_ci default NULL,
  `usuario_controller` varchar(200) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`access_site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for administradores
-- ----------------------------
CREATE TABLE `administradores` (
  `admin_id` int(11) NOT NULL auto_increment,
  `admin_mail` varchar(100) collate utf8_spanish_ci NOT NULL,
  `admin_clave` varchar(32) collate utf8_spanish_ci NOT NULL,
  `admin_nombre` varchar(50) collate utf8_spanish_ci NOT NULL,
  `admin_apellido` varchar(50) collate utf8_spanish_ci NOT NULL,
  `admin_fecha_mod` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `admin_ip_registro` varchar(32) collate utf8_spanish_ci NOT NULL,
  `admin_ip_real_registro` varchar(32) collate utf8_spanish_ci NOT NULL,
  `admin_estado` tinyint(1) NOT NULL,
  `admin_baja` tinyint(1) default NULL,
  PRIMARY KEY  (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for admins_menu
-- ----------------------------
CREATE TABLE `admins_menu` (
  `aplicacion_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL default '0',
  `menu_texto` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_url` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_estado` tinyint(4) NOT NULL,
  PRIMARY KEY  (`aplicacion_id`,`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for admins_menu_items
-- ----------------------------
CREATE TABLE `admins_menu_items` (
  `admin_menu_item_id` int(11) NOT NULL auto_increment,
  `admin_menu_id` int(11) default NULL,
  `admin_menu_item_texto` varchar(250) collate utf8_spanish_ci default NULL,
  `admin_menu_item_url` varchar(250) collate utf8_spanish_ci default NULL,
  `admin_menu_item_estado` tinyint(4) default NULL,
  PRIMARY KEY  (`admin_menu_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for aplicaciones
-- ----------------------------
CREATE TABLE `aplicaciones` (
  `aplicacion_id` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(100) collate utf8_spanish_ci NOT NULL,
  `estado` tinyint(1) default '1',
  PRIMARY KEY  (`aplicacion_id`),
  KEY `estado_IDX` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
CREATE TABLE `menu` (
  `aplicacion_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL default '0',
  `texto` varchar(250) collate utf8_spanish_ci NOT NULL,
  `url` varchar(250) collate utf8_spanish_ci NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY  (`aplicacion_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for menu_items
-- ----------------------------
CREATE TABLE `menu_items` (
  `menu_id` int(11) NOT NULL auto_increment,
  `item_id` int(11) default NULL,
  `texto` varchar(250) collate utf8_spanish_ci default NULL,
  `url` varchar(250) collate utf8_spanish_ci default NULL,
  `estado` tinyint(4) default NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for public_menu
-- ----------------------------
CREATE TABLE `public_menu` (
  `aplicacion_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL default '0',
  `menu_texto` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_url` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_estado` tinyint(4) NOT NULL,
  PRIMARY KEY  (`aplicacion_id`,`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for public_menu_items
-- ----------------------------
CREATE TABLE `public_menu_items` (
  `menu_id` int(11) NOT NULL auto_increment,
  `menuitem_id` int(11) default NULL,
  `menuitem_app_module` varchar(100) collate utf8_spanish_ci default NULL,
  `menuitem_texto` varchar(250) collate utf8_spanish_ci default NULL,
  `menuitem_url` varchar(250) collate utf8_spanish_ci default NULL,
  `menuitem_estado` tinyint(4) default NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL auto_increment,
  `usuario_mail` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_clave` varchar(32) collate utf8_spanish_ci NOT NULL,
  `usuario_nombre` varchar(50) collate utf8_spanish_ci NOT NULL,
  `usuario_apellido` varchar(50) collate utf8_spanish_ci NOT NULL,
  `usuario_sexo` varchar(1) collate utf8_spanish_ci NOT NULL,
  `usuario_nacimiento` date NOT NULL,
  `usuario_pais` varchar(50) collate utf8_spanish_ci NOT NULL,
  `usuario_ciudad` varchar(50) collate utf8_spanish_ci NOT NULL,
  `usuario_skype` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_blog` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_twitter` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_profesion` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_empresa` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_comentarios` text collate utf8_spanish_ci NOT NULL,
  `usuario_foto` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario_fecha_mod` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `usuario_fecha_ingreso` timestamp NOT NULL default '0000-00-00 00:00:00',
  `usuario_fecha_aceptado_usuario` timestamp NOT NULL default '0000-00-00 00:00:00',
  `usuario_grupo` int(2) NOT NULL,
  `usuario_ip` varchar(32) collate utf8_spanish_ci NOT NULL,
  `usuario_ip_real` varchar(32) collate utf8_spanish_ci NOT NULL,
  `usuario_url_referer` varchar(300) collate utf8_spanish_ci NOT NULL,
  `usuario_comentarios_sys` text collate utf8_spanish_ci NOT NULL,
  `usuario_estado` tinyint(1) NOT NULL,
  `usuario_baja` tinyint(1) NOT NULL,
  PRIMARY KEY  (`usuario_id`),
  UNIQUE KEY `mail_usuario` (`usuario_mail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuarios_menu
-- ----------------------------
CREATE TABLE `usuarios_menu` (
  `aplicacion_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL default '0',
  `menu_texto` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_url` varchar(250) collate utf8_spanish_ci NOT NULL,
  `menu_estado` tinyint(4) NOT NULL,
  PRIMARY KEY  (`aplicacion_id`,`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuarios_menu_items
-- ----------------------------
CREATE TABLE `usuarios_menu_items` (
  `menu_id` int(11) NOT NULL auto_increment,
  `menuitem_id` int(11) default NULL,
  `menuitem_app_module` varchar(100) collate utf8_spanish_ci default NULL,
  `menuitem_texto` varchar(250) collate utf8_spanish_ci default NULL,
  `menuitem_url` varchar(250) collate utf8_spanish_ci default NULL,
  `menuitem_estado` tinyint(4) default NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuarios_old
-- ----------------------------
CREATE TABLE `usuarios_old` (
  `usuario_id` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(100) collate utf8_spanish_ci NOT NULL,
  `apellido` varchar(100) collate utf8_spanish_ci NOT NULL,
  `empresa` varchar(100) collate utf8_spanish_ci default NULL,
  `mail` varchar(100) collate utf8_spanish_ci NOT NULL,
  `usuario` varchar(100) collate utf8_spanish_ci default NULL,
  `clave` varchar(100) collate utf8_spanish_ci default NULL,
  `estado` tinyint(1) default '1',
  PRIMARY KEY  (`usuario_id`),
  KEY `usuario_IDX` (`usuario`),
  KEY `clave_IDX` (`clave`),
  KEY `estado_IDX` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `aplicaciones` VALUES ('1', 'surforce-core', '1');
INSERT INTO `menu` VALUES ('1', '1', 'admin', 'admin', '1');
INSERT INTO `menu` VALUES ('1', '2', 'Menú de estadisticas', 'estadisticas', '1');
INSERT INTO `menu` VALUES ('1', '3', 'Prueba', 'test', '0');
INSERT INTO `menu_items` VALUES ('1', '1', 'item de prueba menu 1', null, '1');
INSERT INTO `menu_items` VALUES ('2', '2', 'item de prueba menu 2', null, '1');
INSERT INTO `public_menu` VALUES ('2', '1', 'Inicio', 'default', '1');
INSERT INTO `public_menu` VALUES ('2', '4', 'Contacto', 'contacto', '1');
INSERT INTO `usuarios_menu` VALUES ('2', '7', 'Salir', 'usuarios/login/logout', '1');
INSERT INTO `usuarios_menu` VALUES ('2', '1', 'Inicio', '', '1');
INSERT INTO `usuarios_menu` VALUES ('2', '5', 'Contacto', 'contacto', '0');
INSERT INTO `usuarios_menu` VALUES ('2', '6', 'Panel', 'usuarios/panel', '1');
INSERT INTO `usuarios_old` VALUES ('1', 'Enrique', 'Place', 'SURFORCE', 'enriqueplace@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1');

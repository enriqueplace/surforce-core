/*
MySQL Data Transfer
Source Host: localhost
Source Database: surforce-core
Target Host: localhost
Target Database: surforce-core
Date: 21/01/2009 14:46:32
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for aplicaciones
-- ----------------------------
CREATE TABLE `aplicaciones` (
  `aplicacion_id` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(1) default '1',
  PRIMARY KEY  (`aplicacion_id`),
  KEY `estado_IDX` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
CREATE TABLE `menu` (
  `aplicacion_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL default '0',
  `texto` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY  (`aplicacion_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for menu_items
-- ----------------------------
CREATE TABLE `menu_items` (
  `menu_id` int(11) NOT NULL auto_increment,
  `item_id` int(11) default NULL,
  `texto` varchar(250) default NULL,
  `url` varchar(250) default NULL,
  `estado` tinyint(4) default NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
CREATE TABLE `usuarios` (
  `usuario_id` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `empresa` varchar(100) default NULL,
  `mail` varchar(100) NOT NULL,
  `usuario` varchar(100) default NULL,
  `clave` varchar(100) default NULL,
  `estado` tinyint(1) default '1',
  PRIMARY KEY  (`usuario_id`),
  KEY `usuario_IDX` (`usuario`),
  KEY `clave_IDX` (`clave`),
  KEY `estado_IDX` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records
-- ----------------------------
INSERT INTO `aplicaciones` VALUES ('1', 'surforce-core', '1');
INSERT INTO `menu` VALUES ('1', '1', 'admin', 'admin', '1');
INSERT INTO `menu` VALUES ('1', '2', 'Menú de estadisticas', 'estadisticas', '1');
INSERT INTO `menu` VALUES ('1', '3', 'Prueba', 'test', '0');
INSERT INTO `menu_items` VALUES ('1', '1', 'item de prueba menu 1', null, '1');
INSERT INTO `menu_items` VALUES ('2', '2', 'item de prueba menu 2', null, '1');
INSERT INTO `usuarios` VALUES ('1', 'Enrique', 'Place', 'SURFORCE', 'enriqueplace@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1');

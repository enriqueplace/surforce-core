CREATE TABLE `menu_items` (
  `menu_id` int(11) NOT NULL auto_increment,
  `item_id` int(11) default NULL,
  `app_module` varchar(100) collate utf8_spanish_ci default NULL,
  `texto` varchar(250) collate utf8_spanish_ci default NULL,
  `url` varchar(250) collate utf8_spanish_ci default NULL,
  `estado` tinyint(4) default NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records
-- ----------------------------
INSERT INTO `menu_items` VALUES ('1', '1', 'admin', 'item de prueba menu 1', '', '1');
INSERT INTO `menu_items` VALUES ('2', '2', 'admin', 'item de prueba menu 2', '', '1');
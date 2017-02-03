CREATE TABLE IF NOT EXISTS `mc_plugins_slideshow` (
  `idslide` smallint(5) NOT NULL AUTO_INCREMENT,
  `idlang` tinyint(3) NOT NULL,
  `uri_slide` varchar(125) NOT NULL,
  `img_slide` varchar(25) NOT NULL,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `pos_slide` smallint(5) NOT NULL,
  PRIMARY KEY (`idslide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_plugins_slideshow_category` (
  `idslide` smallint(5) NOT NULL AUTO_INCREMENT,
  `idclc` smallint(5) NOT NULL,
  `uri_slide` varchar(125) NOT NULL,
  `img_slide` varchar(25) NOT NULL,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `pos_slide` smallint(5) NOT NULL,
  PRIMARY KEY (`idslide`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_plugins_slideshow_subcategory` (
  `idslide` smallint(5) NOT NULL AUTO_INCREMENT,
  `idcls` smallint(5) NOT NULL,
  `uri_slide` varchar(125) NOT NULL,
  `img_slide` varchar(25) NOT NULL,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `pos_slide` smallint(5) NOT NULL,
  PRIMARY KEY (`idslide`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_config_size_img` VALUES(null, 6, 'slideshow', 1920, 500, 'large', 'adaptive');
CREATE TABLE IF NOT EXISTS `mc_slideshow` (
  `id_slide` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `img_slide` varchar(25) NOT NULL,
  `order_slide` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_slideshow_content` (
  `id_slide_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_slide` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_slide` varchar(125) NOT NULL,
  `blank_slide` smallint(1) unsigned NOT NULL default 0,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `published_slide` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_slide_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_slide` (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_slideshow_content`
  ADD CONSTRAINT `mc_slideshow_content_ibfk_1` FOREIGN KEY (`id_slide`) REFERENCES `mc_slideshow` (`id_slide`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_slideshow_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_slideshow_category` (
  `id_slide` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_cat` smallint(5) unsigned NOT NULL,
  `img_slide` varchar(25) NOT NULL,
  `order_slide` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_slide`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_slideshow_category_content` (
  `id_slide_content` smallint(5) NOT NULL AUTO_INCREMENT,
  `id_slide` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_slide` varchar(125) NOT NULL,
  `blank_slide` smallint(1) unsigned NOT NULL default 0,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `published_slide` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_slide_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_slide` (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_slideshow_category_content`
  ADD CONSTRAINT `mc_slideshow_category_content_ibfk_1` FOREIGN KEY (`id_slide`) REFERENCES `mc_slideshow_category` (`id_slide`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_slideshow_category_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `mc_slideshow_pages` (
  `id_slide` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_pages` smallint(5) unsigned NOT NULL,
  `img_slide` varchar(25) NOT NULL,
  `order_slide` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_slide`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_slideshow_pages_content` (
  `id_slide_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_slide` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_slide` varchar(125) NOT NULL,
  `blank_slide` smallint(1) unsigned NOT NULL default 0,
  `title_slide` varchar(125) NOT NULL,
  `desc_slide` text,
  `published_slide` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_slide_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_slide` (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_slideshow_pages_content`
  ADD CONSTRAINT `mc_slideshow_pages_content_ibfk_1` FOREIGN KEY (`id_slide`) REFERENCES `mc_slideshow_pages` (`id_slide`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_slideshow_pages_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `resize_img`) VALUES
  (null, 'plugins', 'slideshow', '500', '200', 'small', 'adaptive'),
  (null, 'plugins', 'slideshow', '960', '320', 'medium', 'adaptive'),
  (null, 'plugins', 'slideshow', '1920', '500', 'large', 'adaptive');

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'slideshow';
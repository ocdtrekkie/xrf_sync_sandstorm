CREATE TABLE IF NOT EXISTS `y_nodekv` (
  `descr` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Node values are about',
  `nkey` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Key',
  `nvalue` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Value',
  PRIMARY KEY (`descr`, `nkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details about nodes';
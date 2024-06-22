CREATE TABLE IF NOT EXISTS `y_nodesoftware` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descr` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Node software is installed on',
  `appname` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of software',
  `appver` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Version of software',
  `apppub` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Publisher of software',
  `appdate` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Install date of software',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Software installed on nodes';
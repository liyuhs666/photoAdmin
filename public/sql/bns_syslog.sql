CREATE TABLE IF NOT EXISTS `bns_syslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `content` text,
  `tag` varchar(100) DEFAULT NULL,
  `logtype` tinyint(4) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `views` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `article`;");
E_C("CREATE TABLE `article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `top` varchar(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `pic` varchar(999) NOT NULL,
  `type` varchar(25) NOT NULL,
  `pv` varchar(20) NOT NULL,
  `pv_max` varchar(30) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `day` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `title` (`title`),
  KEY `pv` (`pv`),
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>
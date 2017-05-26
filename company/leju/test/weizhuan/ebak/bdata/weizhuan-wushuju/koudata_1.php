<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `koudata`;");
E_C("CREATE TABLE `koudata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) NOT NULL,
  `aid` int(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `long` varchar(999) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>
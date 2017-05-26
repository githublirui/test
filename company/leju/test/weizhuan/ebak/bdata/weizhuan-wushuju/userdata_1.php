<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `userdata`;");
E_C("CREATE TABLE `userdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tj_id` int(10) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `wx` varchar(255) NOT NULL,
  `realname` varchar(25) NOT NULL,
  `alipay` varchar(100) NOT NULL,
  `wgateid` varchar(255) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `kou` varchar(10) NOT NULL,
  `day` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `pass` (`pass`),
  KEY `tj_id` (`tj_id`),
  KEY `money` (`money`),
  KEY `realname` (`realname`),
  KEY `alipay` (`alipay`),
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>
<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `txdata`;");
E_C("CREATE TABLE `txdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) NOT NULL,
  `sx1` varchar(30) NOT NULL COMMENT '一级上线',
  `realname` varchar(55) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `alipay` varchar(255) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `state` varchar(1) NOT NULL COMMENT '0等待，1确认，2拒绝',
  `time` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>
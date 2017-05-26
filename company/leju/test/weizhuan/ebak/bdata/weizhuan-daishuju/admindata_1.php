<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `admindata`;");
E_C("CREATE TABLE `admindata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `q` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");
E_D("replace into `admindata` values('2','admin','asdasd','最高权限');");
E_D("replace into `admindata` values('5','123','4124','财务管理');");
E_D("replace into `admindata` values('6','1','1','网站设置');");
E_D("replace into `admindata` values('7','geo5078','asdasd','最高权限');");
E_D("replace into `admindata` values('8','1234','1234','财务管理');");

require("../../inc/footer.php");
?>
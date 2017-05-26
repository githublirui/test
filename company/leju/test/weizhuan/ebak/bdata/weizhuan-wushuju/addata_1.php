<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `addata`;");
E_C("CREATE TABLE `addata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ad_type` varchar(234) NOT NULL,
  `ad_content` mediumtext NOT NULL,
  `ad_list` varchar(25) NOT NULL,
  `pv` varchar(30) NOT NULL,
  `endtime` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pv` (`pv`),
  KEY `endtime` (`endtime`),
  KEY `ad_list` (`ad_list`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>
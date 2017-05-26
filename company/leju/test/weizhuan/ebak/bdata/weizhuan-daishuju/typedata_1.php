<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `typedata`;");
E_C("CREATE TABLE `typedata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_pp` decimal(10,2) NOT NULL,
  `type_author` varchar(999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8");
E_D("replace into `typedata` values('2','励志','0.05','');");
E_D("replace into `typedata` values('3','搞笑','0.05','');");
E_D("replace into `typedata` values('5','视频','0.05','');");
E_D("replace into `typedata` values('6','专题','0.05','');");
E_D("replace into `typedata` values('7','健康','0.05','');");
E_D("replace into `typedata` values('8','两性','0.05','');");
E_D("replace into `typedata` values('9','情感','0.05','');");
E_D("replace into `typedata` values('10','育儿','0.05','');");
E_D("replace into `typedata` values('11','星座','0.05','');");
E_D("replace into `typedata` values('12','节目','0.05','');");
E_D("replace into `typedata` values('13','旅游','0.05','');");
E_D("replace into `typedata` values('14','汽车','0.05','');");
E_D("replace into `typedata` values('15','职场','0.05','');");
E_D("replace into `typedata` values('16','美容','0.05','');");
E_D("replace into `typedata` values('17','化妆','0.05','');");
E_D("replace into `typedata` values('18','发型','0.05','');");
E_D("replace into `typedata` values('19','军事','0.05','');");
E_D("replace into `typedata` values('20','佛学','0.05','');");
E_D("replace into `typedata` values('21','养身','0.05','');");
E_D("replace into `typedata` values('22','减肥','0.05','');");
E_D("replace into `typedata` values('23','心理','0.05','');");
E_D("replace into `typedata` values('24','段子','0.05','');");
E_D("replace into `typedata` values('25','糗事','0.05','[author=http://www.gouso.com]公众号[/author]');");
E_D("replace into `typedata` values('26','生活','0.05','[author=http://www.gouso.com]公众号[/author]');");
E_D("replace into `typedata` values('27','吃货','0.05','[author=http://www.gouso.com]公众号[/author]');");
E_D("replace into `typedata` values('28','美食','0.05','[author=http://www.gouso.com]公众号[/author]');");
E_D("replace into `typedata` values('29','家居','0.05','[author=http://www.gouso.com]公众号[/author]');");

require("../../inc/footer.php");
?>
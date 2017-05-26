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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8");
E_D("replace into `txdata` values('1','1','','方志明','15901970032','123235523@qq.com','1.00','2','1435845798');");
E_D("replace into `txdata` values('2','1','','','15901970032','1','1.00','2','1435845994');");
E_D("replace into `txdata` values('3','1','','','15901970032','201455082@qq.com','5.00','2','1435909903');");
E_D("replace into `txdata` values('4','1','','','15901970032','234234@qq.com','1.00','2','1436169993');");
E_D("replace into `txdata` values('5','2','','','15990959898','399123@qq.com','30.00','2','1439797641');");
E_D("replace into `txdata` values('6','1','','方志明','15901970032','2423@qq.com','30.00','1','1439798132');");
E_D("replace into `txdata` values('7','1','','','15901970032','399123@qq.com','120.00','1','1439798310');");
E_D("replace into `txdata` values('8','2','','','15990959898','2234@qq.com','30.00','1','1439798354');");
E_D("replace into `txdata` values('9','2','','','15990959898','399123@qq.com','100.00','1','1439798362');");
E_D("replace into `txdata` values('10','1','','','13725558294','asd@qq.com','33.00','1','1439798585');");
E_D("replace into `txdata` values('11','1','','asd','13725558294','cca@13.com','33.00','1','1439800424');");
E_D("replace into `txdata` values('12','1','','11','13725558294','22','33.00','1','1439801932');");
E_D("replace into `txdata` values('13','19','','12','13725558294','1','33.00','1','1439802138');");
E_D("replace into `txdata` values('14','1','','周','15901970032','15338967031','960.00','2','1440042591');");
E_D("replace into `txdata` values('15','1','','张飞11','15901970032','123213@qq.com1','30.00','2','1440247221');");
E_D("replace into `txdata` values('16','76','1','哈哈哈','15902223333','阿阿斯顿','30.00','2','1440252456');");
E_D("replace into `txdata` values('17','76','1','哈哈哈','15902223333','阿阿斯顿','30.00','1','1440253369');");
E_D("replace into `txdata` values('18','76','1','哈哈哈','15902223333','阿阿斯顿','30.00','1','1440254022');");
E_D("replace into `txdata` values('19','25','0','张力','15990959898','399123@qq.com','200.00','1','1440773673');");
E_D("replace into `txdata` values('20','337','0','王老板','18603051268','15616516115','122.00','2','1440825557');");
E_D("replace into `txdata` values('21','337','0','王老板','18603051268','15616516115','100.00','1','1440825655');");
E_D("replace into `txdata` values('22','95','0','咯一呀','18641953088','18641953085','10000.00','2','1440853865');");
E_D("replace into `txdata` values('23','95','0','咯一呀','18641953088','18641953085','10000.00','2','1440853877');");
E_D("replace into `txdata` values('24','95','0','咯一呀','18641953088','18641953085','1000.00','1','1440853946');");
E_D("replace into `txdata` values('25','95','0','咯一呀','18641953088','18641953085','1000.00','1','1440854012');");
E_D("replace into `txdata` values('26','25','0','张力','15990959898','399123@qq.com','100.00','2','1441007027');");

require("../../inc/footer.php");
?>
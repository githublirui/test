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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8");
E_D("replace into `koudata` values('6','79','189','史上最神秘节目《原来是你》撩开面纱 小沈阳竟当面不识妻子沈春阳！','','0.05','101.226.103.61','2015-08-28','1440720366');");
E_D("replace into `koudata` values('7','58','92','男人画眼线画面太美不敢看 你还敢嫌女友化妆时间长吗？','','0.05','101.226.89.122','2015-08-29','1440804417');");
E_D("replace into `koudata` values('8','58','92','男人画眼线画面太美不敢看 你还敢嫌女友化妆时间长吗？','','0.05','124.167.211.102','2015-08-29','1440804418');");
E_D("replace into `koudata` values('9','58','84','发型 | 早上起来头发像鸟巢的进来！','','0.05','124.167.211.102','2015-08-29','1440804543');");
E_D("replace into `koudata` values('10','58','84','发型 | 早上起来头发像鸟巢的进来！','','0.05','112.64.235.87','2015-08-29','1440804543');");
E_D("replace into `koudata` values('11','58','92','男人画眼线画面太美不敢看 你还敢嫌女友化妆时间长吗？','','0.05','124.167.211.102','2015-08-29','1440804565');");
E_D("replace into `koudata` values('12','58','92','男人画眼线画面太美不敢看 你还敢嫌女友化妆时间长吗？','','0.05','101.226.66.191','2015-08-29','1440805223');");
E_D("replace into `koudata` values('13','79','194','范冰冰演谁都一个样？因为杨贵妃和武媚娘撞妆啦!','','0.02','112.65.193.16','2015-08-29','1440826485');");
E_D("replace into `koudata` values('14','79','196','扩散！这个东西已害死很多人……你也有！','','0.01','124.165.254.222','2015-08-29','1440854886');");
E_D("replace into `koudata` values('15','1282','71','养生最有效的方法！','','0.05','101.226.66.175','2015-08-30','1440875503');");
E_D("replace into `koudata` values('16','1282','71','养生最有效的方法！','','0.05','101.226.33.208','2015-08-30','1440875503');");
E_D("replace into `koudata` values('17','1282','71','养生最有效的方法！','','0.05','101.226.103.62','2015-08-30','1440875504');");
E_D("replace into `koudata` values('18','1282','71','养生最有效的方法！','','0.05','101.226.66.191','2015-08-30','1440880827');");
E_D("replace into `koudata` values('19','1282','71','养生最有效的方法！','','0.05','180.153.214.152','2015-08-30','1440880827');");
E_D("replace into `koudata` values('20','1282','71','养生最有效的方法！','','0.05','117.136.46.205','2015-08-30','1440880827');");
E_D("replace into `koudata` values('21','1282','71','养生最有效的方法！','','0.05','124.254.63.50','2015-08-30','1440898143');");
E_D("replace into `koudata` values('22','79','196','扩散！这个东西已害死很多人……你也有！','','0.01','124.165.73.89','2015-08-30','1440899180');");
E_D("replace into `koudata` values('23','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','106.40.8.147','2015-08-30','1440923141');");
E_D("replace into `koudata` values('24','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','61.148.242.229','2015-08-30','1440923640');");
E_D("replace into `koudata` values('25','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','112.17.243.9','2015-08-30','1440924539');");
E_D("replace into `koudata` values('26','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','1.27.105.61','2015-08-30','1440926199');");
E_D("replace into `koudata` values('27','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','1.27.42.91','2015-08-30','1440928378');");
E_D("replace into `koudata` values('28','375','35','视频疯传！江苏一女中学生不足2分钟被扇脸13次脚踹8次，打人者竟是……','','0.05','106.118.182.153','2015-08-30','1440935159');");
E_D("replace into `koudata` values('29','79','178','当男人说：老婆，我没钱了...这个女人的回复太有才了！','','0.05','112.65.193.16','2015-08-31','1440978475');");
E_D("replace into `koudata` values('30','95','198','☞ 妹纸上门的特殊服务....','','0.05','113.237.240.125','2015-08-31','1440993659');");
E_D("replace into `koudata` values('31','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','27.155.240.132','2015-09-01','1441037992');");
E_D("replace into `koudata` values('32','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','122.139.201.160','2015-09-01','1441053533');");
E_D("replace into `koudata` values('33','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','112.132.23.15','2015-09-01','1441062870');");
E_D("replace into `koudata` values('34','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','61.183.22.135','2015-09-01','1441070784');");
E_D("replace into `koudata` values('35','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','114.88.224.186','2015-09-01','1441077856');");
E_D("replace into `koudata` values('36','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','180.175.168.1','2015-09-01','1441081098');");
E_D("replace into `koudata` values('37','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','61.183.22.135','2015-09-01','1441089231');");
E_D("replace into `koudata` values('38','413','53','如何做辣椒最好吃？几种辣椒的做法，吃货们有福啦~~~','','0.05','101.254.17.165','2015-09-01','1441099727');");

require("../../inc/footer.php");
?>
<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `newsdata`;");
E_C("CREATE TABLE `newsdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8");
E_D("replace into `newsdata` values('7','无需注册微信直接登录','我们无需注册微信登录直接开始你的赚钱之旅');");
E_D("replace into `newsdata` values('9','孩子吃了会致命的8种食物！！','<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">1岁以内别吃蜂蜜！</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	专家提醒！1岁以上宝宝才能吃蜂蜜。因为蜂蜜不易消化完全，往往含有梭状肉毒杆菌芽胞，可引起幼儿肉毒杆菌病，使宝宝精神肌肉失调，甚至呼吸系统瘫痪。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<a href=\"http://https://item.taobao.com/item.htm?spm=a230r.1.14.4.9UmWpf&id=44135116271&ns=1&abbucket=15#detail\" target=\"_blank\"><img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXA5icUYib5zERjCVkBIKUI0NV5gEj4bIrVv0ecbaBpYz9IMtmIIQ83l7ww/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /></a> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">2岁以内不宜喂牛奶</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	2岁以内的宝宝不宜喂鲜牛奶、羊奶，以及成人奶粉，因为婴儿体内消化酶分泌不足或者活性不高，喂牛奶后不一定能消化，会出现消化不良的症状，如呕吐、腹胀、腹泻等。如果不能母乳喂养，应食用婴儿专用配方奶粉。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXAlgAzHBSwPJiazKqPCWdhnT4nLXq0g1gHncWnuUoLdJHfFnTd2PtUB2A/640?wx_fmt=jpeg&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">3岁以前别吃易过敏水果</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	3岁以前的孩子出现食物过敏的几率很大，不适合吃易过敏、刺激性的食物，如水蜜桃、奇异果等表面有绒毛的水果。严重者可引起哮喘。此外，芒果刺激皮肤黏膜，引发口唇部接触性皮炎；菠萝刺激血管，可致口舌发麻，皮肤瘪痒等，同样值得注意。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXA2c781pInJCe5mwI7PrRXKSQhtvqp4oMdCArcrb21oly5icsVuStdG0Q/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">4岁以前别吃糖果、话梅等</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	太甜或者太咸的零食如糖果、话梅不宜给4岁以内的宝宝吃。甜食易引起蛀牙，更重要的是，宝宝口味会影响其成年后饮食习惯，如果习惯摄入口味重的食物、嗜食甜食，成年后易得糖尿病、高血压等与饮食习惯密切相关的慢性疾病。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXA0JZhGbVJrXafD0mbliaBdyyCOW0ficNtcR0RK5w28C8nOmoukWA2ALfw/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">5岁以前别吃螃蟹等海鲜</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	螃蟹等海鲜中含有大量的蛋白质和较高的胆固醇，有过敏体质的孩子容易对其过敏，甚至出现风疹。若孩子患哮喘，吃海鲜会导致哮喘发病。即使孩子没有哮喘和过敏，也要适可而止。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXAVgaQSv9AFhvicNm65SzdwoxqDv061ibL2CJYdCbRuicwH0OxRhkb5NLAw/640?wx_fmt=jpeg&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">6岁以内别喝茶</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	茶中含有咖啡因等可致中枢神经系统兴奋，影响睡眠。而且茶中还含有鞣酸可引起消化道粘膜收缩，与所食蛋白质结合形成凝块，影响营养吸收，并可致消化不良，食欲下降。茶叶中的鞣酸饮后易形成不溶性鞣酸铁，阻碍铁的吸收，易引起贫血。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXALfOje368dGfjlQ7qolBMPuCmSPYT0mF5rGta3sfvzTJW5iaEzcGjzJw/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">7岁以前别喝功能性饮料</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	功能性饮料成分特殊，适合运动员、体力消耗量大的特定人群，在强烈运动、人体大量流汗后饮用。儿童精力和运动量有限，排汗不多，而且功能饮料很多成分不但不适合儿童，还影响他的发育和健康。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXAB8HluFHTEsBkatDhUm5bpiaKicOs9lY9zsB22EktP0uctj3aic51OxnNA/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:700;\">8岁前别吃蚕豆</span>\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	“蚕豆病”是一种6-磷酸葡萄糖脱氢酶缺乏所导致的疾病，多见于8岁左右儿童。使用新鲜蚕豆后突然发生的急性血管内溶血，全身发黄，小便出现酱油色，严重者可引起全身器官衰竭，甚至死亡。\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;text-indent:2em;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p style=\"color:#333333;font-family:''Microsoft YaHei'', Î¢ÈíÑÅºÚ, Tahoma, Helvetica, Arial, 宋体, sans-serif;font-size:14px;background-color:#FFFFFF;\">\r\n	<img src=\"https://mmbiz.qlogo.cn/mmbiz/r5icegejibHQRkOolmBiboH0Hp9gN73BqXARmVJfQKh43Eibq6kY5yOibfQ5Qg4l7459tn2L7Tejph3f8B1TFm8JOJw/640?wx_fmt=png&tp=webp&wxfrom=5\" width=\"auto\" style=\"height:auto;width:auto !important;\" /> \r\n</p>');");
E_D("replace into `newsdata` values('10','111111111111111','11111111111111111111111');");

require("../../inc/footer.php");
?>
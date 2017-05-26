<?php

/**
 * 针对验证的配置
 */
define('CON_PATH_ROOT_PHP', dirname(dirname(dirname(dirname(__FILE__)))) . '/data/verification/config/');

$GLOBALS['category'] = array(
    1 => '宠物',
    2 => '全职招聘',
    3 => '兼职招聘',
    4 => '本地商务服务',
    5 => '本地生活服务',
    6 => '车辆买卖',
    7 => '房产',
    8 => '兼职求职简历',
    9 => '教育培训',
    10 => '票务',
    11 => '全职求职简历',
    12 => '同城活动',
    13 => '同乡/技能交换',
    14 => '二手物品',
);

//$GLOBALS['category_major'] = array(
//	'1_1'=>array(
//			'title' => array('max'=>30,'min'=>6,'checkchar'=>true,'empty'=>true,'type'=>'text','datatype'=>'str','name'=>'标题'),
//			'deal_type' => array('max'=>3,'min'=>0,'checkchar'=>false,'empty'=>true,'type'=>'radio','values'=>'deal_type','datatype'=>'int'),
//			'agent' => array('max'=>3,'min'=>0,'checkchar'=>false,'empty'=>true,'type'=>'radio','values'=>'agent','datatype'=>'int'),
//			'minor_category' => array('max'=>80,'min'=>1,'checkchar'=>false,'empty'=>true,'type'=>'radio','values'=>'minor_category','datatype'=>'int'),
//			'price' => array('max'=>5,'min'=>1,'checkchar'=>false,'empty'=>true,'type'=>'text','datatype'=>'int'),
//			'person' => array('max'=>4,'min'=>2,'checkchar'=>false,'empty'=>true,'type'=>'text','datatype'=>'str'),
//			'phone' => array('max'=>4,'min'=>2,'checkchar'=>false,'empty'=>true,'type'=>'text','datatype'=>'int'),
//			'description' => array('max'=>800,'min'=>0,'checkchar'=>true,'empty'=>true,'type'=>'text','datatype'=>'str'),
//			'parent_id'=>1,
//			'name'=>'宠物狗',
//		),
//);
$GLOBALS['values'] = array(
    'deal_type' => array(0, 1, 2),
    'rent_type' => array(0,1),
    'agent' => array(0, 1, 2),
    'gearbox'=>array(1,2),
    'share_mode'=>array(0,1,2,3,4),
    'license_date'=>array(1992,1993,1994,1995,1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012),
);

define('ATT_ERR_MESSAGE_LEN', '%s 验证失败。长度不正确，应该大于%s小于%s。');
define('ATT_ERR_MESSAGE_SPECIAL', '%s 验证失败。格式错误，不能填写电话和特殊符号。');
define('ATT_ERR_MESSAGE_EMPTY', '%s 验证失败。不能为空。');
define('ATT_ERR_MESSAGE_IN_ARRAY', '%s 验证失败。超出范围，范围%s~%s。');
define('ATT_ERR_MESSAGE_PHONE', '%s 验证失败。应该为正确的电话号码。');
define('ATT_ERR_MESSAGE_TYPE', '%s 验证失败。类型错误，只允许填写%s。');
define('ATT_ERR_MESSAGE_PAIQILIANG', '%s 验证失败。类型错误，最多2位整数，1位小数哦。');
define('ATT_ERR_MESSAGE_ERR', '%s 验证失败。%s');
define('ATT_ERR_MESSAGE_PERSON', '要填写2-6个汉字或字母哦');
define('ATT_ERR_MESSAGE_AGE_1', '要填写2位数字哦');
define('ATT_ERR_MESSAGE_AGE_2', '年龄要满16岁哦');


$GLOBALS['datatype'] = array(
    'int' => '数字',
    'str' => '字符串',
);
?>
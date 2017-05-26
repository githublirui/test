<?php
/**
 * Created by PhpStorm.
 * User: sherlock
 * Date: 14-3-11
 * Time: 下午2:21
 */

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/app/vehicle/model/VehiclePostModel2.class.php';
require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/app/bang/model/BangSecondmarketPromotionAuditModel.class.php';

$ret = BangSecondmarketPromotionAuditModel::getInstance()->getCount('1=1');
var_dump($ret);exit;
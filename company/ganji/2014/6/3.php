<?php

var_dump(file_get_contents('http://game.newthinking.cn/mpapi.html?appid=4'));
die;
$l = 6;
$a = array(0, 5, 12, 5, 4, 65, 7);
rsort($a);

//    var_dump(current($a));
//    die;
//foreach ($a as $i => $c) {
//
//    var_dump(current($a));
////    if($l<$i && $l>) {
////        
////    }
//}
$r = 0;
for ($i = 0; $i < count($a); $i++) {
    $cr = current($a);
    $nx = next($a);
    if ($l < $cr && $l > $nx) {
        $r = $nx;
    }
}
if (!$r) {
    $r = reset($a);
}
//var_dump($r);
//die;

$version = '5.0.3';
$versionItemList = CategoryInfoConfig::$versionItemList[705];
$versionKey = 0;
$versionItemListIds = array();
$cVersionId = '5.0.0'; //客户端版本
for ($i = 0; $i < 3; $i++) {
    $oVersionIds = $versionItemList[$i]; //原始数据
    //1. 如果存在设置的指定版本则返回
    if (array_key_exists($cVersionId, $oVersionIds)) {
        $versionItemListIds = array_merge($versionItemListIds, $oVersionIds[$cVersionId]);
        continue;
    }

    //2. 如果不存在指定的版本，则去寻找最接近的上一个版本
    #记录版本转化成数字的数组
    $versionChangeData = array();
    $verionIdNumIds = array_keys($oVersionIds);
    foreach ($verionIdNumIds as $verionIdNumId) {
        $nversion = Test::getVersionNum($verionIdNumId);
        $versionChangeData[$verionIdNumId] = $nversion;
    }
//    foreach ($oVersionIds as $version => $versionId) {
//        $nversion = Test::getVersionNum($version);
//        $versionIds[$nversion] = $versionId;
//    }


    ksort($versionChangeData);

    $cversionIdNum = Test::getVersionNum($cVersionId);
    $vids = $versionChangeData;
    $vId = 0;
    for ($j = 0; $j < count($vids); $j++) {
        $currentVid = current($vids);
        $nexVid = next($vids);
        if ($cversionIdNum > $currentVid && $cversionIdNum < $nexVid) {
            $vId = $currentVid;
            break;
        }
    }

    //如果区间里没有找到则拿最新的版本
    if ($vId == 0) {
        $vId = reset($vids);
    }
    var_dump($vId);
    die;
    $vId = array_search($vId, $versionChangeData);
    $versionItemListIds = array_merge($versionItemListIds, $oVersionIds[$vId]);
}

print_r($versionItemListIds);
//file_put_contents('tmp.txt', date('H:i:s'), FILE_APPEND);
die;

class Test {

    const v = 13;

    public function getKey() {
        return

                self::v;
    }

    public static function getVersionNum($version) {
        $versionNum = str_replace('.', '', $version);
        $result = $versionNum * 10; //兼容以后四位版本号
        return $result;
    }

}

//$s = '131231split13split11231231';
//var_dump(explode('split', $s));
//die;
//foreach ($data as $k => $v) {
//    var_dump($k);
//    die;
//}
//$test = new Test();
//var_dump($test->getKey());

$rs = array(
    array('install_id' => 1, 'third_party_key' => 5, 'down_time' => 9),
    array('install_id' => 2, 'third_party_key' => 6, 'down_time' => 13),
    array('install_id' => 3, 'third_party_key' => 7, 'down_time' => 12),
    array('install_id' => 4, 'third_party_key' => 8,
        'down_time' => 31),
);

function arrayMultiToSingle($array) {
    $result = array();
    foreach ($array as $value) {
        if (is_array($value)) {
            $result = array_merge($result, arrayMultiToSingle($value));
        } else {
            $result[] = $value;
        }
    }
    return $result;
}

$a = array(array(1, 2, 3), array(6, 7));
var_dump(arrayMultiToSingle($a));
?>
<?php

/**
 * 首页，各大类接口配置信息
 * @author lirui1@ganji.com
 * @version 1.0
 * @copyright ganji.com
 * @package control
 * @since 2014-04-25
 */
class CategoryInfoConfig {

    /**
     * 针对客户端首页，不同版本下发的不同应用列表
     * 第一维为客户端，第二维为首页应用的三段，四维为各版本应用版本id列表
     * @var type 
     */
    public static $versionItemList = array(
        '705' => array(
            '0' => array(
                '5.0.1' => array('0.1.1', '0.1.2', '0.1.3', '0.1.4', '0.1.5', '0.1.6'),
                '5.0.3' => array('0.1.1', '0.1.2', '0.1.3', '0.1.4', '0.1.5', '0.1.6'),
                '5.0.5' => array('0.1.1', '0.1.2', '0.1.3', '0.1.4', '0.1.5', '0.1.6'),
                '5.0.7' => array('0.1.1', '0.1.2', '0.1.3', '0.1.4', '0.1.5', '0.1.6'),
            ),
            '1' => array(
                '0' => array('0.1.9', '0.1.7', '0.1.11', '0.1.14', '0.1.12', '0.1.13', '0.1.8', '0.1.24'),
                '5.0.0' => array('0.1.9', '0.1.7', '0.1.11', '0.1.14', '0.1.12', '0.1.13', '0.1.8', '0.1.15'),
            ),
            '2' => array(
                '0' => array(
                    '0.1.22', '0.1.29', '0.1.15', '0.1.16', '0.1.23', '0.1.25',
                    '0.1.28', '0.1.27', '0.1.19', '0.1.26', '0.1.21', '0.1.20',
                ),
                '5.2.0' => array('0.1.22', '0.1.15', '0.1.16', '0.1.25', '0.1.26', '0.1.28', '0.1.27', '0.1.19'),
                '5.1.0' => array('0.1.22', '0.1.15', '0.1.16', '0.1.25', '0.1.26', '0.1.20', '0.1.27', '0.1.19'),
                '5.0.0/5.0.1' => array('0.1.22', '0.1.16', '0.1.25', '0.1.27', '0.1.26', '0.1.20', '0.1.21', '0.1.19'),
            ),
        ),
        '801' => array(
            '0' => array(
                '0' => array('0.1.1', '0.1.2', '0.1.3', '0.1.4', '0.1.5', '0.1.6'),
            ),
            '1' => array(
                '0' => array('0.1.9', '0.1.7', '0.1.10', '0.1.14', '0.1.11', '0.1.13', '0.1.8', '0.1.24'),
                '5.0.0/5.0.1' => array('0.1.9', '0.1.7', '0.1.10', '0.1.14', '0.1.11', '0.1.13', '0.1.8', '0.1.15'),
            ),
            '2' => array(
                '0' => array(
                    '0.1.30', '0.1.29', '0.1.15', '0.1.16', '0.1.25', '0.1.28',
                    '0.1.19', '0.1.23', '0.1.26', '0.1.20', '0.1.21', '0.1.22'
                ),
                '5.2.0' => array('0.1.15', '0.1.16', '0.1.25', '0.1.23', '0.1.26', '0.1.19', '0.1.22', '0.1.28'),
                '5.1.0' => array('0.1.15', '0.1.16', '0.1.25', '0.1.23', '0.1.26', '0.1.19', '0.1.22', '0.1.20'),
                '5.0.0/5.0.1' => array('0.1.16', '0.1.18', '0.1.17', '0.1.23', '0.1.26', '0.1.19', '0.1.22', '0.1.20'),
            ),
        ),
    ); //对应客户端各版本首页的itemlist ids

    /**
     * 赶集生活 频道 首页 版本控制
     *
     * @author lidanfeng@ganji.com
     *
     */
    public static $categoryVersionItemList = array(
        '7' => array(
            '705' => array(
                '0' => array(
                    '0' => array(
                        '7.1.1',
                        '7.1.2',
                        '7.1.3',
                        '7.1.4',
                    ),
                    '5.2.0' => array(
                        '7.1.1',
                        '7.1.2',
                        '7.1.3',
                        '7.1.5',
                    ),
                ),
                '1' => array(
                    '0' => array(
                        '7.2.1',
                        '7.2.2',
                        '7.2.3',
                        '7.2.4',
                        '7.2.5',
                        '7.2.6',
                        '7.2.7',
                        '7.2.8',
                        '7.2.9',
                        '7.2.10',
                        '7.2.11',
                    ),
                    '5.2.0' => array(
                        '7.2.1',
                        '7.2.2',
                        '7.2.3',
                        '7.2.4',
                        '7.2.5',
                        '7.2.6',
                        '7.2.7',
                        '7.2.8',
                        '7.2.9',
                        '7.2.10',
                        '7.2.11',
                    ),
                ),
            ),
            '801' => array(
                '0' => array(
                    '0' => array(
                        '7.1.1',
                        '7.1.2',
                        '7.1.3',
                        '7.1.4',
                    ),
                    '5.2.0' => array(
                        '7.1.1',
                        '7.1.2',
                        '7.1.3',
                        '7.1.5',
                    ),
                ),
                '1' => array(
                    '0' => array(
                        '7.2.1',
                        '7.2.2',
                        '7.2.3',
                        '7.2.4',
                        '7.2.5',
                        '7.2.6',
                        '7.2.7',
                        '7.2.8',
                        '7.2.9',
                        '7.2.10',
                        '7.2.11',
                    ),
                    '5.2.0' => array(
                        '7.2.1',
                        '7.2.2',
                        '7.2.3',
                        '7.2.4',
                        '7.2.5',
                        '7.2.6',
                        '7.2.7',
                        '7.2.8',
                        '7.2.9',
                        '7.2.10',
                        '7.2.11',
                    ),
                ),
            ),
        ), //one end flag
    );

    /**
     * 针对特定渠道，需要替换指定的应用,key需要替换的itemid,value替换后的itemid
     * @var type 
     */
    public static $AGENCY_REPLACE_ITEMS = array(
        'goapk02' => array(array('0.1.22' => '0.1.21')),
        'kuaibo01' => array(array('0.1.22' => '0.1.21')),
        'qq' => array('5.1.0' => array('0.1.23' =>
                '0.1.31')),
    );

}

?>

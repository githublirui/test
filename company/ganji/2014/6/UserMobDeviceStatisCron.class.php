<?php
/**
 * user_mob_device real 库数据统计脚本
 * /usr/local/webserver/php/bin/php /data/home/chenyihong/www/ganji/ganji_online/mobile_client/app/dev/cron/UserMobDeviceStatisCron.class.php
 * @author chenyihong <chenyihong@ganji.com>
 * @version 2014/05/28
 * @copyright ganji.com
 */

define ( 'NOW_FILE_PATH', dirname ( __FILE__ ) );
include_once NOW_FILE_PATH . '/../../../config/clientConfig.inc.php';
require_once CLIENT_ROOT . '/config/config.inc.php';
require_once GANJI_CONF . '/DBConfig.class.php';

require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/app/base/SqlBuilderNamespace.class.php';

class UserMobDeviceStatis {
    public function run() {
        $this->statis();
    }

    public function statis() {
        $flag = true;
        $limit = 1000;
        $lastId = 0;
        $page = 0;
        $versionStatis = array();
        while($flag) {
            $deviceList = $this->getDeviceList($lastId, $limit);
            if (empty($deviceList)) {
                $flag = false;break;
            }
            if (count($deviceList) < $limit) {
                $flag = false;
            }
            $lastId = $deviceList[count($deviceList) - 1]['id'];
            $page ++;
            // var_dump($page);

            // if ($page > 30) break;

            foreach ($deviceList as $key => $item) {
                if (empty($item)) 
                    continue;
                $versionStatis[$item['versionStr']] ++;
                file_put_contents('/tmp/user_mob_device_other.log', $item['platform'] . "\t" . $item['installID'] . "\t" . $item['versionStr'] . "\n", FILE_APPEND);
            }
            if ($page % 10 == 1) {
                ksort($versionStatis);
                file_put_contents('/tmp/deviceStatis.log', $lastId . "\n" . json_encode($versionStatis) . "\n", FILE_APPEND);
            }
            usleep(10 * 1000);
        }
        ksort($versionStatis);
        file_put_contents('/tmp/deviceStatis.log', $lastId . "\n" . json_encode($versionStatis) . "\n", FILE_APPEND);
    }

    public function getDeviceList($lastId, $limit) {
        $dbHandler = $this->getDbHandle();
        $sqlString = SqlBuilderNamespace::buildSelectSql('user_mob_device', array('id', 'platform', 'installID', 'versionStr'), array(array('id', '>', $lastId), array('platform', 'NOT IN', array(705, 801))), array($limit), array('id' => 'ASC'));
        $result = DBMysqlNamespace::query($dbHandler, $sqlString);
        return $result;
    }

    private static $REAL_MS_CONFIG = array(
        'host' => '192.168.116.20',
        'username' => 'chenyihong',
        'password' => 'p0HmZILRB',
        'port' => 3870,
    );
    // private static $REAL_MS_CONFIG = array(
    //     'host' => '10.3.255.21',
    //     'username' => 'off_dbmob',
    //     'password' => 'off_dbmob_F1F3617',
    //     'port' => 3870,
    // );
    public static $dbHandler = false;
    public function getDbHandle() {
        if (self::$dbHandler == false) {
            self::$dbHandler = DBMysqlNamespace::createDBHandle(self::$REAL_MS_CONFIG, 'widget', DBConstNamespace::ENCODING_UTF8);
        }
        return self::$dbHandler;
    }
}

$instance = new UserMobDeviceStatis();
$instance->run();
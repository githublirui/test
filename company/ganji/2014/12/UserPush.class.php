<?php

/**
 * 判断用户推送
 * @version 2014/12/01
 * @copyright ganji.com
 */
define('NOW_FILE_PATH', dirname(__FILE__));
require_once NOW_FILE_PATH . '/../../../config/config.inc.php';
include_once NOW_FILE_PATH . '/../../../config/clientConfig.inc.php';
require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/app/base/SqlBuilderNamespace.class.php';
require_once CODE_BASE2 . '/app/mobile_client/ClientPushNamespace.class.php';
require_once CODE_BASE2 . '/app/mobile_client/model/console/ClientOperationPushUser.class.php';

class UserPush {

    public static $linePageCount = 10000; //文件行数分页大小
    public static $moduloPageCount = 500; //取模用户id分页大小
    public static $line = 0;
    public static $fileNum = 0;

    public function run() {
        $file = '';
        $GLOBALS['argv']['2'] = sprintf('%02d', $GLOBALS['argv']['2']);
        $argvs = $GLOBALS['argv'];
//        $date = date('Y-m-d');
//        if ($date == '2014-12-02') {
//            $file = NOW_FILE_PATH . '/userids/userid_0';
//        } else if ($date == '2014-12-07') {
//            $file = NOW_FILE_PATH . '/userids/userid_1';
//        } else if ($date == '2014-12-09') {
//            $file = NOW_FILE_PATH . '/userids/userid_2';
//        } else {
//            echo 'push date error';
//            exit;
//        }

        $file = NOW_FILE_PATH . '/userids/userid' . $argvs[1] . '/userid_' . $argvs[1] . '_' . $argvs[2];
        $fileHandle = fopen($file, 'r+');
        $userids = array();
        while (!feof($fileHandle)) {
            $userid = trim(fgets($fileHandle));
            $userids[] = $userid;
            if (count($userids) >= self::$linePageCount) {//分页处理10000条
                $this->linePage($userids); //行分页
                $userids = array();
            }
            self::$line++;
        }
        fclose($fileHandle);
    }

    /**
     * 处理单条分页数据
     * @param type $userIds
     */
    public function linePage($userIds) {
        $modulosUserIds = $this->toModulo($userIds);
        foreach ($modulosUserIds as $moduloUserIds) {
            $this->moduloPage($moduloUserIds); //单个模，分页处理
        }
    }

    /**
     * 单个模 用户数组分页
     * @param type $userIds
     */
    public function moduloPage($userIds) {
        $userIdPages = array_chunk($userIds, self::$moduloPageCount);
        foreach ($userIdPages as $userIdPage) {
            $insertArr = array();
            $insertContent = array();
            $loginDeviceRecords = ClientPushNamespace::getClientsByUserId($userIdPage);
            foreach ($loginDeviceRecords as $loginId => $deviceRecords) {
                foreach ($deviceRecords as $deviceRecord) {
                    if (in_array($deviceRecord['customer_id'], array(705, 801))) {
                        //只给赶集生活推
                        //插入push
                        $file = NOW_FILE_PATH . '/userids/userid' . $GLOBALS['argv'][1] . '/push/pushuser_' . $GLOBALS['argv'][2];
                        file_put_contents($file, $loginId . ' ' . $deviceRecord['install_id'] . ' ' . $deviceRecord['customer_id'] . "\n", FILE_APPEND);
//                        $insertContent[] = $loginId . ' ' . $deviceRecord['install_id'] . ' ' . $deviceRecord['customer_id'];
//                        echo "loginId: " . $loginId . " line:" . self::$line . "\n";
//                        $insertArr[] = '("' . $deviceRecord['install_id'] . '","' . $deviceRecord['customer_id'] . '","13","' . time() . '")';
                    }
                }
            }
//            if (!empty($insertContent)) {
//                $file = NOW_FILE_PATH . '/userids/userid' . $GLOBALS['argv'][1] . '/push/pushuser_' . $GLOBALS['argv'][2];
//                file_put_contents($file, implode("\n", $insertContent) . "\n", FILE_APPEND);
//            }
//            if (count($insertArr) > 0) {
            //插入sql
//              $sql = 'INSERT INTO `client_operation_push_user` (`install_id`,`customer_id`,`user_type`,`create_time`) values';
//              $sql .= implode(',', $insertArr) . ';';
//              $operationDBHandle = ClientOperationPushUser::getDbHandler();
//              DBMysqlNamespace::execute($operationDBHandle, $sql);
//            }
        }
    }

    /**
     * 
     * 按照取模重组数组
     */
    public function toModulo($userIds) {
        $result = array();
        foreach ($userIds as $userId) {
//取模分组
            $modulo = sprintf('%02d', $userId % 100);
            $result[$modulo][] = $userId;
        }
        return $result;
    }

    private static $REAL_MOB_CONFIG = array(
        'host' => 'g1-off-ku-real.dns.ganji.com',
        'username' => 'lirui1',
        'password' => 'a4cff15c',
        'port' => 3870,
    );
    private static $ONLINE_MOB_CONFIG = array(
//线上mob 库
//        'host' => '192.168.116.99',
//        'username' => 'mymob',
//        'password' => 'PvIIJVKxj4GvO8cSEN',
//        'port' => 3870,
//测试环境
//        'host' => '10.3.255.21',
//        'username' => 'off_dbmob',
//        'password' => 'off_dbmob_F1F3617',
//        'port' => 3870,
    );
    public static $slaveDBHandler = false;
    public static $masterDBHander = false;

    public function getDbHandle($slave = true) {
        if ($slave == true) {
            if (self::$slaveDBHandler == false) {
                self::$slaveDBHandler = DBMysqlNamespace::createDBHandle(self::$REAL_MOB_CONFIG, 'widget', DBConstNamespace::ENCODING_UTF8);
            }
            return self::$slaveDBHandler;
        } else {
            if (self::$masterDBHander == false) {
                self::$masterDBHander = DBMysqlNamespace::createDBHandle(self::$ONLINE_MOB_CONFIG, 'widget', DBConstNamespace::ENCODING_UTF8);
            }
            return self::$masterDBHander;
        }
    }

}

$instance = new UserPush();
$instance->run();


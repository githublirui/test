<?php

/**
 * 添加用户推送
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

class InsertUserPush {

    public static $linePageCount = 500; //文件行数分页大小
    public static $line = 0;

    public function run() {
        $file = '';
//        $date = date('Y-m-d');
//        if ($date == '2014-12-03') {
//            $file = NOW_FILE_PATH . '/pushuser/total_push0';
//        } else if ($date == '2014-12-07') {
//            $file = NOW_FILE_PATH . '/pushuser/total_push1';
//        } else if ($date == '2014-12-09') {
//            $file = NOW_FILE_PATH . '/pushuser/total_push2';
//        } else {
//            echo "add push user date error\n";
//            exit;
//        }
        $file = NOW_FILE_PATH . '/pushuser/total_push0';
        $fileHandle = fopen($file, 'r+');
        $userRecords = array();
        while (!feof($fileHandle)) {
            $userRecord = trim(fgets($fileHandle));
            $userRecords[] = $userRecord;
            if (count($userRecords) >= self::$linePageCount) {//分页处理10000条
                $this->insertPage($userRecords); //行分页
                $userRecords = array();
            }
            self::$line++;
        }
        fclose($fileHandle);
    }

    /**
     * @desc 插入一个分页的推送用户
     * @param $userids 用户记录 数组
     * @return 
     */
    public function insertPage($userRecords) {
        $insertArr = array();
        foreach ($userRecords as $userRecord) {
            $userRecordData = explode(" ", $userRecord);
            $loginId = trim($userRecordData[0]);
            $installId = trim($userRecordData[1]);
            $customerId = trim($userRecordData[2]);
            $insertArr[] = '("' . $installId . '","' . $customerId . '","13","' . time() . '")';
        }
        if (!empty($insertArr)) {
            $sql = 'INSERT INTO `client_operation_push_user` (`install_id`,`customer_id`,`user_type`,`create_time`) values';
            $sql .= implode(',', $insertArr) . ';';
            $operationDBHandle = $this->getDbHandle(false);
            DBMysqlNamespace::execute($operationDBHandle, $sql);
            echo self::$line . "\n";
            usleep(100 * 1000);
        }
        return false;
    }

    private static $REAL_MOB_CONFIG = array(
        'host' => 'g1-off-ku-real.dns.ganji.com',
        'username' => 'lirui1',
        'password' => 'a4cff15c',
        'port' => 3870,
    );
    private static $ANA_MOB_MASTER = array(
        'host' => 'sd-ana-ku-m00.dns.ganji.com',
        'username' => 'dbmob',
        'password' => 'dbmobwana',
        'port' => 3888,
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
                self::$slaveDBHandler = DBMysqlNamespace::createDBHandle(self::$ANA_MOB_MASTER, 'widget', DBConstNamespace::ENCODING_UTF8);
            }
            return self::$slaveDBHandler;
        } else {
            if (self::$masterDBHander == false) {
                self::$masterDBHander = DBMysqlNamespace::createDBHandle(self::$ANA_MOB_MASTER, 'ana_mob', DBConstNamespace::ENCODING_UTF8);
            }
            return self::$masterDBHander;
        }
    }

}

$instance = new InsertUserPush();
$instance->run();


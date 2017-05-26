<?php

/**
 * user_mob_device 历史数据恢复
 * /usr/local/webserver/php/bin/php /data/home/chenyihong/www/ganji/ganji_online/mobile_client/app/dev/cron/UserMobDeviceRecoveCron.class.php
 * @author chenyihong <chenyihong@ganji.com>
 * @version 2014/04/24
 * @copyright ganji.com
 */
define('NOW_FILE_PATH', dirname(__FILE__));
include_once NOW_FILE_PATH . '/../../../config/clientConfig.inc.php';
require_once CLIENT_ROOT . '/config/config.inc.php';
require_once GANJI_CONF . '/DBConfig.class.php';

require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/app/base/SqlBuilderNamespace.class.php';

class SyscSubscribeTable {

    public function run() {
        $flag = true;
        $limit = 500;
        $lastId = 10000; //最后id
        $page = 0;

        while ($flag) {
            $list = $this->getList($lastId, $limit);
            if (empty($list)) {
                $flag = false;
                break;
            }
            //分页结束
            if (count($list) < $limit) {
                $flag = false;
            }
            $lastId = $list[count($list) - 1]['subscribeID'];
            $page ++;
            //更新
            $dbHandler = $this->getDbHandle(false);
            foreach ($list as $row) {
                $columnsAndValues = array(
                    'id' => $row['subscribeID'],
                    'conditions' => $row['querys'],
                    'frequency' => $row['frequency'] * 60,
                    'install_id' => $row['userid'],
                    'last_push_time' => $row['lastpushtime'],
                    'last_view_time' => $row['lastviewtime'],
                );
                $sqlString = SqlBuilderNamespace::buildInsertSql('client_subscribe_705', $columnsAndValues);
                $result = DBMysqlNamespace::execute($dbHandler, $sqlString);
            }
            usleep(100 * 1000);
        }
    }

    //从历史表获取出数据记录
    public function getList($lastId, $limit) {
        $dbHandler = $this->getDbHandle(false);
        $sqlString = SqlBuilderNamespace::buildSelectSql('usersubscribe', array('subscribeID', 'frequency', 'userid', 'querys', 'lastpushtime', 'lastviewtime'), //
                        array(array('state', '=', 0), array('subscribeID', '>', $lastId)), array($limit), array('subscribeID' => 'ASC'));
        $result = DBMysqlNamespace::query($dbHandler, $sqlString);
        return $result;
    }

    private static $REAL_MOB_CONFIG = array(
        'host' => '192.168.116.20',
        'username' => 'chenyihong',
        'password' => 'p0HmZILRB',
        'port' => 3870,
    );
    //线上mob 库
    private static $ONLINE_MOB_CONFIG = array(
        'host' => '192.168.116.99',
        'username' => 'mymob',
        'password' => 'PvIIJVKxj4GvO8cSEN',
        'port' => 3870,
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

$instance = new SyscSubscribeTable();
$instance->run();


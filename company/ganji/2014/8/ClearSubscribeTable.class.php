<?php

/**
 * 订阅无效数据清理，conditions数据json格式错误
 * @version 2014/04/24
 * @copyright ganji.com
 */
define('NOW_FILE_PATH', dirname(__FILE__));
include_once NOW_FILE_PATH . '/../../../config/clientConfig.inc.php';
require_once CLIENT_ROOT . '/config/config.inc.php';
require_once GANJI_CONF . '/DBConfig.class.php';

require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/app/base/SqlBuilderNamespace.class.php';

class ClearSubscribeTable {

    public function run() {
        $flag = true;
        $limit = 500;
        $lastId = 1; //最后id
        $page = 0;
        $stat = 0;
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
            $lastId = $list[count($list) - 1]['id'];
            $page ++;
            //更新
            $dbHandler = $this->getDbHandle(false);
            foreach ($list as $row) {
                $conditions = $row['conditions'];
                $conditionsArr = json_decode($conditions, true);
                //无效conditions
                if (!$conditionsArr || !is_array($conditionsArr)) {
                    //更正
                    $conditions = preg_replace('/,\s*,/', ',', $conditions);
                    $conditions = preg_replace('/\}\s*,\s*\]/', '}]', $conditions);
                    $conditions = preg_replace('/\[\s*,\s*\{/is', '[{', $conditions);
                    $columnsAndValues = array(
                        'conditions' => $conditions,
                    );
                    $filters = array(
                        array('id', '=', $row['id']),
                    );
                    $upSql = SqlBuilderNamespace::buildUpdateSql('client_subscribe_705', $columnsAndValues, $filters);
                    $result = DBMysqlNamespace::execute($dbHandler, $upSql);
				
                    echo 'conditions id: ' . $row['id'] . "\n";
                    $stat++;
                }
            }
            usleep(100 * 1000);
        }
//        echo 'Total conditions error:' . $conditionsStat . "\n";
        echo 'Total :' . $stat . "\n";
    }

    //从历史表获取出数据记录
    public function getList($lastId, $limit) {
        $dbHandler = $this->getDbHandle(false);
        $sqlString = SqlBuilderNamespace::buildSelectSql('client_subscribe_705', array('id', 'install_id', 'conditions'), //
                        array(array('id', '=', '2455450')), array($limit), array('id' => 'ASC'));
        $result = DBMysqlNamespace::query($dbHandler, $sqlString);
        return $result;
    }

    private static $REAL_MOB_CONFIG = array(
        'host' => '192.168.116.20',
        'username' => 'chenyihong',
        'password' => 'p0HmZILRB',
        'port' => 3870,
    );
    private static $ONLINE_MOB_CONFIG = array(
        //线上mob 库
//        'host' => '192.168.116.99',
//        'username' => 'mymob',
//        'password' => 'PvIIJVKxj4GvO8cSEN',
//        'port' => 3870,
        //测试环境
        'host' => '10.3.255.21',
        'username' => 'off_dbmob',
        'password' => 'off_dbmob_F1F3617',
        'port' => 3870,
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

$instance = new ClearSubscribeTable();
$instance->run();


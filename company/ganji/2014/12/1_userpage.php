<?php

define('NOW_FILE_PATH', dirname(__FILE__));

/**
 * 
 * 大数据分页处理
 */
class dataPager {

    private static $dbHandle = null;
    private static $table = 'data_pager';

    /**
     * 分页创建表模版sql
     * @var type 
     */
    private static $sqlCreateTemplate = "
CREATE TABLE IF NOT EXISTS `%s_%s` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `data_id` varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='分页表'";

    /**
     * 分页删除模版sql
     * @var type 
     */
    private static $sqlDropTemplate = "DROP TABLE IF EXISTS `%s_%s`;";

    /**
     * 数据文件
     * @var type 
     */
    private static $file = 'userid';

    public static function getDbHandle() {
        if (!self::$dbHandle) {
            $baseModel = new BaseModel();
            self::$dbHandle = $baseModel->getDbHandler('lrtest');
        }
        return self::$dbHandle;
    }

    public function operateTables($action = 'create') {
        $action = ucfirst($action);
        $templateVar = 'sql' . $action . 'Template';
        $sqlTemplate = self::$$templateVar;
        for ($i = 0; $i < 100; $i++) {
            $sql = sprintf($sqlTemplate, self::$table, sprintf('%02d', $i));
            DBMysqlNamespace::execute(self::getDbHandle(), $sql);
        }
    }

    public function insertData() {
        $file = NOW_FILE_PATH . '/' . self::$file;
        if (!file_exists($file)) {
            return;
        }
        $fileHandle = fopen($file, 'r+');
        $line = 1;
        while (!feof($fileHandle)) {
            $data = trim(fgets($fileHandle));
            $insertData = array();
            if ($data) {
                $dataTable = self::$table . '_' . sprintf('%02d', substr($data, strlen($data) - 2));
                $insertData['data_id'] = $data;
                //查询是否存在
                $selectSql = SqlBuilderNamespace::buildSelectSql($dataTable, array('id'), array(array('data_id', '=', $data)));
                $id = DBMysqlNamespace::getOne(self::getDbHandle(), $selectSql);
                if (!$id) {
                    $insertSql = SqlBuilderNamespace::buildInsertSql($dataTable, $insertData);
                    $insertId = DBMysqlNamespace::insertAndGetID(self::getDbHandle(), $insertSql);
                    echo $dataTable . ': ' . $insertId . "  " . $line . "\n";
                }
            }
            $line++;
        }
        fclose($fileHandle);
    }

    /**
     * 判断用户是否是客户端用户
     * @param type $userId
     * @return boolean Description
     */
    public function isCustomerUser($userId) {
        $userId = trim($userId);
        $table = 'user_login_install_' . sprintf('%02d', substr($userId, strlen($userId) - 2));
        $filters = array(
            array('login_id', '=', $userId),
        );
        $sql = SqlBuilderNamespace::buildSelectSql($table, 'id', $filters);
    }

}

$dataPager = new dataPager();
$dataPager->isCustomerUser(3114313);

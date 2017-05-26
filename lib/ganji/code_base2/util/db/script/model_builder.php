<?php

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/util/code/PhpCodeGenerator.class.php';

class ModelBuilder {
    
    /**
     * 根据表名生成类名，例如trade_order => TradeOrderModel
     * @param type $tableName
     * @return string
     */
    private static function _getClassName($tableName) {
        if (empty($tableName)) {
            return '';
        }
        
        $className = '';
        $tableNameParts = explode('_', $tableName);
        foreach ($tableNameParts as $part) {
            $className .= ucfirst($part);
        }
        $className .= 'Model';
        
        return $className;
    }
    
    /**
     * 根据数据库表配置生成model中的fieldTypes配置
     * @param type $dbFields
     * @return type
     */
    private static function _getFieldTypes($dbFields) {
        if (empty($dbFields)) {
            return array();
        }
        
        $fieldTypes = array();
        foreach($dbFields as $field) {
            $fieldType = strpos($field['Type'], 'int') !== FALSE ? 'INT' : 'VARCHAR';
            $fieldTypes[$field['Field']] = $fieldType;
        }
        return $fieldTypes;
    }
    
    /**
     * 批量创建model文件
     * @param type $server 服务器配置，例如DBConfig::$SERVER_MS_SLAVE
     * @param type $database 数据库，例如'beijing'
     * @param type $tableLike 表范围，例如'pet_post'，或者'%_post'
     * @param type $fileDir 放置model的目录
     * @return boolean
     */
    public static function createModels($server, $database, $tableLike, $fileDir = '') {
        //获取表名称
        $dbr = DBMysqlNamespace::createDBHandle($server, $database);
        $sql = "show tables like '{$tableLike}'";
        $tables = DBMysqlNamespace::getAll($dbr, $sql);
        if (empty($tables)) {
            return false;
        }
        
        //为每个表创建model
        foreach ($tables as $tableInfo) {
            //获取表字段
            $tableName  = current($tableInfo);
            $sql = "DESC {$tableName}";
            $dbFields = DBMysqlNamespace::getAll($dbr, $sql);

            //生成代码文件需要的变量
            $dateText = date('Y-m-d H:i:s');
            $className  = self::_getClassName($tableName);

            //fieldTypes
            $fieldTypes = self::_getFieldTypes($dbFields);
            $fieldTypesString = PhpCodeGenerator::genArray($fieldTypes, false, '    ');
            $fieldTypesString = trim($fieldTypesString);
            
            $code = <<<modelCode
<?php
/**
 * 使用单例模式的model
 * @Copyright (c) 2013 Ganji Inc.
 * @date {$dateText}
 * (使用code_base2/util/db/script/model_builder.php生成)
 */

require_once CODE_BASE2 . '/util/db/ModelV5.class.php';

class {$className} extends ModelV5 {
    
    /**
     * 实例
     */
    private static \$_instance = null;
    
    /**
     * 表名
     */
    protected \$tableName = '{$tableName}';
    
    /**
     * 表字段
     */
    protected \$fieldTypes = {$fieldTypesString};
    
    protected function __construct() {
        //require_once GANJI_CONF . '/DBConfig.class.php';
        //\$this->setHandler(DBConfig::\$SERVER_MS_MASTER, DBConfig::\$SERVER_MS_SLAVE, 'ganji_vehicle');
    }
    
    public static function getInstance() {
        if (self::\$_instance instanceof self) {
            return self::\$_instance;
        }
        
        self::\$_instance = new self();
        return self::\$_instance;
    }
}
modelCode;
            
            $filePath = self::_getFilePath($fileDir, $className);
            file_put_contents($filePath, $code);
            echo "{$filePath} 生成成功!\n";
        }
        
        return true;
    }
    
    /**
     * 根据目录和类名创建文件路径
     * @param type $fileDir 目录位置
     * @param type $className 类名
     * @return type
     */
    private static function _getFilePath($fileDir, $className) {
        if (empty($fileDir)) {
            $fileDir = dirname(__FILE__);
        }
        
        return $fileDir . "/{$className}.class.php";
    }
}

$ret = ModelBuilder::createModels(DBConfig::$SERVER_MS_SLAVE, 'beijing', 'vehicle_offer');
echo $ret ? '创建成功' : '创建失败';
exit;

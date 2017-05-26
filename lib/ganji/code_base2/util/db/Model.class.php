<?php

/**
 * @breif model基类
 * @deprecated 因为跟ganji_v3的SqlBuilder等类名冲突，v3v5代码共存时容易报错，因此停止维护，改用code_base2/util/db/ModelV5.class.php
 * @author wangchuanzheng@ganji.com 2013.8
 * http://wiki.corp.ganji.com/二手频道/项目文档/单例模式Model方案
 */

require_once CODE_BASE2 . '/util/db/SqlBuilderV5.class.php';
require_once CODE_BASE2 . '/util/db/SqlBuilder.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';

class Model {
    protected $masterHandler = null;  //主库句柄
    protected $slaveHandler = null;   //从库句柄
    protected $fieldTypes = array();  //字段配置
    protected $tableName = '';        //表名
    protected $sqlBuilder = null;     //sql构造器

    /**
     * 增删改查历史（用于调试）
     */
    protected static $SQL_HISTORY = array();
            
    /**
     * 初始化句柄
     * @param type $dbConfig
     */
    protected function setHandler($masterServer, $slaveServer, $database) {
        if (empty($masterServer) || empty($database)) {
            return false;
        }
        
        if (empty($slaveServer)) {
            $slaveServer = $masterServer;
        }

        $this->masterHandler = DBMysqlNamespace::createDBHandle($masterServer, $database);
        $this->slaveHandler = DBMysqlNamespace::createDBHandle($slaveServer, $database);

        //sql构造器
        $this->sqlBuilder = new SqlBuilderV5($this->tableName, $this->fieldTypes, $this->slaveHandler);

        return true;
    }
    
    /**
     * 构造函数，验证子类是否注册
     */
    protected function __construct() {
        if (empty($this->fieldTypes) || empty($this->tableName)) {
            trigger_error('fieldTypes or tableName should be set');
        }
    }
    
    /**
     * 单例模式不允许拷贝
     */
    private function __clone() {}
    
    /**
     * 获取主库句柄
     * @return type
     */
    protected function getMasterDb() {
        if (empty($this->masterHandler)) {
            trigger_error('master db handler empty');
        }
        return $this->masterHandler;
    }
    
    /**
     * 获取从库句柄
     * @return type
     */
    protected function getSlaveDb() {
        if (empty($this->slaveHandler)) {
            trigger_error('slave db handler empty');
        }
        return $this->slaveHandler;
    }
    
    /**
     * 强制读主库（读完强烈建议releaseForceMaster）
     */
    public function forceMaster() {
        $this->tempSlaveHandler = $this->slaveHandler;
        $this->slaveHandler = $this->masterHandler;
        return $this;
    }
    
    /**
     * 恢复读从库
     */
    public function releaseForceMaster() {
        if (empty($this->tempSlaveHandler)) {
            return false;
        }
        
        $this->slaveHandler = $this->tempSlaveHandler;
    }
    
    /**
     * 插入数据
     * @param type $data
     * @return boolean | int 
     *     false:失败
     *     int : 插入记录对应的id
     */
    public function insert($data) {
        if (empty($data)) {
            return false;
        }
        $sql = $this->sqlBuilder->createInsertSql($data);

        self::addSqlHistory($sql);
        
        return DBMysqlNamespace::insertAndGetID($this->getMasterDb(), $sql);
    }
    
    /**
     * 删除记录
     * @param type $where where条件
     */
    public function delete($where) {
        if (empty($where)) {
            return false;
        }
        
        $sql = $this->sqlBuilder->createDeleteSql($where);

        self::addSqlHistory($sql);
        
        return DBMysqlNamespace::execute($this->getMasterDb(), $sql);
    }
    
    /**
     * 根据id更新数据
     * @param type $id
     * @param type $updateData
     * @return boolean
     */
    public function updateById($id, $updateData) {
        $id = intval($id);
        if (empty($id) || empty($updateData)) {
            return false;
        }
        
        return self::update("id={$id}", $updateData);
    }
    
    /**
     * 更新数据
     * @param type $where 条件
     * @param array $updateData 更新的内容
     * @return boolean
     */
    public function update($where, $updateData) {
        if (empty($where) || empty($updateData)) {
            return false;
        }

        $sql = $this->sqlBuilder->createUpdateSql($updateData, $where);

        self::addSqlHistory($sql);
        
        return DBMysqlNamespace::execute($this->getMasterDb(), $sql);
    }
    
    /**
     * 获取一个字段值
     * @param type $field 字段
     * @param type $where where条件
     *     string e.g. string 'id=3 and create_at<2222222 or type in (2,3)'
     *     array e.g. array('id' => 3, 'type' => 2)
     * @return boolean
     */
    public function getOne($field, $where) {
        if (empty($field) || !is_string($field)) {
            return false;
        }
        
        $row = $this->getRow($where, $field);
        if (!empty($row) && is_array($row)) {
            return current($row);
        }
            
        return $row;
    }
    
    /**
     * 获取数量
     * @param type $where
     * @return type
     */
    public function getCount($where) {
        return intval($this->getOne('count(1)', $where));
    }
    
    /**
     * 获取所有记录
     * @param string|array $where where条件
     *     string e.g. string 'id=3 and create_at<2222222 or type in (2,3)'
     *     array e.g. array('id' => 3, 'type' => 2)
     * @param string $orderBy 排序条件 e.g. 'id desc'
     * @param int $limit 数量
     * @param int $offset 偏移
     * @param string $fields 选择的字段 'id,type,create_at'
     * @return array
     */
    public function getAll($where = '', $orderBy = '', $limit = 0, $offset = 0, $fields = '*') {
        if (empty($fields)) {
            return array();
        }
        
        $sql = $this->sqlBuilder->createSelectSql($fields, $where, $orderBy, $limit, $offset);
        if (empty($sql)) {
            return array();
        }
        
        self::addSqlHistory($sql);
        
        return DBMysqlNamespace::getAll($this->getSlaveDb(), $sql);
    }
    
    /**
     * 根据id获取记录
     * @param type $id
     * @return array 
     */
    public function getRowById($id) {
        $id = intval($id);
        if (empty($id)) {
            return array();
        }
        
        return $this->getRow("id={$id}");
    }
    
    /**
     * 根据where条件获取记录
     * @param string|array $where where条件
     *     string e.g. string 'id=3 and create_at<2222222 or type in (2,3)'
     *     array e.g. array('id' => 3, 'type' => 2)
     * @param string $fields 选择的字段 'id,type,create_at'
     * @param string $orderBy 排序条件 e.g. 'id desc'
     * @return type
     */
    public function getRow($where, $selectField = '*', $orderBy = '') {
        if (empty($where) || empty($selectField)) {
            return array();
        }
        
        if (strstr($selectField, 'count') || strstr($selectField, 'sum')) {
            $sql = $this->sqlBuilder->createSelectSql($selectField, $where);
        } else {
            $sql = $this->sqlBuilder->createSelectSql($selectField, $where, $orderBy, 1);
        }

        self::addSqlHistory($sql);
        
        return DBMysqlNamespace::getRow($this->getSlaveDb(), $sql);
    }
    
    /**
     * 添加到增删改查历史队列
     */
    public static function addSqlHistory($currentSql) {
        if (empty($currentSql)) {
            return false;
        }
        
        //入队列
        self::$SQL_HISTORY[] = $currentSql;
    }
    
    /**
     * 获取增删改查历史队列
     */
    public static function getSqlHistory() {
        return self::$SQL_HISTORY;
    }
}
<?php

/**
 * @breif model基类
 * @author wangchuanzheng@ganji.com 2013.8
 * http://wiki.corp.ganji.com/二手频道/项目文档/单例模式Model方案
 */

require_once CODE_BASE2 . '/util/db/SqlBuilderV5.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';

class ModelV5 {
    protected $masterHandler = null;  //主库句柄
    protected $slaveHandler = null;   //从库句柄
    protected $fieldTypes = array();  //字段配置
    protected $tableName = '';        //表名
    protected $sqlBuilder = null;     //sql构造器
            
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
    public function getList($fields = '*', $where = '', $orderBy = '', $limit = 0, $offset = 0) {
        if (empty($fields)) {
            return array();
        }
        
        $sql = $this->sqlBuilder->createSelectSql($fields, $where, $orderBy, $limit, $offset);
        if (empty($sql)) {
            return array();
        }

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

        return DBMysqlNamespace::getRow($this->getSlaveDb(), $sql);
    }

    /**
     * sql直接查询，暂时只支持读操作，需要写操作的时候再扩展
     * @param $sql
     */
    public function query($sql) {
        if (empty($sql)) {
            return false;
        }
        
        return DBMysqlNamespace::query($this->getSlaveDb(), $sql);
    }
    
}

<?PHP
/** 
 * sql生成器，用于db增删改查时拼凑sql使用
 * ModelV5.class.php开发时衍生品，两者搭配使用疗效好
 * 详见http://wiki.corp.ganji.com/二手频道/项目文档/单例模式Model方案
 * @Copyright (c) 2013 Ganji Inc.
 * @file          /code_base2/util/db/SqlBuilderV5.class.php
 * @author        wangchuanzheng
 * @date          2013-05-4
 */

class SqlBuilderV5 {
    /**
     * @var string 表名称
     */
    private $tableName = '';

    /**
     * @var array 字段配置
     */
    private $fieldTypes = array();

    /**
     * @var object 数据库链接
     */
    private $dbLink = null;

    /**
     * 构造
     * @param $tableName
     * @param $fieldTypes
     * @param $dbLink
     */
    public function __construct($tableName, $fieldTypes, $dbLink) {
        if (empty($tableName) || empty($fieldTypes)) {
            trigger_error('fieldTypes or tableName should be set');
        }

        $this->tableName = $tableName;
        $this->fieldTypes = $fieldTypes;
        $this->dbLink = $dbLink;
    }


    /**
     * 检查字段的类型，确定要不要用边界符
     * @param String $fieldName
     * @return string
     */
    private static function _getFieldTypeMod($fieldName, $fieldTypes) {
        return isset($fieldTypes[$fieldName]) && $fieldTypes[$fieldName] == 'INT' ? '' : "'";
    }

    /**
     * 生成select sql 语句
     * @param string $tableName 表名
     * @param array $fieldTypes 字段注册
     * @param string $field 查询的字段
     * @param array|string $where 条件
     * @param string $orderBy 排序
     * @param int $limit 限制数量
     * @param int $offset 偏移
     * @return string sql语句
     */
    public function createSelectSql($field = '*', $where = '', $orderBy = '', $limit = 0, $offset = 0) {
        //order by
        $orderByPart = empty($orderBy) ? '' : " ORDER BY {$orderBy}";

        //limit
        $limit = intval($limit);
        $limitPart = $limit > 0 ? ' LIMIT ' . intval($offset) . ",{$limit}" : '';

        //where
        $wherePart = $this->createWhereSql($where);
        return "SELECT {$field} FROM {$this->tableName}{$wherePart}{$orderByPart}{$limitPart}";
    }

    /**
     * 生成插入sql
     * @param array $data 插入的数据
     * @param array $fieldTypes 字段注册
     * @param string $tableName 表明
     * @return string sql语句
     */
    public function createInsertSql($data) {
        if (empty($data)) {
            return '';
        }

        //过滤字段
        self::filterFields($data, $this->fieldTypes);

        $sqlFields = array();
        $sqlVaules = array();
        foreach ($data as $fieldName => $value) {
            $sqlFields[] = "`{$fieldName}`";
            $mod = self::_getFieldTypeMod($fieldName, $this->fieldTypes);
            $sqlVaules[] = $mod . mysqli_real_escape_string($this->dbLink, $value) . $mod;
        }

        $insertFields = implode(', ', $sqlFields);
        $insertValues = implode(', ', $sqlVaules);
        return "INSERT INTO `{$this->tableName}` ({$insertFields}) VALUES ({$insertValues})";
    }

    /**
     * 创建更新sql语句
     * @param type $setData 更新的数据
     * @param type $where 条件
     * @param type $fieldTypes 字段注册
     * @param type $tableName 表名
     * @return boolean
     */
    public function createUpdateSql($setData, $where) {
        if (empty($setData) || empty($where)) {
            return false;
        }

        $setPart = $this->createSetSql($setData);
        if (empty($setPart)) {
            return false;
        }

        $wherePart = $this->createWhereSql($where);
        if (empty($wherePart)) {
            return false;
        }

        return "UPDATE {$this->tableName} {$setPart} {$wherePart}";
    }

    /**
     * 删除数据
     * @param $where WHERE
     * @return String sql
     */
    public function createDeleteSql($where) {
        if (empty($where)) {
            return false;
        }

        $wherePart = $this->createWhereSql($where);
        if (empty($wherePart)) {
            return false;
        }

        return "DELETE FROM {$this->tableName} {$wherePart}";
    }

    /**
     *
     * 生成Where条件
     * @param String
     */
    public function createWhereSql($where) {
        if (empty($where)) {
            return '';
        }

        if (is_string($where)) {
            return " WHERE {$where}";
        }

        //根据字段过滤
        self::filterFields($where, $this->fieldTypes);

        //拼凑
        $keyValueParts = array();
        foreach ($where as $fieldName => $value) {
            $mod = self::_getFieldTypeMod($fieldName, $this->fieldTypes);
            $keyValueParts[] = self::_checkField($fieldName) . "=" . $mod . mysqli_real_escape_string($this->dbLink, $value) . $mod;
        }

        $keyValueStr = implode(' AND ', $keyValueParts);
        return " WHERE {$keyValueStr}";
    }

    /**
     *
     * 生成Set语句
     * @param String or Array $data
     */
    protected function createSetSql($setData) {
        if (empty($setData)) {
            return '';
        }

        if (is_string($setData)) {
            return " SET {$setData}";
        }

        //根据字段过滤
        self::filterFields($setData, $this->fieldTypes);

        //拼凑
        $keyValueParts = array();
        foreach ($setData as $fieldName => $value) {
            $mod = self::_getFieldTypeMod($fieldName, $this->fieldTypes);
            $keyValueParts[] = self::_checkField($fieldName) . "=" . $mod . mysqli_real_escape_string($this->dbLink, $value) . $mod;
        }

        $keyValueStr = implode(',', $keyValueParts);
        return " SET {$keyValueStr}";
    }

    /**
     *
     * 过滤没用的字段，以及根据类型判断是否合格
     * @param Array $fields
     */
    protected static function filterFields(&$data, $fieldTypes) {
        if (empty($fieldTypes)) {
            return false;
        }

        $validData = array();
        foreach ($data as $key => $value) {
            //未注册的字段丢弃掉
            if (!isset($fieldTypes[$key])) {
                continue;
            }

            //根据类型验证
            if ($fieldTypes[$key] == 'INT') {
                if (!is_numeric($value)) {
                    continue;
                }
                $value = intval($value);
            } elseif ($fieldTypes[$key] == 'VARCHAR') {
                if (!is_string($value)) {
                    continue;
                }
            } else {
                continue;
            }
            
            $validData[$key] = $value;
        }
        
        $data = $validData;
        return true;
    }

    /**
     * 
     * 检查字段名
     * 过滤掉特殊字符（不包含.） 得到合法的字符
     * @param String $field
     */
    private static function _checkField($fieldName) {
        $fieldName = preg_replace('/[^\w\.]/', '', $fieldName);
        
        $pos = strpos($fieldName, '.');
        if($pos > 0){
            $fieldName = str_replace('.', "`.`", $fieldName);
        } elseif($pos === 0){
            $fieldName = substr($fieldName, 1);
        }

        return "`{$fieldName}`";
    }
    
}

<?php

/**
 * B2S生成Data Model 类
 * @author lirui
 */
class GenerateDataModel {

    private $dataDir = 'G:/projects/lirui_dev/51talk/App/B2s/Data'; //data 目录
    private $modelDir = 'G:/projects/lirui_dev/51talk/App/B2s/Model'; //model 目录
    private $author = 'lirui'; //作者
    private $table = ''; //表名
    private $desc = ''; //model 说明
    private $dataSample = '<?php

/**
 * {$tableU}
 * {$desc}
 * @author    {$author} <{$author}@51talk.com>
 * @since     {$date}
 * @copyright Copyright (c) 2012-2016 51talk Inc. (http://www.51talk.com)
 */
class Data_{$tableU} extends Data {

    /**
     * 数据表定义
     * @return string
     */
    public function getTableName() {
        return \'b2s.{$table}\';
    }

    /**
     * 根据条件获取单条数据信息
     *
     * @param $where 条件
     * @param string $field
     * @return array 用户信息
     */
    public function getDataInfo($where, $field = \'*\') {
        return $this->getRow($where, $field);
    }

    /**
     * 获取所有条件的列表
     *
     * @param string $where 查询条件
     * @param string $field 查询字段
     * @param string $order 排序条件
     * @param string $limit 过滤数据条数
     * @param string $group group分组
     * @return array|boolean
     */
    public function getDataList($where = \'\', $field = \'*\', $order = \'\', $limit = \'\', $group = \'\') {
        return $this->getRows($where, $field, $order, $limit, $group);
    }

    /**
     * 添加数据
     *
     * @author lirui@51talk.com
     * @param array $data
     * @return number
     */
    public function add(array $data) {
        return $this->addRow($data);
    }

    /**
     * 添加数据
     *
     * @param array $data
     * @return number
     */
    public function updateData($where, array $data) {
        return $this->update($where, $data);
    }

    /**
     * 查询一行数据
     *
     * @param mixed $condtitions 条件
     * @param string $field
     * @param string $order
     * @param string $group
     * @return mixed
     */
    public function findRow($condtitions, $field = \'*\', $order = null, $group = null) {
        return $this->getRow($condtitions, $field, $order, $group);
    }

    /**
     *
     * @param $sql
     * @return mixed
     */
    public function queryData($sql, $type = \'slave\') {
        return $this->query($sql, $type);
    }

    /**
     * 
     * @param type $where
     * @param type $field
     * @return type
     */
    public function getCount($where, $field = \'*\') {
        return $this->count($where, $field);
    }

}
';
    private $modelSample = '<?php

/**
 * {$tableU} 
 * @author {$author} <{$author}@51talk.com>
 * @since  {$date}
 * @copyright Copyright (c) 2012-2016 51talk Inc. (http://www.51talk.com)
 */
class Model_{$tableU} extends DataModel {

    private $tb = \'\';

    public function __construct() {
        $this->tb = new Data_{$tableU}();
    }

    /**
     * 添加
     * @param $wheres array|string
     * @param $field string 字段
     * @return string|integer
     */
    public function add($data) {
        if (!$data) {
            return false;
        }
        return $this->tb->add($data);
    }

    /**
     * 获取数量
     * @param mixed $conditions
     * @return number
     */
    public function getCount($condition) {
        return $this->tb->getCount($condition);
    }

    /**
     * 
     * @param type $id
     * @param type $data
     */
    public function updateById($id, $data) {
        if (!$id) {
            return;
        }
        if (is_array($id)) {
            $condition = "id in (" . implode(\',\', $id) . ")";
        } else {
            $condition = [\'id\' => $id];
        }
        return $this->tb->updateData($condition, $data);
    }

    /**
     * 通过id
     * @param type $id
     */
    public function getById($id) {
        $result = array();
        if (!$id) {
            return $result;
        }
        if (is_array($id)) {
            $idsStr = implode(\',\', $id);
            $condition = "id in($idsStr)";
            $rows = $this->tb->getDataList($condition);
            foreach ($rows as $row) {
                $result[$row[\'id\']] = $row;
            }
        } else {
            $condition = "id = $id";
            $result = $this->tb->getDataInfo($condition);
        }
        return $result;
    }
}
';

    public function setDataDir($dir) {
        $this->dataDir = $dir;
    }

    public function setModelDir($dir) {
        $this->modelDir = $dir;
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function setDesc($desc) {
        $this->desc = $desc;
    }

    private function _getTableU($table) {
        $tableUArr = explode('_', $this->table);
        $tableUArr = array_map(function($v) {
            return ucfirst($v);
        }, $tableUArr);
        return implode('', $tableUArr);
    }

    public function generate() {
        $this->generateData();
        $this->generateModel();
    }

    public function generateModel() {
        $tableU = $this->_getTableU($this->table);
        $params = [
            'table' => $this->table,
            'tableU' => $tableU,
            'author' => $this->author,
            'date' => date('Y-m-d'),
            'desc' => $this->desc,
        ];
        $result = $this->modelSample;
        foreach ($params as $parm => $paramValue) {
            $parmReplace = '{$' . $parm . '}';
            $result = str_replace($parmReplace, $paramValue, $result);
        }
        file_put_contents($this->modelDir . '/' . "Model_{$tableU}.class.php", $result);
        echo "生成表{$this->table} Model 成功\n";
    }

    public function generateData() {
        $tableU = $this->_getTableU($this->table);

        $params = [
            'table' => $this->table,
            'tableU' => $tableU,
            'author' => $this->author,
            'date' => date('Y-m-d'),
            'desc' => $this->desc,
        ];
        $result = $this->dataSample;
        foreach ($params as $parm => $paramValue) {
            $parmReplace = '{$' . $parm . '}';
            $result = str_replace($parmReplace, $paramValue, $result);
        }
        file_put_contents($this->dataDir . '/' . "Data_{$tableU}.class.php", $result);
        echo "生成表{$this->table} Data 成功\n";
    }

}

$GenerateDataModel = new GenerateDataModel();
$GenerateDataModel->setTable('b2s_agent_sales_stat'); //设置表名
$GenerateDataModel->setDesc('销售目标设定'); //设置说明
$GenerateDataModel->generate(); //开始生成

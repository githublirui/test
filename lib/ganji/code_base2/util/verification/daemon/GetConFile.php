<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetConFile
 *
 * @author liuumine
 */
include_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/stdafx.php';

define('CON_PATH_ROOT_V', dirname(dirname(dirname(dirname(__FILE__)))) . '/data/verification/config/');

class GetConFile extends data {

    public $tablename = 'verification';
    public $tablename_mojor = 'category_major';

    public function __construct($category_id) {
        $this->getmajor($category_id);
    }

    public function getDbCon($category_major_id) {
        $sql = "SELECT * FROM {$this->tablename} WHERE category_major_id ='{$category_major_id}'";
        $db = $this->_connectDb();
        $st = $db->prepare($sql);
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $v) {
            $newRes [$v['option']] = $v;
        }
        return $newRes;
    }

    private function getmajor($category_id) {

        $sql = "SELECT * FROM {$this->tablename_mojor} WHERE parent_id = '{$category_id}'";
        $db = $this->_connectDb();
        $st = $db->prepare($sql);
        $st->execute();
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $v) {
            $result = $this->getDbCon($v['category_id']);
            if (0 == count($result)) {
                $result = $this->getDbCon($this->getDefineId($category_id));
            }
            $result['name'] = $v['category_name'];
            $result['parent_id'] = $v['parent_id'];
            $fileArr[$category_id . '_' . $v['category_id']] = $result;
        }
        $content = serialize($fileArr);
        $this->writeFile($category_id, $content);
    }

    private function writeFile($filename, $content) {

        if (!is_dir(CON_PATH_ROOT_V))
            mkDirs(CON_PATH_ROOT_V);
        $ft = @fopen(CON_PATH_ROOT_V.$filename, 'w');
        fwrite($ft, $content);
        fclose($ft);
    }

    /**
     * 获得DB资源
     *
     * @param 主辅库 $master
     * @return resource
     */
    private function _connectDb($master = 1) {
        $db = $this->connectDb('app.v', $master);
        return $db;
    }
    
    private function getDefineId($category_id){
        $array = array(
            1=>1,
            14=>148,
            6=>19,
            7=>9,
        );
        return $array[$category_id];
    }

}

new GetConFile(1);
new GetConFile(14);
new GetConFile(6);
new GetConFile(7);
?>

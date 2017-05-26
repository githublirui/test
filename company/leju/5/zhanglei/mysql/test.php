<?php
if(file_exists('./Db.class.php')){
    include_once('./Db.class.php');
}else{
    throw new Exception('mysqli类不存在');
}

$conf = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'root',
    'name' => 'demo',
    'port' => 3306
);
$mysqli = Db::getInstance($conf);

$_table = 'images';
$data = array(
    'path' => 'a',
    'hash' => 'v'
);
$where = array(
    'id' => 1
);
$field = array(
    '*'
);
$result = $mysqli->delete($_table, $where);
print_r($result);

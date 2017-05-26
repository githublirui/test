<?php
session_start();
if(file_exists('../../autoload/loader.class.php')){
    include_once('../../autoload/loader.class.php');
}else{
    throw new Exception('loader.class.php is not exists');
}
spl_autoload_register(array('loader', 'loadClass'));

$mongodb = MongoHelper::getInstance();
$connection = $mongodb->getCollection();
?>
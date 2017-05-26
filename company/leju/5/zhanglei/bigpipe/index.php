<?php
// 定义常量
define('APP_PATH', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('CONFIG', APP_PATH . DS . 'config');

// 加载autoload类
if (!file_exists('../autoload/loader.class.php')) 
{
    throw new Exception('autoload类文件不存在');
}
include_once('../autoload/loader.class.php');
spl_autoload_register(array('Loader', 'loadClass'));

// 实例化渲染类
$render		= new Render();

// 实例化大骨架类 
$pagelet	= new pageletSkeleton();

// 渲染
$render->renderPage($pagelet);


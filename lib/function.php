<?php

function test_dispath() {
    $sapi = php_sapi_name();

    $path = @$_SERVER['REQUEST_URI'];
    $pathArr = explode('?', $path);
    $filePath = @$pathArr[1];

    defined("URL_PATH") or define('URL_PATH', "/" . substr($filePath, 0, strrpos($filePath, "/")) . "/");

    $paras_arr = explode("&", $filePath);
    $filePath = (TEST_PATH . '/' . urldecode($paras_arr[0]));
    $path = $filePath;
    if ($sapi == "cli") {
        //命令行执行
        $path = $GLOBALS['argv'][1];
        $path = realpath(TEST_PATH . '/' . $path);
        $GLOBALS['now_path'] = dirname($path);
    }

    if (is_dir($path)) {
        $path = $path . DIRECTORY_SEPARATOR . "index.php";
    }
    $path = iconv("UTF-8", "GBK//IGNORE", $path);
    $GLOBALS['now_path'] = dirname($path);
    if (file_exists($path) && is_file($path)) {
        include $path;
    } else {
        throw new Exception($path . ' file not found');
    }
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: zhaoweiguo
 * Date: 14-5-15 PM3:04
 * @brief  检查项目中是否含有echo、var_dump、todo标签等测试代码
 */



require_once dirname(__FILE__) . "/../../SF_Bootstrap.php";


$baseFolder = FINAGLE_BASE;
$ignoreFiles = array(".", "..", "test", "unittest", ".git", ".gitignore", "SF_Bootstrap", ".idea");

$keywords = array("echo", "var_dump");

if(!is_dir($baseFolder)) {
    exit('not found the finagle base folder!\n');
}


if($dh = opendir($baseFolder)) {

    while (($file = readdir($dh)) !== false) {

        if(in_array($file, $ignoreFiles)) {
            continue;
        }
        echo "checking $file:  ...\n";

        foreach ($keywords as $keyword) {

            echo $keyword."(";

            $exist = exec("grep -R '$keyword' " . $baseFolder . "/" . $file);
            if(empty($exist)) {
                echo "pass) ";
            } else {
                echo "fail)!\nReason:" . $exist . "\n";
                closedir($dh);
    //                break; 
            }
        }
        echo "\n===================";

        echo "\n";

    }
}
closedir($dh);










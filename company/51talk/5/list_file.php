<?php
$s = 4;
switch ($s){
                case 1:
                    echo 1;
                    break;
                case 2:
                    echo 2;
                    break;
                case 3:
                case 4:
                case 100:
                    echo 666;
}
die;
ini_set("display_errors", 0);
set_time_limit(0);

/**
 * 遍历文件，查找类
 */
function read_dir_content($parent_dir, $depth = 0) {
    static $result = array();
    static $f = 0;
//    $str_result .= "<li>" . dirname($parent_dir) . "</li>";
//    $str_result .= "<ul>";
    if ($handle = opendir($parent_dir)) {
        while (false !== ($file = readdir($handle))) {
            if (in_array($file, array('.', '..')))
                continue;
            if (is_dir($parent_dir . "/" . $file)) {
                read_dir_content($parent_dir . "/" . $file, $depth++) . "</li>";
            }
            if (is_file($parent_dir . "/" . $file)) {
                $model_str = file_get_contents($parent_dir . "/" . $file);
                $model_pattern = "/Load\:\:loadModel\(\s*['|\"](?!B2S)\w+['|\"]\s*\)/is";
                preg_match_all($model_pattern, $model_str, $matches);
                if ($matches[0]) {
                    foreach ($matches[0] as $matche) {
                        $fpath = str_replace("G:/projects/b2s/", "", $parent_dir . "/" . $file);
                        $result[$fpath][] = $matche;
                        $f++;
                    }
                }
            }
        }
        closedir($handle);
    }
    return $result;
}

$models = read_dir_content("G:/projects/b2s/App/B2s");
var_dump($models);
die;
?>

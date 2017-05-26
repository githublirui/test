<?php

/**
 * PHP 汉字转拼音 
 * @author Jerryli(hzjerry@gmail.com) 
 * @version V0.20140715 
 * @package SPFW.core.lib.final 
 * @global SEA_PHP_FW_VAR_ENV 
 * @example 
 *  echo CUtf8_PY::encode('阿里巴巴科技有限公司'); //编码为拼音首字母 
 *  echo CUtf8_PY::encode('阿里巴巴科技有限公司', 'all'); //编码为全拼音 
 */
class CUtf8_PY {



}

$ret = CUtf8_PY::encode('马池口小学奤夿屯','all');
var_dump($ret);
die;
?> 
<?php

function __mb_autoload($class_name) {
    $file = MB_ROOT . "/source/{$class_name}.class.php";
    require_once $file;
}
spl_autoload_register('__mb_autoload');

function inputRaw($jsonDecode = true) {
    $post = file_get_contents('php://input');
    if($jsonDecode) {
        $post = @json_decode($post, true);
    }
    return $post;
}

function coll_key($ds, $key) {
    if(!empty($ds) && !empty($key)) {
        $ret = array();
        foreach($ds as $row) {
            $ret[$row[$key]] = $row;
        }
        return $ret;
    }
    return array();
}

function coll_neaten($ds, $key) {
    if(!empty($ds) && !empty($key)) {
        $ret = array();
        foreach($ds as $row) {
            $ret[] = $row[$key];
        }
        return $ret;
    }
    return array();
}

function coll_walk($ds, $callback, $key = null) {
    if(!empty($ds) && is_callable($callback)) {
        $ret = array();
        if(!empty($key)) {
            foreach($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row[$key]);
            }
        } else {
            foreach($ds as $k => $row) {
                $ret[$k] = call_user_func($callback, $row);
            }
        }
        return $ret;
    }
    return array();
}

/**
 * 该函数从一个数组中取得若干元素。
 * 该函数测试（传入）数组的每个键值是否在（目标）数组中已定义；
 * 如果一个键值不存在，该键值所对应的值将被置为FALSE，
 * 或者你可以通过传入的第3个参数来指定默认的值。
 *
 * @param array $keys 需要筛选的键名列表
 * @param array $src 要进行筛选的数组
 * @param mixed $default 如果原数组未定义某个键，则使用此默认值返回
 * @return array
 */
function coll_elements($keys, $src, $default = false) {
    $return = array();
    if(!is_array($keys)) {
        $keys = array($keys);
    }
    foreach($keys as $key) {
        if(isset($src[$key])) {
            $return[$key] = $src[$key];
        } else {
            $return[$key] = $default;
        }
    }
    return $return;
}

/**
 * 判断一个数是否介于一个区间或将一个数转换为此区间的数.
 *
 * @param string $num 输入参数
 * @param int $downline 参数下限
 * @param int $upline 参数上限
 * @param string $returnNear 对输入参数是判断还是返回
 * @return boolean | number
 * <pre>
 *  boolean 判断输入参数是否介于 $downline 和 $upline 之间
 *  number 将输入参数转换为介于  $downline 和 $upline 之间的整数
 * </pre>
 */
function util_limit($num, $downline, $upline, $returnNear = true) {
    $num = intval($num);
    $downline = intval($downline);
    $upline = intval($upline);
    if($num < $downline){
        return empty($returnNear) ? false : $downline;
    } elseif ($num > $upline) {
        return empty($returnNear) ? false : $upline;
    } else {
        return empty($returnNear) ? true : $num;
    }
}

/**
 * 将一个数组转换为 XML 结构的字符串
 *
 * @param array $arr 要转换的数组
 * @param int $isRoot 节点层级, 1 为 Root.
 * @return string XML 结构的字符串
 */
function util_2xml($arr, $isRoot = 1) {
    $s = $isRoot == 1 ? "<xml>" : '';
    foreach($arr as $tagname => $value) {
        if (is_numeric($tagname)) {
            $tagname = $value['TagName'];
            unset($value['TagName']);
        }
        if(!is_array($value)) {
            $s .= "<{$tagname}>".(!is_numeric($value) ? '<![CDATA[' : '').$value.(!is_numeric($value) ? ']]>' : '')."</{$tagname}>";
        } else {
            $s .= "<{$tagname}>" . util_2xml($value, $isRoot + 1)."</{$tagname}>";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    return $isRoot == 1 ? $s."</xml>" : $s;
}


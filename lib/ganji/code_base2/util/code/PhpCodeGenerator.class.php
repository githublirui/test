<?php
/**
 * php代码生成器（按照ganji代码风格生成）
 * wangchuanzheng@ganji.com
 * 2013-1-7
 */

class PhpCodeGenerator {
    /**
     * 列表形式 array(1,2,3,4);
     */
    const ARRAY_TYPE_LIST = 1;
    /**
     * map形式
     * array(1 => 1, 2 => 2, 3 => 3);
     */
    const ARRAY_TYPE_MAP = 2;
    
    /**
     * 根据PHP数组生成代码
     * @param array $array php数组
     * @param bool $stringInsteadNumber 是否使用字符串表示数值，例如123转成'123'
     * @param string $linePrefix 每行前缀，例如'    ' 用于缩进对齐
     * @return string php 数组代码
     */
    public static function genArray($array, $stringInsteadNumber = false, $linePrefix = '') {
        if (empty($array)) {
            return is_array($array) ? 'array()' : '';
        }
        
        $ret = "{$linePrefix}array(\n";
        $arrayType = self::getArrayType($array);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $arrayLines = self::genArray($value, $stringInsteadNumber);
                $arrayLines = explode("\n", $arrayLines);
                foreach ($arrayLines as $k => &$line) {
                    if ($k > 0) {
                        $line = "{$linePrefix}    {$line}";
                    }
                }
                $value = join("\n", $arrayLines);
            } elseif (!is_numeric($value) || $stringInsteadNumber) {
                $value = "'{$value}'";
            }
            
            if ($arrayType == self::ARRAY_TYPE_LIST) {
                $ret .= "{$linePrefix}    {$value},\n";
            } else {
                $key = !$stringInsteadNumber && is_numeric($key) ? $key : "'{$key}'";
                $ret .= "{$linePrefix}    {$key} => {$value},\n";
            }
        }
        $ret .= "{$linePrefix})";
        return $ret;
    }
    
    public static function getArrayType($arrayContent) {
        $keys = array_keys($arrayContent);
        if (empty($keys)) {
            return self::ARRAY_TYPE_LIST;
        }
        
        foreach ($keys as $k => $v) {
            if ($k != $v) {
                return self::ARRAY_TYPE_MAP;
            }
        }
        
        return self::ARRAY_TYPE_LIST;
    }
    
}

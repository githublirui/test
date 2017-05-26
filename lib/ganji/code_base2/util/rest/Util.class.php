<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author yyquick
 */
class Util {

    public static function getParamArray($name, $array, $default = array()) {
        $ret = array();
        $pattern = "/{$name}\[(.*)\]/";
        $isPattern = false;
        foreach ($array as $key => $value) {
            if (preg_match($pattern, $key, $matchs)) {
                $ret[$matchs[1]] = $value;
                $isPattern = true;
            }
        }
        if ( !$isPattern )
        	$ret = $default;
        return $ret;
    }

    public static function encodeArray($args) {
        $out = '';
        foreach ($args as $name => $value) {
            if ( is_array($value) )
            {
            	foreach ( $value as $key => $val )
            	{
            		if ( isset($val) )
            			$out .= "{$name}[{$key}]=".urlencode($val)."&";
            	}
            }
        	else
        	{
        		if ( isset($value) )
        			$out .= urlencode($name) . '=' . urlencode($value).'&';
        	}
        }
        $out = substr($out, 0, -1);
        return $out;
    }

}

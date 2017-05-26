<?php
/**
 * 表单验证基配置类
 * 
 * @author longweiguo
 * @version 2009-06-03
 */

class ValidatorConfig
{
	//不为空的验证模式
    const MODE_REQUIRED       = 1;
    
    //字串长度验证模式
    const MODE_LENGTH         = 2;
    
    //同其它表单字段值对比的验证模式
    const MODE_COMPARE_FIELD  = 3;
    
    //同指定值对比的验证模式
    const MODE_COMPARE_VALUE  = 4;
    
    //正则表达式验证模式
    const MODE_REGEXP         = 5;
    
    //ajax验证模式
    const MODE_AJAX           = 6;
    
    //自定义验证模式
    const MODE_CUSTOM         = 7;
    
    static $modes = array(
        self::MODE_REQUIRED,
        self::MODE_LENGTH,
        self::MODE_COMPARE_FIELD,
        self::MODE_COMPARE_VALUE,
        self::MODE_REGEXP,
        self::MODE_AJAX,
        self::MODE_CUSTOM,
    );
    
    
    //行内显示错误信息
    const SHOW_ERROR_INLINE   = 1;
    
    //alert方式显示错误信息
    const SHOW_ERROR_ALERT    = 2;	
    
    
    //按每个字计算长度
    const STRLEN_SYMBOL       = 'symbol';
    
    //按每个字节计算长度
    const STRLEN_BYTE         = 'byte';
}

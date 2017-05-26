<?php
/** 
 * 生成表单字段对象工厂类
 * @file code_base2/util/from/field/FieldFactory.class.php
 * @author longweiguo
 * @date 2011-03-21
 * @version 2.0
 *
 * 通过此类来创建表单字段对象
 */

require_once dirname(__FILE__) . '/ValidateField.class.php';

/**
 * 生成表单字段对象工厂类
 * @class FieldFactory
 *
 * 通过此类来创建表单字段对象
 */
class FieldFactory
{
	private static $fields = array();
	
	protected static $lowerStringAttrs = array(
        'name'    => 1,
        'id'      => 1,
        'value'   => 1,
        'type'    => 1,
    );
	
    /**
     * 根据字段名，取得表单字段对象
     *
     * @param string $fieldName 表单字段的name
     * @return object
     */
	public static function & getField($fieldName)
	{
		if (isset(self::$fields[$fieldName])){
			return self::$fields[$fieldName];
		}
		return NULL;
	}
	
	public static function getFields()
	{
		return self::$fields;
	}
	
	public static function fieldExists($fieldName)
	{
		return (isset(self::$fields[$fieldName])) ? true : false;
	}
	
    /**
     * 创建一个表单字段对象
     *
     * @param array $params ['name'=>'username', 'type'=>'text' ...] 要创建的表单字段的属性
     * @return object 返回生成的表单字段对象
     */
	public static function & creatField($params)
	{
	    if (is_string($params)){
	    	$params = array('name' => $params);
	    }
	    
		if (!is_array($params)){
	    	die('[FieldFactory::creatField] Create field error. $params is not an array.');
	    }
		
		self::formatParams($params);
		
		if (empty($params['name'])){
			die('[FieldFactory::creatField] Create field error. Field name should be set.');
		}
        
		$name = $params['name'];
        if (isset(self::$fields[$name])){
            self::$fields[$name]->setAttributes($params);
        }
        else {
			self::$fields[$name] = new ValidateField($params);
		}

		return self::$fields[$name];
	}
	
	private static function formatParams(&$params)
	{
		foreach ($params as $key=>$val){
			$keyLower = strtolower($key);
			if (isset(self::$lowerStringAttrs[$keyLower]) && $keyLower != $key){
				unset($params[$key]);
				$params[$keyLower] = $val;
			}
		}
	}
}



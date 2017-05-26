<?php
/**
 * 表单验证句柄工厂类，用于创建表单验证句柄
 * 
 * @author longweiguo
 * @version 2009-06-03
 */

require_once dirname(__FILE__) . '/Validator.class.php';

class ValidatorFactory
{
	
	/**
	 * 创建表单验证对象
	 * @example 
	 * ValidatorFactory::createValidator( ValidatorConfig::MODE_REQUIRED, array(
	 *     'fieldName'         => 'username', 
	 *     'errorMessage'      => '用户名不能为空', 
	 *     'defaultMessage'    => '请填写用户名', 
	 *     'focusMessage'      => '请填写用户名,3-20个字符', 
	 *     'emptyValue'        => '请输入用户名',
	 * ));
	 *
     * @param int $mode
	 * @param array $params
	 * @return object
	 */
	public static function createValidator($mode, $params=array())
	{
	    if(is_array($mode)){
            $params = $mode;
            $mode = $params['mode'];
        }
		
		switch ($mode){
            case ValidatorConfig::MODE_REQUIRED:
            	include_once dirname(__FILE__) . '/RequiredValidator.class.php';
                $validator = new RequiredValidator($params);
                break;
                
            case ValidatorConfig::MODE_LENGTH:
            	include_once dirname(__FILE__) . '/LengthValidator.class.php';
                $validator = new LengthValidator($params);
                break;
                
            case ValidatorConfig::MODE_COMPARE_FIELD:
            	include_once dirname(__FILE__) . '/CompareFieldValidator.class.php';
                $validator = new CompareFieldValidator($params);
                break;
                
            case ValidatorConfig::MODE_COMPARE_VALUE:
            	include_once dirname(__FILE__) . '/CompareValueValidator.class.php';
                $validator = new CompareValueValidator($params);
                break;
                
            //case ValidatorConfig::MODE_DEFINED_REGEXP:
                //include_once dirname(__FILE__) . '/DefinedRegexpValidator.class.php';
                //$validator = new DefinedRegexpValidator($params);
                //break;
                
            case ValidatorConfig::MODE_REGEXP:
                include_once dirname(__FILE__) . '/RegexpValidator.class.php';
                $validator = new RegexpValidator($params);
                break;
                
            case ValidatorConfig::MODE_CUSTOM:
            	include_once dirname(__FILE__) . '/CustomValidator.class.php';
                $validator = new CustomValidator($params);
                break;
                
            case ValidatorConfig::MODE_AJAX:
            	include_once dirname(__FILE__) . '/AjaxValidator.class.php';
                $validator = new AjaxValidator($params);
                break;
                
            default:
                throw new Exception('验证类型"'.$mode.'"不支持');
                break;
        }
        
        return $validator;
	}
}

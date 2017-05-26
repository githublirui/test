<?php
/** 
 * 表单类
 * @file code_base2/util/from/FormNamespace.class.php
 * @author longweiguo
 * @date 2011-03-21
 * @version 2.0
 *
 * 通过此类来处理表单验证，表单字段生成等
 */

require_once(dirname(__FILE__) . '/validator/ValidatorFactory.class.php');
require_once(dirname(__FILE__) . '/field/FieldFactory.class.php');

/**
 * 表单类
 * @class Form
 *
 * 通过此类来处理表单验证，表单字段生成等
 */
abstract class FormNamespace
{
    //表单验证配置变量
	private $validatorConfig         = array();

    //验证不通过时收集的错误信息
    private $errors					= array();
    
    private $fieldErrors			= array();
    
    private $customErrors           = array();
    
    private $fields                 = array();
    
    private $validFields            = array();

    /**
     * 添加字段验证对象
     *
     * @param int $mode
     * @param array $params
     */
    public function addValidator($mode, $params='')
    {
        if (is_object($mode)){
        	$objValidator = $mode;
        }
        else {
        	if(is_array($mode)){
	            $params = $mode;
	        	$mode = array_shift($params);
	        }
	        $objValidator = ValidatorFactory::createValidator($mode, $params);
        }
		
        //$this->validators[$objValidator->getFieldName()][] = $objValidator;
		
		$fieldName = $objValidator->getFieldName();
		if (!isset($this->fields[$objValidator->getFieldName()])){
            $this->fields[$objValidator->getFieldName()] = $objValidator->getField();
		}
    }
    
    private function createValidatorsFromConfig()
    {
    	if (count($this->validatorConfig) > 0)
    	{
    		foreach ($this->validatorConfig as $config){
    			if (isset($config['mode'])){
    				$mode = $config['mode'];
    				unset($config['mode']);
    			}
    			else {
    			    $mode = array_shift($config);
    			}
    			self::addValidator($mode, $config);
    		}
    		$this->validatorConfig = array();
    	}
    }
    
    /**
     * 根据表单字段name移除验证规则
     *
     * @param string $fieldName
     */
    public function removeValidator($fieldName)
    {
        self::createValidatorsFromConfig();
        
        if (array_key_exists($fieldName, $this->fields)){
            $this->fields[$fieldName]->removeValidators();
            $resultOrBool = array_search($fieldName, $this->validFields);
            if($resultOrBool != false){
                unset($this->validFields[$resultOrBool]);
            }
        }
    }
    
	/**
	 * 执行验证
     *
	 * @return boolen 返回是否通过验证
	 */
    final public function validate()
    {
        $valid				= true;
        $this->errors		= array();
        $this->fieldErrors	= array();
        $this->customErrors = array();
        $this->validFields	= array();
        
        $this->createValidatorsFromConfig();
        
        if (count($this->fields) > 0) {
            foreach ($this->fields as $field){
                if (!$field->validate()){
                    $valid = false;
                    $this->setError($field->getName(), $field->getValidatorErrorMessage());
                }
                else {
                	$this->validFields[] = $field->getName();
                }
            }
        }
        
        return $valid;
    }

	public function setError($fieldName, $errorMessage='')
	{
		$this->errors[] = $errorMessage;
		
		if (empty($errorMessage)) {
			$this->customErrors[] = $fieldName;
		} else if (empty($this->fieldErrors[$fieldName])) {
		    $this->fieldErrors[$fieldName] = $errorMessage;
		}

	}
    
    public function getError()
    {
        return (count($this->errors) > 0) ? $this->errors[0] : NULL;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }

    public function getFieldErrors()
    {
        return $this->fieldErrors;
    }
    
    public function hasError()
    {
        return (count($this->errors) > 0) ? true : false;
    }
    
    public function getCustomError()
    {
        return (count($this->customErrors) > 0) ? $this->customErrors[0] : NULL;
    }
    
    public function getCustomErrors()
    {
        return $this->customErrors;
    }
    
    public function hasCustomError()
    {
        return (count($this->customErrors) > 0) ? true : false;
    }
    
    /**
     * 取得客户端验证js
     *
     * @param bool $addJsTag 输出js的同时，是否输出script标签，默认为false
     * @return string 返回JS代码
     */
    public function getValidatorJs($addJsTag = false)
    {        
        $js = '';
		if ($addJsTag){
			$js = "<script type='text/javascript'>\n";
		}
        
        foreach ($this->fields as $field){
        	if ($field->getDisplay() && !$field->getDisabled()){
        	    $js .= $field->getValidatorJs();
        	}
        }
        
        if (count($this->validFields) > 0){
        	foreach ($this->validFields as $fieldName){
                if (!empty($this->fields[$fieldName]) && !$this->fields[$fieldName]->isEmpty())
        		$js .= 'GJ.validator("'.$fieldName.'").displayValid();' . "\n";
        	}
        }
        
        if (count($this->fieldErrors) > 0){
	        $first = true;
	        foreach ((array)$this->fieldErrors as $fieldName=>$errorMessage){           
	            if ($first){
	                $js .= 'GJ.validator("'.$fieldName.'").scrollToTop().displayError("'.$errorMessage.'");' . "\n";
	            }
	            else {
	                $js .= 'GJ.validator("'.$fieldName.'").displayError("'.$errorMessage.'");' . "\n";
	            }
	            $first = false;
	        }
        }
        
		if ($addJsTag){
			$js .= "</script>";
		}
        
        return $js;
    }

    /**
     * 判断当前是否为表单提交之后
     *
     * @return bool
     */
	public static function isPostBack()
	{
		return $_SERVER['REQUEST_METHOD']=='POST';
	}
    
    /**
     * 取得表单提交的值
     * 该值不一定是原始表单字段的值，而是表单字段对应的数据表字段的值
     *
     * @return array()
     */
    public function getPostData($key='')
    {
    	static $data;
    	
    	if (!$data) {
	    	$data = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST : $_GET;
	
	    	if (count($this->fields) > 0) {
	            foreach ($this->fields as $field){
	                $data[$field->getDbFieldName()] = $field->getPostValue();
	            }
	        }
    	}
    	
    	if ($key) {
    		if (array_key_exists($key, $data)) {
    			return $data[$key];
    		}
    		return null;
    	}
    	return $data;
    }

    /**
     * 取得当前所有表单字段对象
     *
     * @return array()
     */
    public function getFields()
    {
    	return $this->fields;
    }

    /**
     * 添加一个表单字段对象
     *
     * @param string|array $field 当为字符串时表示表单字段的name，当为array时表示一个表单字段的一系列属性
     * @return void
     */
	public function addField($field)
	{
		if (is_string($field)){
			$field = array('name'=>$field);
		}
	    if (is_array($field)){
            $field = FieldFactory::creatField($field);
        }
		if (!is_object($field)){
			die('[FormNamespace::addField] $field is not an object.');
		}
		$this->fields[$field->getName()] = $field;
	}
	
    /**
     * 添加一组表单字段对象
     *
     * @param array $fields 
     * @return void
     */
	public function addFields($fields)
	{
		foreach ($fields as $field){
			$this->addField($field);
		}
	}

    /**
     * 根据字段名，取得表单字段对象
     *
     * @param string $fieldName 表单字段的name
     * @return object
     */
	public function getField($fieldName)
	{
		return $this->fields[$fieldName];
	}

    public function removeField($fieldName){
        unset($this->fields[$fieldName]);
    }
	
    /**
     * 给当前表单的所有字段设置值
     *
     * @param array $value 下标为表单字段名的数组
     * @return void
     */
	public function setValue($value)
	{
		FieldNamespace::setInitValue($value);
	}
}

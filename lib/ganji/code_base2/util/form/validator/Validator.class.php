<?php
/**
 * 表单验证基类
 * 
 * @author longweiguo
 * @version 2009-06-03
 */

require_once dirname(__FILE__) . '/Validator.config.php';
require_once dirname(__FILE__) . '/Regexp.config.php';

abstract class Validator
{
    
    /**
     * 验证类型
     *
     * @var string
     */
    private $mode                = '';
    
	/**
	 * 表单字段name，可能包含"[]"或"::"
	 *
	 * @var string
	 */
	private $fieldName           = '';
	
	/**
	 * 错误提示信息
	 *
	 * @var string
	 */
	private $errorMessage        = '';
	
	
	/**
	 * 临时错误信息
	 *
	 * @var string
	 */
	private $tmpErrorMessage     = '';
	
    /**
     * 是否禁用验证规则的php回调函数
     *
     * @var string|array
     */
	private $disabledPhpCallback = '';
    
	/**
	 * 是否禁用验证规则的js回调函数
	 *
	 * @var string
	 */
    private $disabledJsCallback  = '';
    
	/**
	 * 是否通过验证
	 *
	 * @var boolen
	 */
	private $isValid             = false;
	
	/**
	 * 是否执行过validate()方法
	 *
	 * @var boolen
	 */
	protected $validated         = false;
	
	private static $fields       = array();
    
    public static $validators    = array();
    
    protected $field;
	
	/**
	 * 构造器
	 *
	 * @param string $fieldName        表单字段名
	 * @param string $errorMessage     错误提示信息
	 * @param string $defaultMessage   默认提示信息
	 * @param string $focusMessage     聚焦时的提示信息
	 * @param string $tipSpanId        信息提示位置span的id号
	 */
	public function __construct($params)
	{
		if (empty($params['fieldName'])){
			throw new Exception('Param "fieldName" should be not empty.');
		}		
		$this->setFieldName( $params['fieldName'] );
		
		if ($params['mode'] == ValidatorConfig::MODE_REQUIRED || $params['mode'] == ValidatorConfig::MODE_AJAX){
			self::$validators[$params['fieldName']]['_'.$params['mode']] = & $this;
		}
		else {
		    self::$validators[$params['fieldName']][] = & $this;
		}
		
        if (!FieldFactory::fieldExists($params['fieldName'])){
            FieldFactory::creatField(array('name'=>$params['fieldName']));
        }
        $this->field = & FieldFactory::getField($params['fieldName']);
        
        if (($value = self::getArrayValue( $params, 'defaultMessage' )) !== NULL){
            $this->setDefaultMessage($value);
        }
        
        if (($value = self::getArrayValue( $params, 'focusMessage' )) !== NULL){
            $this->setFocusMessage($value);
        }
        
        if (($value = self::getArrayValue( $params, 'tipSpanId' )) !== NULL){
            $this->setTipSpanId($value);
        }
        
        if (($value = self::getArrayValue( $params, 'emptyValue' )) !== NULL){
            $this->setEmptyValue($value);
        }
        
        if (($value = self::getArrayValue( $params, 'focusValue' )) !== NULL){
            $this->setFocusValue($value);
        }
        
        if (($value = self::getArrayValue( $params, 'defaultDbValue' )) !== NULL){
            $this->setDefaultDbValue($value);
        }
        
        if (($value = self::getArrayValue( $params, 'showErrorMode' )) !== NULL){
            $this->setShowErrorMode($value);
        }
		
		$this->setErrorMessage( self::getArrayValue( $params, 'errorMessage', '' ) );
        $this->setDisabledPhpCallback( self::getArrayValue( $params, 'disabledPhpCallback', '' ) );
        $this->setDisabledJsCallback( self::getArrayValue( $params, 'disabledJsCallback', '' ) );
	}
	
	public static function getValidators($fieldName)
	{
		return array_key_exists($fieldName, self::$validators) ? self::$validators[$fieldName] : array();
	}
    
    /**
     * 移除某字段某模式的验证规则
     *
     * @param string $fieldName
     * @param int $mode
     */
	public static function removeValidator($fieldName, $mode)
    {
        if (array_key_exists($fieldName, self::$validators)){
            foreach ((array)self::$validators[$fieldName] as $key=>$validator){
                if ($validator->getMode() == $mode){
            	    self::$validators[$fieldName][$key] = NULL;
            	    unset(self::$validators[$fieldName][$key]);
                }
            }
        }
    }
    
    /**
     * 移除某字段的所有验证规则
     *
     * @param string $fieldName
     */
    public static function removeValidators($fieldName)
    {
        if (array_key_exists($fieldName, self::$validators)){
        	foreach ((array)self::$validators[$fieldName] as $key=>$validator){
        		self::$validators[$fieldName][$key] = NULL;
        	}
            unset(self::$validators[$fieldName]);
        }
    }
	
    //移除整个字段的所有规则
    /*
	public static function remove($fieldName)
    {
    	foreach (self::$validators[$fieldName] as $key=>$validator){
    		self::$validators[$fieldName][$key] = NULL;
    	}
    	unset(self::$validators[$fieldName]);
    	unset(self::$fields[$fieldName]);
    }*/
    
    /**
	 * 执行验证
	 * @return boolean
	 */
	abstract function validate();	
	
	public function getField()
	{
		return $this->field;
	}

	/**
	 * 设置表单字段name，可能包含"[]"
	 *
	 * @param string $fieldName
	 */
	protected function setFieldName($fieldName)
	{
		$this->fieldName = $fieldName;
			
		//$fieldName = explode("::", $fieldName);
        //$fieldName = $fieldName[count($fieldName)-1];
        //$fieldName = explode("[", $fieldName);
        //self::setFieldInfo($this->fieldName, 'postName', $fieldName[0]);
	}
	
	/**
	 * 返回表单字段name，可能包含"[]"
	 *
	 * @return string
	 */
	public function getFieldName()
	{
		return $this->fieldName;
	}
    
    public static function setFieldInfo($fieldName, $key, $value)
    {
        if (!isset(self::$fields[$fieldName])){
            self::$fields[$fieldName] = array(
                'postName'            => '',         //表单提交后$_POST的下标
                'defaultMessage'      => '',         //默认提示信息
                'focusMessage'        => '',         //聚焦时的提示信息
                'tipSpanId'           => '',         //显示提示信息的元素id
                'emptyValue'          => NULL,       //表示为空的值
                'focusValue'          => NULL,       //聚焦时的值
                'defaultDbValue'      => NULL,       //表示空时存入数据库中的值
                'showErrorMode'       => '',         //提示错误的显示方式
            );
        }   
    	
    	self::$fields[$fieldName][$key] = $value;
    }
    
    public static function getFieldInfo($fieldName, $key)
    {
        if (array_key_exists($fieldName, self::$fields) && is_array(self::$fields[$fieldName]) && array_key_exists($key, self::$fields[$fieldName])){
	    	return self::$fields[$fieldName][$key];
        }
        return NULL;
    }
	
    /**
     * 设置错误信息
     *
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
    	$this->errorMessage = self::formatValidatorString($errorMessage);
    }
	
	/**
	 * 取得错误提示信息
	 *
	 * @return string
	 */
    public function getErrorMessage($ckeckValid=true)
    {
        //用于js中输出
    	if (!$ckeckValid){
        	return $this->errorMessage;
        }

        //服务器端不通过验证才返回错误信息
        if (!$this->isValid()){
        	if (!empty($this->tmpErrorMessage)){
        		$message = $this->tmpErrorMessage;
        		$this->tmpErrorMessage = '';
        	}
            else {
            	$message = $this->errorMessage;
            }
        	return $message;
        }
        
    	return "";
    }
    
    /**
     * 设置临时错误信息
     *
     * @param string $errorMessage
     */
    protected function setTmpErrorMessage($tmpErrorMessage)
    {
        $this->tmpErrorMessage = self::formatValidatorString($tmpErrorMessage);
    }
    
    /**
     * 取得临时错误提示信息
     *
     * @return string
     */
    protected function getTmpErrorMessage()
    {
        return $this->tmpErrorMessage;
    }
    
    /**
     * 设置默认提示字串
     *
     * @param string $defaultMessage
     */
    public function setDefaultMessage($defaultMessage)
    {
    	$this->field->setDefaultMessage($defaultMessage);
    }
    
    /**
     * 聚焦时的提示字串
     *
     * @param string $focusMessage
     */
    public function setFocusMessage($focusMessage)
    {
    	$this->field->setFocusMessage($focusMessage);
    }
    
    /**
     * 设置信息提示span的id号
     *
     * @param string $tipSpanId
     */
    public function setTipSpanId($tipSpanId)
    {
    	$this->field->setTipSpanId($tipSpanId);
    }
    
    /**
     * 设置表示空的值
     *
     * @param string $emptyValue
     */
    public function setEmptyValue($emptyValue)
    {
        $this->field->setEmptyValue($emptyValue);
    }
    
    /**
     * 设置聚焦时的值
     *
     * @param string $emptyValue
     */
    public function setFocusValue($focusValue)
    {
        $this->field->setFocusValue($focusValue);
    }
    
    /**
     * 设置表示为空的数据表字段的值
     *
     * @param string $defaultDbValue
     */
    public function setDefaultDbValue($defaultDbValue)
    {
        $this->field->setDefaultDbValue($defaultDbValue);
    }
    
    /**
     * 设置验证类型
     *
     * @param string $mode
     */
    protected function setMode($mode)
    {
    	$this->mode = $mode;
    }
    
    /**
     * 获取验证类型
     *
     * @return string
     */
    public function getMode()
    {
    	return $this->mode;
    }
    
    /**
     * 设为已执行过验证
     *
     * @param boolen $boolen
     */
    protected function setValidated($boolen)
    {
    	$this->validated = $boolen;
    }
    
    /**
     * 取得是否执行过验证
     *
     * @return boolen
     */
    protected function getValidated()
    {
        return $this->validated;
    }
    
    /**
     * 设为已通过验证
     *
     * @param boolen $boolen
     */
    protected function setValid($boolen)
    {
        $this->isValid = $boolen;
    }
	
	/**
	 * 返回是否通过难证
	 *
	 * @return boolen
	 */
	public function isValid()
	{
		if ($this->getValidated() == false){
			throw new Exception("Run validate() first");
		}
		
		return $this->isValid;
	}
	
	/**
	 * 设置禁用验证规则的php回调函数
	 *
	 * @param string|array $disabledPhpCallback
	 */
	public function setDisabledPhpCallback($disabledPhpCallback)
	{
		$this->disabledPhpCallback = $disabledPhpCallback;
	}
    
	/**
	 * 取得禁用验证规则的php回调函数
	 *
	 * @return string|array
	 */
    public function getDisabledPhpCallback()
    {
        return $this->disabledPhpCallback;
    }
    
    /**
     * 检测是否禁用验证规则
     *
     * @return bool
     */
    protected function checkDisabled()
    {
    	if (!$this->disabledPhpCallback){
    		return false;
    	}
    	return call_user_func($this->disabledPhpCallback);
    }
    
    /**
     * 设置禁用验证规则的js回调函数
     *
     * @param string $disabledJsCallback
     */
    public function setDisabledJsCallback($disabledJsCallback)
    {
        $this->disabledJsCallback = $disabledJsCallback;
    }
    
    /**
     * 取得禁用验证规则的js回调函数
     *
     * @return string
     */
    public function getDisabledJsCallback()
    {
        return $this->disabledJsCallback;
    }
    
    /**
     * 设置错误显示模式
     *
     * @param int $showErrorMode
     */
    public function setShowErrorMode($showErrorMode)
    {
    	$this->field->setShowErrorMode($showErrorMode);
    }
    
    /**
     * 取得错误显示模式
     *
     * @return int
     */
    public function getShowErrorMode()
    {
        return $this->field->getShowErrorMode();
    }
    
    /**
     * 格式化js输出字串
     *
     * @param string $msg
     * @return string
     */
    protected static function formatValidatorString($msg)
    {
        if (!$msg) return $msg;
    	$msg = str_replace(array('"', "\r","\n"), array('', '', ''), $msg);
        return $msg;
    }
    
    /**
     * 取数组某下标的值
     *
     * @param array $array
     * @param string $key
     * @param string $default
     * @return string
     */
    protected function getArrayValue($array, $key, $default=NULL)
    {
        if (array_key_exists($key, $array)){
            return $array[$key];
        }
        return $default;
    }
    
    /**
     * 检测是否设置$fieldName
     *
     */
    protected function checkFieldName()
    {
        if (empty($this->fieldName)){
            throw new Exception('$fieldName is empty');
        }
    }
    
    /**
     * 检测是否设置$errorMessage
     *
     */
    protected function checkErrorMessage()
    {
        if (empty($this->errorMessage)){
            throw new Exception('$errorMessage is empty');
        }
    }
	
}

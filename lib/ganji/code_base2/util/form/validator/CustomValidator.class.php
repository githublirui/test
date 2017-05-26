<?php
/**
 * 自定义表单验证类
 * 指定一个已存的php函数或对象的方法作为服务器端验证回调函数
 * 指定一个已存的js函数作为客户器端验证回调函数
 * 
 * @author  longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:(同时在服务器端和客户端执行验证)
 * new CustomValidator( array(
 *     'fieldName'      => 'username',
 *     'phpCallback'    => 'phpCheckUsername',
 *     'jsCallback'     => 'jsCheckUsername',
 *     'errorMessage'   => '用户名不正确',
 * ) );
 * 
 * 例二:(只在服务器端验证)
 * new CustomValidator( array(
 *     'fieldName'      => 'username',
 *     'phpCallback'    => 'phpCheckUsername',
 *     'errorMessage'   => '用户名不正确',
 * ) );
 * 
 * 例三:(只在客户端验证)
 * new CustomValidator( array(
 *     'fieldName'      => 'username',
 *     'jsCallback'     => 'jsCheckUsername',
 *     'errorMessage'   => '用户名不正确',
 * ) );
 * 
 * 例四:(不设置$errorMessage, 错误信息由回调函数返回)
 * new CustomValidator( array(
 *     'fieldName'      => 'username',
 *     'phpCallback'    => 'phpCheckUsername',
 * ) );
 * 
 * 例五:(多参数式)
 * new CustomValidator('username', 'phpCheckUsername', 'jsCheckUsername', '用户名不正确');
 */

class CustomValidator extends Validator
{
	
	/**
	 * 服务器端验证回调函数
	 *
	 * @var string|array
	 */
	private $phpCallback    = '';
	
	/**
	 * 客户端端验证回调函数
	 *
	 * @var string
	 */
	private $jsCallback     = '';
	
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $phpCallback      服务器端验证回调函数(已存在的php函数或对象的方法，有一个参数，用于接收表单字段值)
     * @param string $jsCallback       客户端端验证回调函数(已存在的js函数)
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $phpCallback='', $jsCallback='', $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'phpCallback'    => $phpCallback,
                'jsCallback'     => $jsCallback,
                'errorMessage'   => $errorMessage,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'tipSpanId'      => $tipSpanId,
            );
        }
    	
    	parent::__construct($params);
        
        $this->setPhpCallback( parent::getArrayValue($params, 'phpCallback', '') );
        $this->setJsCallback(  parent::getArrayValue($params, 'jsCallback', '') );
        
        $this->setMode(ValidatorConfig::MODE_CUSTOM);
    }
    
    public function setPhpCallback($phpCallback)
    {
    	$this->phpCallback = $phpCallback;
    }
    
    public function setJsCallback($jsCallback)
    {
        $this->jsCallback = $jsCallback;
    }
	
    /**
     * 执行验证
     *
     * @return boolen 返回是否通过验证
     */
    public function validate()
    {
    	$this->checkParams();
    	
    	parent::setValidated(true);
        
        $postValue = $this->field->getPostValue();
        if (empty($this->phpCallback) || parent::checkDisabled()){
            parent::setValid(true);
        }
        else {
             $ret = call_user_func($this->phpCallback, $postValue);
             if (is_string($ret)){
             	 parent::setTmpErrorMessage($ret);
             }
             else if ($ret){
             	 parent::setValid(true);
             }
             else {
             	 parent::checkErrorMessage();
             }
        }
    	
    	return parent::isValid();
    }
    
    
        
    /**
     * 获取html验证规则
     * 返回值形如：
     */
    public function getRules() {
        $this->checkParams();
        
        //待实现
        
        return array();
    }
    
    public function getJsData()
    {
        $this->checkParams();
        if (!empty($this->jsCallback)){
            $disabledJsCallback = $this->getDisabledJsCallback();
            if (!empty($disabledJsCallback)){
            	$jsCode = sprintf('setCallback(%s, "%s", %s)', $this->jsCallback, $this->getErrorMessage(false), $disabledJsCallback);
            }
            else {
            	$jsCode = sprintf('setCallback(%s, "%s")', $this->jsCallback, $this->getErrorMessage(false));
            }
            return array($jsCode);
        }
		return array();
    }
    
    protected function checkParams()
    {
        parent::checkFieldName();
        
        if (empty($this->phpCallback) && empty($this->jsCallback)){
            throw new Exception('Must set $phpCallback or $jsCallback');
        }
    }
}

<?php
/**
 * ajax表单验证类
 * 此验证只在客户端进行
 * 
 * @author  longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:(数组参数式)
 * new AjaxValidator( array(
 *     'fieldName'    => 'username',
 *     'ajaxUrl'      => 'ckeckUsername.php',
 *     'errorMessage' => '用户名错误'
 * ) ); 
 * 
 * 例二:(多参数式)
 * new AjaxValidator( 'username', 'ckeckUsername.php', '用户名错误' );
 * 
 * 例三:(不设置$errorMessage, 错误信息在'ckeckUsername.php'中返回)
 * new AjaxValidator( 'username', 'ckeckUsername.php' );
 * 
 */

class AjaxValidator extends Validator
{
	
	/**
	 * ajax验证文件url
	 *
	 * @var string
	 */
	private $ajaxUrl    = '';
		
        /**
         * ajax扩展参数
         * @var type 
         */
        private $_ajaxExtParams = array();
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $ajaxUrl          正则表达式
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $ajaxUrl='', $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
        	$params = $fieldName;
        }
        else {
        	$params = array(
        	    'fieldName'      => $fieldName,
        	    'ajaxUrl'        => $ajaxUrl,
        	    'errorMessage'   => $errorMessage,
        	    'defaultMessage' => $defaultMessage,
        	    'focusMessage'   => $focusMessage,
        	    'tipSpanId'      => $tipSpanId,
        	);
        }
    	
    	parent::__construct($params);
        
        $this->setAjaxUrl(parent::getArrayValue($params, 'ajaxUrl', ''));
        
        $this->setMode(ValidatorConfig::MODE_AJAX);
        
        //扩展参数
        $allowParams = array('type', 'success', 'error', 'timeout', 'complete', 'dataType');
        foreach ($allowParams as $paramName) {
            $this->_ajaxExtParams[$paramName] = parent::getArrayValue($params, $paramName, '');
        }
    }
    
    /**
     * 设置ajax验证url
     *
     * @param string $ajaxUrl
     */
    public function setAjaxUrl($ajaxUrl)
    {
    	$this->ajaxUrl = $ajaxUrl;
    }
    	
    /**
     * 执行验证
     * ajax验证不在服务器端执行，直接返回true
     *
     * @return boolen 返回是否通过验证
     */
    public function validate()
    {
    	$this->checkParams();
    	
    	parent::setValidated(true);
        
        parent::setValid(true);
    	
    	return parent::isValid();
    }
    
    /**
     * 获取html验证规则
     * 返回值形如：
     */
    public function getRules() {
        $this->checkParams();
        
        $ajaxParams = array(
            'url' => $this->ajaxUrl,
        );
        foreach ($this->_ajaxExtParams as $key => $value) {
            if ($value !== '' && $value !== null) {
                $ajaxParams[$key] = $value;
            }
        }
        
        $rule = array(
            'key' => 'ajax',
            'params' => $ajaxParams
        );
        
        return array($rule);
    }
    
    public function getJsData()
    {
    	$this->checkParams();
    	$disabledJsCallback = $this->getDisabledJsCallback();
        if (!empty($disabledJsCallback)){
        	$jsCode = sprintf('setAjax("%s", "%s", "", %s)', $this->ajaxUrl, $this->getErrorMessage(false), $disabledJsCallback);
        }
        else {
        	$jsCode = sprintf('setAjax("%s", "%s")', $this->ajaxUrl, $this->getErrorMessage(false));
        }
    	
    	return array('ajax' => $jsCode);
    }
    
    protected function checkParams()
    {
    	parent::checkFieldName();
    	
    	if (empty($this->ajaxUrl)){
    		throw new Exception('$ajaxUrl is empty');
    	}
    }
}

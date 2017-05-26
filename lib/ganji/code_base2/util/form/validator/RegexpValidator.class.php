<?php
/**
 * 正则表达式表单验证类
 * 
 * @author longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:
 * new RegexpValidator( array(
 *     'fieldName'      => 'price',
 *     'regexp'         => '/-?[0-9\.]+/',
 *     'errorMessage'   => '价格只能是数字',
 * ) );
 * 
 * 例二：
 * new RegexpValidator('price', '/-?[0-9\.]+/', '价格只能是数字');
 */

class RegexpValidator extends Validator
{
	
	/**
	 * 正则表达式
	 *
	 * @var string
	 */
	private $regexp    = '';
	
	/**
	 * 是否为不匹配
	 *
	 * @var boolen
	 */
	private $exclude   = false;
		
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $regexp           正则表达式
     * @param string $errorMessage     错误提示信息
     * @param string $exclude          是否为排除（即不匹配时为正确）
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $regexp='', $errorMessage='', $exclude=false, $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'regexp'         => $regexp,
                'errorMessage'   => $errorMessage,
                'exclude'        => $exclude,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'tipSpanId'      => $tipSpanId,
            );
        }
        
        parent::__construct($params);
        
        $this->setRegexp( parent::getArrayValue($params, 'regexp', '') );
        $this->setExclude( parent::getArrayValue($params, 'exclude', false) );
        
        $this->setMode(ValidatorConfig::MODE_REGEXP);
    }
    
    public function setRegexp($regexp)
    {
        $this->regexp = $regexp;
    }
    
    /**
     * 设为是否为不匹配
     *
     * @param boolen $boolen
     */
    public function setExclude($boolen)
    {
        $this->exclude = $boolen;
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
    	
    	$regexp    = is_array($this->regexp) ? $this->regexp[0] : $this->regexp;
    	$match     = $regexp ? preg_match($regexp, $postValue) : true;
        
        if ($this->field->isEmpty() || parent::checkDisabled() || (!$this->exclude && $match) || ($this->exclude && !$match)){
        	parent::setValid(true);
        }
    	
    	return parent::isValid();
    }
    
    /**
     * 获取html验证规则
     * 返回值形如：
     * data-regex='['([0-9-]){7,20}', true, "请输入正确格式的E-mail地址"]'
     */
    public function getRules() {
        $this->checkParams();
        
        //regexp如果是数组，则有2个元素，第一个给php验证，第2个给js验证
        $regexp = is_array($this->regexp) ? $this->regexp[1] : $this->regexp;
        
        //前端new RegExp()需要正则类型或者string类型，json无法携带正则类型，
        //故去掉前后//，例如/^[1-9]\d*$/ 变成 ^[1-9]\\d*$，具体原因，联系前端组
        preg_match('/^\/(.+)\/([a-z])?$/', $regexp, $matches);
        $regexp = isset($matches[1]) ? $matches[1] : '';
        $regexp = str_replace('\\', '\\\\', $regexp);
        
        $params = array(
            $regexp, $this->getErrorMessage(false), $this->exclude
        );
        
        //正则属性，如/i /m /g
        if (!empty($matches[2])) {
            $params[] = $matches[2];
        }
        
        return array(array(
            'key' => 'regexp',
            'params' => $params,
        ));
    }
    
    public function getJsData()
    {
        $this->checkParams();
        
        $regexp    = is_array($this->regexp) ? $this->regexp[1] : $this->regexp;
        if ($regexp){
	        $disabledJsCallback = $this->getDisabledJsCallback();
	        if (!empty($disabledJsCallback)){
	            $jsCode = sprintf('setRegexp(%s, "%s", %s, %s)', $regexp, 
                                                         $this->getErrorMessage(false), 
                                                         $this->exclude?'true':'false',
                                                         $disabledJsCallback);
	        }
	        else {
	            $jsCode = sprintf('setRegexp(%s, "%s", %s)', $regexp, 
                                                         $this->getErrorMessage(false), 
                                                         $this->exclude?'true':'false');
	        }
        }
        
        return array($jsCode);
    }
    
    protected function checkParams()
    {
        parent::checkFieldName();
        parent::checkErrorMessage();
        
        if (empty($this->regexp)){
            throw new Exception('$regexp is empty');
        }
    }
}

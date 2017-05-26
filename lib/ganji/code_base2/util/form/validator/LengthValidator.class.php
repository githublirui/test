<?php
/**
 * 字符长度表单验证类
 * 
 * @author longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:
 * new LengthValidator( array(
 *     'fieldName'      => 'username',
 *     'minLength'      => 3,
 *     'maxLength'      => 20,
 *     'errorMessage'   => '用户名的字符数必须在3-20以内',
 * ) );
 * 
 * 例二:
 * new LengthValidator( 'username', 3, 20, '用户名的字符数必须在3-20以内' );
 */

class LengthValidator extends Validator
{
	
	/**
	 * 最小长度
	 *
	 * @var int
	 */
	private $minLength     = 0;
	
	/**
	 * 最大长度
	 *
	 * @var int
	 */
	private $maxLength     = 0;
	
	/**
	 * 服务器端编码
	 *
	 * @var string
	 */
	private $serverCharset = 'UTF-8';
	
	/**
	 * 客户端编码
	 *
	 * @var string
	 */
	private $clientCharset = 'UTF-8';
	
	/**
	 * 计算长度的方式（按字还 是字节）
	 *
	 * @var unknown_type
	 */
	private $strlenType    = ValidatorConfig::STRLEN_SYMBOL;
	
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param int $minLength           最小长度(如果为0将不作判断)
     * @param int $maxLength           最大长度(如果为0将不作判断)
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $minLength='', $maxLength='', $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'minLength'      => $minLength,
                'maxLength'      => $maxLength,
                'errorMessage'   => $errorMessage,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'tipSpanId'      => $tipSpanId,
            );
        }
        
        parent::__construct($params);
        
        $this->setMinLength( parent::getArrayValue($params, 'minLength', 0) );
        $this->setMaxLength( parent::getArrayValue($params, 'maxLength', 0) );
        $this->setServerCharset( parent::getArrayValue($params, 'serverCharset', 'UTF-8') );
        $this->setClientCharset( parent::getArrayValue($params, 'clientCharset', 'UTF-8') );
        $this->setStrlenType( parent::getArrayValue($params, 'strlenType', ValidatorConfig::STRLEN_SYMBOL));
        
        $this->setMode(ValidatorConfig::MODE_LENGTH);
    }
    
    /**
     * 设置最小长度
     *
     * @param int $minLength
     */
    private function setMinLength($minLength)
    {
    	$this->minLength = max(0, (int)$minLength);
    }
    
    /**
     * 设置最大长度
     *
     * @param int $maxLength
     */
    private function setMaxLength($maxLength)
    {
        $this->maxLength = max(0, (int)$maxLength);
    }
    
    public function setServerCharset($serverCharset='UTF-8')
    {
        $this->serverCharset = $serverCharset;
    }
    
    public function setClientCharset($clientCharset='UTF-8')
    {
        $this->clientCharset = $clientCharset;
    }
    
    public function setStrlenType($strlenType=ValidatorConfig::STRLEN_SYMBOL)
    {
    	$this->strlenType    = $strlenType;
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
        if ($this->maxLength == 3300 || $this->maxLength == 40000) { // 推广后台房源描述获取长度时去除html标签 (增加40000，服务组店铺使用...)
            $length = $this->getLength(strip_tags($postValue));
        } else {
            $length = $this->getLength($postValue);
        }
        parent::setValid(true);
        
        if (!$this->field->isEmpty() && !parent::checkDisabled()){
	   		if ($this->minLength > 0 && $length < $this->minLength){
	            parent::setValid( false );
	        }
	        if ($this->maxLength > 0 && $length > $this->maxLength){
	            parent::setValid( false );
	        }  
        }
    	
    	return parent::isValid();
    }
    
    private function getLength($postValue)
    {
    	if(!extension_loaded('mbstring') ){
            die('php-mbstring should be install ');
    	}
    	
    	$serverCharset = strtoupper($this->serverCharset);
    	if (empty($serverCharset)) $serverCharset = 'UTF-8';
    	$clientCharset = strtoupper($this->clientCharset);
        if (empty($clientCharset)) $clientCharset = 'UTF-8';
        
        if ($serverCharset == 'UTF-8' && $clientCharset == 'UTF-8'){
        	if ($this->strlenType == ValidatorConfig::STRLEN_SYMBOL){
        	    return $this->strlenUtf8($postValue);
        	}
        	else {
        		return strlen(mb_convert_encoding($postValue, "GBK", $serverCharset));
        	}
        }
        else if ($serverCharset != 'UTF-8' && $clientCharset == 'UTF-8'){
        	$postValue = mb_convert_encoding($postValue, $serverCharset, "UTF-8");
        	if ($this->strlenType == ValidatorConfig::STRLEN_SYMBOL){
        		return mb_strlen($postValue, $serverCharset);
        	}
        	else {
                return strlen($postValue);  
        	}
        }
        else if ($serverCharset == 'UTF-8' && $clientCharset != 'UTF-8'){
        	if ($this->strlenType == ValidatorConfig::STRLEN_SYMBOL){
	            return $this->strlenUtf8(mb_convert_encoding($postValue, "UTF-8", $clientCharset));
        	}
        	else {
        		return strlen($postValue);  
        	}
        }
        else {
            if ($this->strlenType == ValidatorConfig::STRLEN_SYMBOL){
                return mb_strlen($postValue, $serverCharset);
            }
            else {
                return strlen($postValue);  
            }
        }
    }

	/**
     * 计算utf8字符长度
     *
     * @return int
     */
	public static function strlenUtf8($str) 
	{ 
		$str = str_replace("\r\n"," ", stripslashes($str));
	    return mb_strlen($str, 'utf-8');
	}
    
    /**
     * 获取html验证规则
     * 返回值形如：
     * data-max-length='[30, "密码长度最多30位"]'
     * data-min-length='[6, "密码长度至少6位"]'
     * 
     */
    public function getRules() {
        $this->checkParams();
        
        $retRules = array();
        //最小值
        if ($this->minLength > 0) {
            $retRules[] = array(
                'key' => 'minLength',
                'params' => array(
                    $this->minLength, $this->getErrorMessage(false)
                )
            );
        }
        
        //最大值
        if ($this->maxLength > 0) {
            $retRules[] = array(
                'key' => 'maxLength',
                'params' => array(
                    $this->maxLength, $this->getErrorMessage(false)
                )
            );
        }
        
        return $retRules;
    }
    
    public function getJsData()
    {
        $this->checkParams();
		
		$jsData = array();
        
        //$jsCode = sprintf('setServerCharset("%s")', $this->serverCharset);
        //$jsData['serverCharset'] = $jsCode;
        
        $jsCode = sprintf('setStrlenType("%s")', $this->strlenType);
        $jsData['strlenType'] = $jsCode;
        
        $disabledJsCallback = $this->getDisabledJsCallback();
        
        if ($this->minLength > 0 && $this->maxLength > 0){
            if (!empty($disabledJsCallback)){
            	$jsCode = sprintf('setLength(%d, %d, "%s", %s)', $this->minLength, $this->maxLength, $this->getErrorMessage(false), $disabledJsCallback);
            }
            else {
                $jsCode = sprintf('setLength(%d, %d, "%s")', $this->minLength, $this->maxLength, $this->getErrorMessage(false));
            }
            $jsData['length'] = $jsCode;
        }
        else if ($this->minLength > 0){
            
            if (!empty($disabledJsCallback)){
                $jsCode = sprintf('setMinLength(%d, "%s", %s)', $this->minLength, $this->getErrorMessage(false), $disabledJsCallback);
            }
            else {
                $jsCode = sprintf('setMinLength(%d, "%s")', $this->minLength, $this->getErrorMessage(false));
            }
            $jsData['minLength'] = $jsCode;
        }
        else if ($this->maxLength > 0){
            if (!empty($disabledJsCallback)){
                $jsCode = sprintf('setMaxLength(%d, "%s", %s)', $this->maxLength, $this->getErrorMessage(false), $disabledJsCallback);
            }
            else {
                $jsCode = sprintf('setMaxLength(%d, "%s")', $this->maxLength, $this->getErrorMessage(false));
            }
            $jsData['maxLength'] = $jsCode;
        }
		
		return $jsData;
    }
    
    protected function checkParams()
    {
        parent::checkFieldName();
        parent::checkErrorMessage();
        
        if ($this->minLength <= 0 && $this->maxLength <= 0){
            throw new Exception('Must set $minLength or $maxLength');
        }
    }
}

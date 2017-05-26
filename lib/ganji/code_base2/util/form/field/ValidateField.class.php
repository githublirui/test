<?php
/**
 * 带验证功能的表单字段类
 * @author 龙卫国<lwg_8088@yahoo.com.cn>
 * @date   2009-08-05
 */

require_once dirname(__FILE__) . '/FieldNamespace.class.php';

class ValidateField extends FieldNamespace 
{
    private $defaultMessage         = '';
    
    private $focusMessage           = '';
    
    public $tipSpanId               = '';
    
    protected $focusValue           = NULL;

    protected $isNumber = false;
    
    /**
     * 错误显示模式 alert|inline
     *
     * @var int
     */
    private $showErrorMode          = '';
    
    private $validatorErrorMessage  = '';
    
    public function __construct($params)
    {
        parent::__construct($params); 
    }

	protected function filterAttributes($params)
	{
		if (isset($params['requiredMessage'])){
            $this->setRequired($params['requiredMessage']);
            unset($params['requiredMessage']);
        }

		if (isset($params['rules'])){
            $this->addRules($params['rules']);
            unset($params['rules']);
        }

		if (isset($params['defaultMessage'])){
            $this->setDefaultMessage($params['defaultMessage']);
            unset($params['defaultMessage']);
        }

		if (isset($params['focusMessage'])){
            $this->setFocusMessage($params['focusMessage']);
            unset($params['focusMessage']);
        }

		if (isset($params['tipSpanId'])){
            $this->setTipSpanId($params['tipSpanId']);
            unset($params['tipSpanId']);
        }

		if (isset($params['focusValue'])){
            $this->setFocusValue($params['focusValue']);
            unset($params['focusValue']);
        }

		if (isset($params['setShowErrorMode'])){
            $this->setSetShowErrorMode($params['setShowErrorMode']);
            unset($params['setShowErrorMode']);
        }

		if (isset($params['isNumber'])){
            $this->setIsNumber($params['isNumber']);
            unset($params['isNumber']);
        }
		
		return parent::filterAttributes($params);
	}
    
    /**
     * 取得表单提交的值
     *
     * @return string
     */
    public function getPostValue()
    {
        $postName = $this->getPostName();
        
        $value = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (array_key_exists($postName, $_POST)){
                $value = $_POST[$postName];
            }
        }         
        else if (array_key_exists($postName, $_GET)){
            $value = $_GET[$postName];
        }
        
        $defaultDbValue = $this->getDefaultDbValue();
        $emptyValue     = $this->getEmptyValue();
        $focusValue     = $this->getFocusValue();
        if ($value == $emptyValue || $value == $focusValue){
            $value = ''; 
        }
        if ($value === '' && $defaultDbValue !== NULL){
            $value = $defaultDbValue; 
        }
        
        return $value;
    }

    /**
     * 提交的数据是否为空
     *
     * @return bool
     */
    public function isEmpty()
    {
        $value          = $this->getPostValue();        
        $defaultDbValue = $this->getDefaultDbValue();
        if ($value === '' || ($defaultDbValue !== NULL && $value == $defaultDbValue)){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param string $defaultMessage
     */
    public function setDefaultMessage($defaultMessage)
    {
        $this->defaultMessage = parent::formatHtmlAttrString($defaultMessage);
    }
    
    /**
     * @return string
     */
    public function getDefaultMessage()
    {
        return $this->defaultMessage;
    }

    /**
     * @param string $focusMessage
     */
    public function setFocusMessage($focusMessage)
    {
        $this->focusMessage = parent::formatHtmlAttrString($focusMessage);
    }

    /**
     * @return string
     */
    public function getFocusMessage()
    {
        return $this->focusMessage;
    }
    
    /**
     * 设置信息提示span的id号
     *
     * @param string $tipSpanId
     */
    public function setTipSpanId($tipSpanId)
    {
        $this->tipSpanId = parent::formatHtmlAttrString($tipSpanId);
    }
    
    /**
     * 获取信息提示span的id号
     *
     * @return string
     */
    public function getTipSpanId()
    {
        return $this->tipSpanId;
    }
    
    /**
     * 设置聚焦时的值
     *
     * @param string $focusValue
     */
    public function setFocusValue($focusValue)
    {
        $this->focusValue = parent::formatHtmlAttrString($focusValue);
    }
    
    /**
     * 获取聚焦时的值
     *
     * @return string
     */
    public function getFocusValue()
    {
        return $this->focusValue;
    }
    
    /**
     * 设置错误显示模式
     *
     * @param int $showErrorMode
     */
    public function setShowErrorMode($showErrorMode)
    {
        $this->showErrorMode = $showErrorMode;
    }
    
    /**
     * 取得错误显示模式
     *
     * @return int
     */
    public function getShowErrorMode()
    {
        return $this->showErrorMode;
    }
    
    public function addRules($rules)
    {
    	foreach ((array)$rules as $rule){
    		$this->addRule($rule);
    	}
    }
    
    public function addRule($args)
    {
    	if (is_array($args) && !empty($args['mode'])){
    		if (!in_array($args['mode'], ValidatorConfig::$modes)){
				throw new Exception('Validator mode "'.$args['mode'].'" is invalid');
			}
			$rule = $args;
    	}
    	else {
	    	$args     = func_get_args();
	    	$argCount = count($args);
	    	$name     = $this->getName();
	    	
	    	$rule = array();
	    	switch ($args[0]){
	    		case ValidatorConfig::MODE_REQUIRED: //必填验证
	    			if ($argCount < 2 || empty($args[1])){
	    				throw new Exception("{$name} params error.");
	    			}
	    			$rule = array(
	    			    'mode'          => $args[0],
	    			    'errorMessage'  => $args[1],
	    		    );
	    			break;
	    		case ValidatorConfig::MODE_LENGTH:   //长度验证
	                if ($argCount < 4 || empty($args[3]) || ($args[1] < 1 && $args[2] < 1)){
	                    throw new Exception("{$name} params error.");
	                }
	                $strlenType   = self::getValueFromArray($args, 6, ValidatorConfig::STRLEN_SYMBOL);
                    $minLength    = self::getValueFromArray($args, 1, 0);
                    $maxLength    = self::getValueFromArray($args, 2, 0);
	                $errorMessage = self::getValueFromArray($args, 3);
	                if (empty($errorMessage)){
		                $msg = array();
	                	if ($strlenType=ValidatorConfig::STRLEN_SYMBOL){
	                        $msg[] = '字数';
	                    }
	                    else {
	                        $msg[] = '字符数';
	                    }
	                    if ($minLength > 0){
	                        $msg[] = "不能少于{$minLength} ";
	                    }
	                    if ($maxLength > 0){
	                        $msg[] = "不能多于{$maxLength}";
	                    }
	                    $errorMessage = implode("", $msg);
	                }
	                
	    			$rule = array(
	                    'mode'          => $args[0],
	    			    'minLength'     => $minLength,
	                    'maxLength'     => $maxLength,
	                    'errorMessage'  => $errorMessage,
	    			    'serverCharset' => self::getValueFromArray($args, 4, 'UTF-8'), //要求与数据库编码一致
	                    'clientCharset' => self::getValueFromArray($args, 5, 'UTF-8'), //要求与网页编码一致
	                    'strlenType'    => $strlenType,
	    			);
	                break;
	            case ValidatorConfig::MODE_COMPARE_FIELD: //与其它字段对比验证
	                if ($argCount < 4 || empty($args[1]) || empty($args[2]) || empty($args[3])){
	                    throw new Exception("{$name} params error.");
	                }
	                $rule = array(
	                    'mode'          => $args[0],
	                    'operator'      => self::getValueFromArray($args, 1, '=='),
	                    'toFieldName'   => self::getValueFromArray($args, 2),
	                    'errorMessage'  => self::getValueFromArray($args, 3),
	                );
	                break;
	            case ValidatorConfig::MODE_COMPARE_VALUE: //与指定值对比验证
	                if ($argCount < 4 || empty($args[1]) || empty($args[2]) || empty($args[3])){
	                    throw new Exception("{$name} params error.");
	                }
	                $rule = array(
	                    'mode'          => $args[0],
	                    'operator'      => self::getValueFromArray($args, 1, '=='),
	                    'toValue'       => self::getValueFromArray($args, 2),
	                    'errorMessage'  => self::getValueFromArray($args, 3),
	                );
	                break;
	            case ValidatorConfig::MODE_REGEXP: //正则表达式验证
	                if ($argCount < 3 || empty($args[1]) || empty($args[2])){
	                    throw new Exception("{$name} params error.");
	                }
	                $rule = array(
	                    'mode'          => $args[0],
	                    'regexp'        => self::getValueFromArray($args, 1),
	                    'errorMessage'  => self::getValueFromArray($args, 2),
	                    'exclude'       => self::getValueFromArray($args, 3, false),
	                );
	                break;
	            case ValidatorConfig::MODE_AJAX: //AJAX验证
	                if ($argCount < 3 || empty($args[1]) || empty($args[2])){
	                    throw new Exception("{$name} params error.");
	                }
	                $rule = array(
	                    'mode'          => $args[0],
	                    'ajaxUrl'       => self::getValueFromArray($args, 1),
	                    'errorMessage'  => self::getValueFromArray($args, 2),
	                );
	                break;
	            case ValidatorConfig::MODE_CUSTOM: //自定义验证
	                if ($argCount < 4 || empty($args[3]) || (empty($args[1]) && empty($args[2]))){
	                    throw new Exception("{$name} params error.");
	                }
	                $rule = array(
	                    'mode'          => $args[0],
	                    'phpCallback'   => self::getValueFromArray($args, 1),
	                    'jsCallback'    => self::getValueFromArray($args, 2),
	                    'errorMessage'  => self::getValueFromArray($args, 3),
	                );
	                break;
	            default:
	            	throw new Exception('Validator mode "'.$args[0].'" is invalid');
					break;
	    	}

			$paramCount = count($rule);
			if (!empty($args[$paramCount])){
    			$rule['disabledPhpCallback'] = $args[$paramCount];
    		}
            if (!empty($args[$paramCount+1])){
                $rule['disabledJsCallback']  = $args[$paramCount+1];
            }
    	}
		
    	$rule['fieldName'] = $this->name;
    	ValidatorFactory::createValidator($rule);
    }
    
    public function setRequired($errorMessage, $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($errorMessage)){
    	    $errorMessage['mode'] = ValidatorConfig::MODE_REQUIRED;
    	    $this->addRule($errorMessage);
    	}
    	else {
			$this->addRule(ValidatorConfig::MODE_REQUIRED, $errorMessage, $disabledPhpCallback, $disabledJsCallback);
		}
    }
    
    public function unSetRequired()
    {
        $this->removeValidator(ValidatorConfig::MODE_REQUIRED);
    }
 
    public function setLengthRule($minLength=0, $maxLength=0, $errorMessage='', $serverCharset='UTF-8', 
                                  $clientCharset='UTF-8', $strlenType=ValidatorConfig::STRLEN_SYMBOL,
                                  $disabledPhpCallback='', $disabledJsCallback='')
    {
    	if (is_array($minLength)){
    	    $minLength['mode'] = ValidatorConfig::MODE_LENGTH;
    	    $this->addRule($minLength);
    	}
    	else {
            $this->addRule(ValidatorConfig::MODE_LENGTH, $minLength, $maxLength, $errorMessage, $serverCharset, 
                           $clientCharset, $strlenType, $disabledPhpCallback, $disabledJsCallback);
    	}
    }

    public function setCompareFieldRule($operator='==', $toFieldName='', $errorMessage='', $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($operator)){
            $operator['mode'] = ValidatorConfig::MODE_COMPARE_FIELD;
            $this->addRule($operator);
        }
        else {
            $this->addRule(ValidatorConfig::MODE_COMPARE_FIELD, $operator, $toFieldName, $errorMessage, $disabledPhpCallback, $disabledJsCallback);
        }
    }

    public function setCompareValueRule($operator='==', $toValue='', $errorMessage='', $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($operator)){
            $operator['mode'] = ValidatorConfig::MODE_COMPARE_VALUE;
            $this->addRule($operator);
        }
        else {
            $this->addRule(ValidatorConfig::MODE_COMPARE_VALUE, $operator, $toValue, $errorMessage, $disabledPhpCallback, $disabledJsCallback);
        }
    }

    public function setRegexpRule($regexp, $errorMessage='', $exclude='', $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($regexp)){
            $regexp['mode'] = ValidatorConfig::MODE_REGEXP;
            $this->addRule($regexp);
        }
        else {
            $this->addRule(ValidatorConfig::MODE_REGEXP, $regexp, $errorMessage, $exclude, $disabledPhpCallback, $disabledJsCallback);
        }
    }

    public function setAjaxRule($ajaxUrl, $errorMessage='', $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($ajaxUrl)){
            $ajaxUrl['mode'] = ValidatorConfig::MODE_AJAX;
            $this->addRule($ajaxUrl);
        }
        else {
            $this->addRule(ValidatorConfig::MODE_AJAX, $ajaxUrl, $errorMessage, $disabledPhpCallback, $disabledJsCallback);
        }
    }

    public function setCustomRule($phpCallback, $jsCallback='', $errorMessage='', $disabledPhpCallback='', $disabledJsCallback='')
    {
        if (is_array($phpCallback) && !empty($args['mode'])){
            $phpCallback['mode'] = ValidatorConfig::MODE_CUSTOM;
            $this->addRule($phpCallback);
        }
        else {
            $this->addRule(ValidatorConfig::MODE_CUSTOM, $phpCallback, $jsCallback, $errorMessage, $disabledPhpCallback, $disabledJsCallback);
        }
    }
	
	/**
	 * 设置表单字段的值是否为一个数字，当为数字时，前面的0将自动去掉
	 *
	 * @param string $fieldName
	 */
	protected function setIsNumber($bool)
	{
		$this->isNumber = (bool)$bool;
	}
	
	/**
	 * 返回表单字段的值是否为一个数字
	 *
	 * @return string
	 */
	public function getIsNumber()
	{
		return $this->isNumber;
	}
    
    public function getValidators()
    {
        return Validator::getValidators($this->name);
    }
    
    public function removeValidators()
    {
        Validator::removeValidators($this->name);
    }
    
    public function removeValidator($mode)
    {
        if (!in_array($mode, ValidatorConfig::$modes)){
            throw new Exception('Validator mode "'.$mode.'" is invalid');
        }
        Validator::removeValidator($this->name, $mode);
    }
    
    /**
     * 附带data-属性的输出
     * @param string $template 模板，相对form/field/templates/的路径
     * @param type $fontendAttr 前端html 属性，例如class, id
     * @return type
     */
    public function toHtml($template, $additionalAttrs = array()) {
        $dataValidatorAttr = $this->getDataValidators(false);
        if (!empty($dataValidatorAttr)) {
            foreach ($dataValidatorAttr as $key => $attrValue) {
                $this->attributes[$key] = $attrValue;
            }
        }
        
        return parent::toHtml($template, $additionalAttrs);
    }
    
    /**
     * 获取用于前端验证的data-开头的验证属性
     * @param type $buildString 是否拼装字符串，true 字符串 | false 数组形式
     * @param type $separator 字符串分隔符，默认空格
     * @return array | string
     * 返回值例如:
     * data-tip-span='#ddd'
     * data-required='[true, "请输入邮箱地址"]'
     * data-format='["email", "请输入正确格式的E-mail地址"]'
     * data-tip-span="#email-tip-span"
     * data-max-length='[30, "密码长度最多30位"]'
     * data-min-length='[6, "密码长度至少6位"]'
     * data-max="100"
     */
    public function getDataValidators($buildString = true, $separator = ' ') {
        $dataValidators = array();
        
        //提示语容器
        if (!empty($this->tipSpanId)){
            $dataValidators['data-tip-span'] = "#{$this->tipSpanId}";
        }
        
        //焦点提示语
        if (!empty($this->focusMessage)) { 
            $dataValidators['data-tip'] = "{$this->focusMessage}";
        }
        
        //validator导出规则
        $validators = $this->getValidators();
        $dataRules = array();
        foreach ($validators as $validator) {
            $rules = $validator->getRules();
            if (empty($rules)) {
                continue;
            }
            
            foreach ($rules as $ruleInfo) {
                if (!empty($ruleInfo['key']) && !empty($ruleInfo['params'])) {
                    //regexp需要合并给前端
                    if (in_array($ruleInfo['key'], array('regexp', 'ajax'))) {
                        $dataRules[$ruleInfo['key']][] = $ruleInfo['params'];
                    } else {
                        $dataRules[$ruleInfo['key']] = $ruleInfo['params'];
                    }
                }
            }
        }
        
        require_once CODE_BASE2 . '/util/text/String.class.php';
        $dataValidators['data-rules'] = String::jsonEncode($dataRules);

        //数组形式返回值
        if (!$buildString) {
            return $dataValidators;
        }
        
        //拼装字符串
        $attrList = array();
        foreach ($dataValidators as $key => $attrValue) {
            $attrList[] = "{$key}='{$attrValue}'";
        }
        return join($separator, $attrList);
    }
    
    public function getValidatorJs()
    {        
        $jsData = array();
        
        if (!empty($this->showErrorMode)){
            $jsData['showErrorMode'] = 'setShowErrorMode("' . ($this->showErrorMode == ValidatorConfig::SHOW_ERROR_ALERT ? 'alert' : 'inline') . '")';
        }
        if (!empty($this->tipSpanId)){
            $jsData['tipSpanId'] = 'setTipSpanId("' . $this->tipSpanId . '")';
        }
        if (!empty($this->defaultMessage)){
            $jsData['defaultMessage'] = 'setDefaultMsg("' . $this->defaultMessage . '")';
        }
        if (!empty($this->focusMessage)){
            $jsData['focusMessage'] = 'setFocusMsg("' . $this->focusMessage . '")';
        }
        if ($this->emptyValue !== NULL){
            $jsData['emptyValue'] = 'setEmptyValue("' . $this->emptyValue . '")';
        }
        if ($this->focusValue !== NULL){
            $jsData['focusValue'] = 'setFocusValue("' . $this->focusValue . '")';
        }
        if ($this->defaultDbValue !== NULL){
            $jsData['defaultDbValue'] = 'setDefaultDbValue("' . $this->defaultDbValue . '")';
        }        
        if ($this->isNumber){
            $jsData['isNumber'] = 'setIsNumber(true)';
        }

        $validators = $this->getValidators();
        foreach ($validators as $validator){
            $thisJsData = $validator->getJsData();
            foreach ((array)$thisJsData as $key=>$str){
                if (is_string($key)){
                     $jsData[$key] = $str;
                }
                else {
                    $jsData[] = $str;
                }
            }
        }
        
        if (count($jsData) > 0){
            $js  = 'GJ.validator("' . $this->name . '")';
            $js .= "\n." . implode("\n.", $jsData);
            $js .= ";\n\n";
        }
        return $js;
    }
    
    /**
     * 执行验证
     *
     * @return boolen 返回是否通过验证
     */
    public function validate()
    {
        $valid = true;
        $errMsg = array();
        
        if ($this->getDisplay() && !$this->getDisabled()){
			$validators = $this->getValidators();
        	foreach ($validators as $validator){
			    if (!$validator->validate()){
			        $valid = false;
			        $errMsg[] = $validator->getErrorMessage();
			    }
			}
        }
        
        $this->validatorErrorMessage = implode(" ", $errMsg);
        
        return $valid;
    }
    
    public function hasAjaxValidator()
    {
    	$validators = $this->getValidators();
    	return array_key_exists('ajax', $validators);
    }
    
    public function getValidatorErrorMessage()
    {
        return $this->validatorErrorMessage;
    }
    
    protected static function getValueFromArray($array, $key, $default='')
    {
        if (array_key_exists($key, $array)){
            return $array[$key];
        }
        return $default;
    }
    
}

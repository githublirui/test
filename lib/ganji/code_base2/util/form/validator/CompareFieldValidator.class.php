<?php
/**
 * 与某字段的值对比的表单验证类
 * 
 * @author longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:
 * new CompareFieldValidator( array(
 *     'fieldName'      => 'password2',
 *     'operator'       => '==',
 *     'toFieldName'    => 'password',
 *     'errorMessage'   => '第二次输入的密码不正确',
 * ) );
 * 
 * 例二:
 * new CompareFieldValidator('password2', '==', 'password', '第二次输入的密码不正确');
 * 
 */

class CompareFieldValidator extends Validator
{
	
	/**
	 * 运算符
	 *
	 * @var string
	 */
	private $operator    = '';
	
	/**
	 * 对比的表单字段name
	 *
	 * @var string
	 */
	private $toFieldName = '';
	
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $operator         运算符
     * @param string $toFieldName      对比的表单字段name
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $operator='', $toFieldName='', $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'operator'       => $operator,
                'toFieldName'    => $toFieldName,
                'errorMessage'   => $errorMessage,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'tipSpanId'      => $tipSpanId,
            );
        }
    	
    	parent::__construct($params);
        
        $this->setOperator( parent::getArrayValue($params, 'operator', '') );
        $this->setToFieldName( parent::getArrayValue($params, 'toFieldName', '') );
        
        $this->setMode(ValidatorConfig::MODE_COMPARE_FIELD);
    }
    
    /**
     * 设置运算符
     *
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
    
    /**
     * 设置要对比的值
     *
     * @param string $toValue
     */
    public function setToFieldName($toFieldName)
    {
        $this->toFieldName = $toFieldName;
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
        
        if ($this->field->isEmpty() || parent::checkDisabled()){
        	parent::setValid(true);
        }
        else {
	    	$postValue   = $this->field->getPostValue();
			$toPostValue = '';
			if (FieldFactory::fieldExists($this->toFieldName)){
				$toField     = FieldFactory::getField($this->toFieldName);
				$toPostValue = $toField->getPostValue();
			}
			else {
				if (array_key_exists($this->toFieldName, $_POST)){
					$toPostValue = $_POST[$this->toFieldName];
				}
				else if (array_key_exists($this->toFieldName, $_GET)){
					$toPostValue = $_GET[$this->toFieldName];
				}
			}
			eval('parent::setValid( $postValue ' . $this->operator . ' $toPostValue ? true : false );');
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
        $disabledJsCallback = $this->getDisabledJsCallback();
        if (!empty($disabledJsCallback)){
        	$jsCode = sprintf('setCompareField("%s", "%s", "%s", %s)', $this->operator, $this->toFieldName, $this->getErrorMessage(false), $disabledJsCallback);
        }
        else {
        	$jsCode = sprintf('setCompareField("%s", "%s", "%s")', $this->operator, $this->toFieldName, $this->getErrorMessage(false));
        }
        
        //return array('compareField' => $jsCode);
        return array($jsCode);
    }
    
    protected function checkParams()
    {
        parent::checkFieldName();
        parent::checkErrorMessage();
        
        if (empty($this->operator)){
            throw new Exception('$operator is empty');
        }
        
        if (!in_array($this->operator, array('==', '<=', '>=', '>', '<', '!='))){
            throw new Exception('$operator "'.$this->operator.'" is not supported');
        }
        
        if (empty($this->toFieldName)){
            throw new Exception('$toFieldName is empty');
        }
    }
}

<?php
/**
 * 与某值对比的表单验证类
 * 
 * @author longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:
 * new CompareValueValidator( array(
 *     'fieldName'      => 'date',
 *     'operator'       => '>=',
 *     'toValue'        => date('Y-m-d'),
 *     'errorMessage'   => '日期不能小于当前日期',
 * ) );
 * 
 * 例二:
 * new CompareValueValidator( 'date', '>=', date('Y-m-d'), '日期不能小于当前日期');
 * 
 */

class CompareValueValidator extends Validator
{
	
	/**
	 * 运算符
	 *
	 * @var string
	 */
	private $operator    = '';
	
	/**
	 * 对比的值
	 *
	 * @var string
	 */
	private $toValue = '';
	
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $operator         运算符
     * @param string $toValue          对比的值
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $operator='', $toValue='', $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'operator'       => $operator,
                'toValue'        => $toValue,
                'errorMessage'   => $errorMessage,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'tipSpanId'      => $tipSpanId,
            );
        }
    	
    	parent::__construct($params);
        
        $this->setOperator(parent::getArrayValue($params, 'operator', ''));
        $this->setToValue(parent::getArrayValue($params, 'toValue', ''));
        
        $this->setMode(ValidatorConfig::MODE_COMPARE_VALUE);
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
    public function setToValue($toValue)
    {
    	$this->toValue = (string)$toValue;
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
	    	$postValue = $this->field->getPostValue();
			$toValue   = $this->toValue;
	    	eval('parent::setValid( $postValue ' . $this->operator . ' $toValue ? true : false );');
        }
        
    	return parent::isValid();
    }
    
        
    /**
     * 获取html验证规则
     * 返回值形如：
     */
    public function getRules() {
        $this->checkParams();
        
        $rule = array(
            'key' => 'compare',
            'params' => array(
                "this {$this->operator} {$this->toValue}",
                $this->getErrorMessage(false)
            )
        );
        
        return array($rule);
    }
    
    public function getJsData()
    {
        $this->checkParams();
        $disabledJsCallback = $this->getDisabledJsCallback();
        if (!empty($disabledJsCallback)){
        	$jsCode = sprintf('setCompareValue("%s", "%s", "%s", %s)', $this->operator, $this->toValue, $this->getErrorMessage(false), $disabledJsCallback);
        }
        else {
        	$jsCode = sprintf('setCompareValue("%s", "%s", "%s")', $this->operator, $this->toValue, $this->getErrorMessage(false));
        }
        
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
    }
}

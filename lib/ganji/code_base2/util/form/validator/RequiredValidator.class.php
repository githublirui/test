<?php
/**
 * 不能为空的表单验证类
 * 
 * @author longweiguo
 * @version 2009-06-03
 * @example 
 * 例一:
 * new RequiredValidator( array(
 *     'fieldName'      => 'username',
 *     'errorMessage'   => '用户名不能为空',
 *     'defaultMessage' => '请填写用户名',
 *     'focusMessage'   => '请填写用户名',
 *     'emptyValue'     => '',
 *     'tipSpanId'      => 'tip_username_id',
 * ) );
 * 
 * 例二:
 * new RequiredValidator( 'username', '用户名不能为空', '请填写用户名', '请填写用户名', '', 'tip_username_id');
 */

class RequiredValidator extends Validator
{
	
	/**
     * 构造函数
     *
     * @param string $fieldName        表单字段名
     * @param string $errorMessage     错误提示信息
     * @param string $defaultMessage   默认提示信息
     * @param string $focusMessage     聚焦时的提示信息
     * @param string $emptyValue       表示为空的值
     * @param string $tipSpanId        信息提示位置span的id号
     */
	public function __construct($fieldName, $errorMessage='', $defaultMessage=NULL, $focusMessage=NULL, $emptyValue=NULL, $tipSpanId=NULL)
    {
        if (is_array($fieldName)){
            $params = $fieldName;
        }
        else {
            $params = array(
                'fieldName'      => $fieldName,
                'errorMessage'   => $errorMessage,
                'defaultMessage' => $defaultMessage,
                'focusMessage'   => $focusMessage,
                'emptyValue'     => $emptyValue,
                'tipSpanId'      => $tipSpanId,
            );
        }
        
        parent::__construct($params);
        
        $this->setMode(ValidatorConfig::MODE_REQUIRED);
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
       
        if (parent::checkDisabled() || !$this->field->isEmpty()){
    		parent::setValid(true);
    	}
    	
    	return parent::isValid();
    }
    
    public function getJsData()
    {
        $this->checkParams();
        
        $disabledJsCallback = $this->getDisabledJsCallback();
        if (!empty($disabledJsCallback)){
            $jsCode = sprintf('setRequired("%s", %s)', $this->getErrorMessage(false), $disabledJsCallback);
        }
        else {
        	$jsCode = sprintf('setRequired("%s")', $this->getErrorMessage(false));
        }
        
        return array('required' => $jsCode);
    }
    
    /**
     * 获取html验证规则
     * 返回值形如：data-required='[true, "请选择"]'
     */
    public function getRules() {
        $this->checkParams();
        
        $attribute = array(
            'key' => 'required', 
            'params' => array(
                true, $this->getErrorMessage(false)
            )
        );
        
        return array($attribute);
    }
    
    protected function checkParams()
    {
        parent::checkFieldName();
        parent::checkErrorMessage();
    }
}

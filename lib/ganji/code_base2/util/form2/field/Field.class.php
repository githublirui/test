<?php

class Field {
    private $fieldName = '';
    /**
     * 字段配置
     * @var array
     */
    private $fieldConfig = array();
    private $rules = array();

    /**
     * 验证之后的错误信息
     * @var string
     */
    private $errorMessage = '';
    
    private $form = null;

    public function __construct($fieldName, $fieldConfig, $form) {
        if(count($fieldConfig) < 1) {
            throw new Exception("rule must be needed");
        }

        $this->fieldName = $fieldName;
        $this->fieldConfig = $fieldConfig;
        
        //form
        if (!($form instanceof Form)) {
            throw new Exception("form must be instance of Form.class.php");
        }
        $this->form = $form;
        
        //rules
        $rules = $fieldConfig['rules'];
        foreach($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * 添加一条验证规则
     * @param $rule
     * @throws Exception
     */
    public function addRule($rule) {
        if(!is_array($rule) || count($rule) < 2) {
            throw new Exception("the first args must be a array");
        }
        $this->rules[] = $rule;
    }

    public function removeRuleByMode($mode) {
        $delRules = array();
        foreach($this->rules as $index => $rule) {
            if($rule[0]  == $mode) {
                array_push($delRules, $rule);
                unset($this->rules[$index]);
            }
        }
        return $delRules;
    }

    /**
     * 获取规则
     */
    public function getRules() {
        return $this->rules;
    }

    /**
     * @param $value
     * @return bool
     */
    public function validate($value) {
        $this->errorMessage = "";
        $rules = $this->rules;
        
        //required是否被置为false
        require_once dirname(__FILE__) . '/../expression/Expression.class.php';
        $isSetUnrequired = false;
        foreach ($rules as &$ruleInfo) {
            if ($ruleInfo[0] == 'required') {
                //动态计算required
                if (is_string($ruleInfo[1])) {
                    $fieldValue = $this->form->getFieldValueByName($this->fieldName);
                    $exp = new Expression($ruleInfo[1], $this->form, $fieldValue);
                    $ruleInfo[1] = $exp->execute();
                }
                
                if ($ruleInfo[1] === false) {
                    $isSetUnrequired = true;
                    break;
                }
            }
        }
        
        //如果非必需字段，且为空值，则不进行其他rule校验
        if ($isSetUnrequired && ($value === '' || $value == null)) {
            return true;
        }
        
        //创建字段校验器
        require_once dirname(__FILE__) . '/../validator/FieldValidator.class.php';
        $fieldConfig = $this->fieldConfig;
        $fieldConfig['rules'] = $rules;
        $fieldValidator = new FieldValidator($fieldConfig, $this->form);

        //字段校验
        if(!$fieldValidator->validate($value)) {
            $this->errorMessage = $fieldValidator->getErrorMessage();
            return false;
        }
        
        return true;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }
}

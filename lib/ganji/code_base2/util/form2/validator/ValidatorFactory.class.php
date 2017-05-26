<?php

include_once dirname(__FILE__) . '/ValidatorBase.class.php';
require_once dirname(__FILE__) . '/../expression/Expression.class.php';

class RequiredValidator extends ValidatorBase {

    /**
     * 校验value是否有值
     * @param $value
     * @return bool
     */
    public function validate($value) {
        $this->valid = true;

        //非必须
        if (!$this->validValue) {
            return $this->valid;
        }
        
        if($value === '' || $value === null) {
            $this->valid = false;
        }

        return $this->valid;
    }
}

class MaxLengthValidator extends ValidatorBase {
    public function validate($value) {
        $this->valid = true;

        $len = $this->getLength($value);

        if($this->validValue > 0 && $len > $this->validValue) {
            $this->valid = false;
        }

        return $this->valid;
    }
}

class MinLengthValidator extends ValidatorBase {
    public function validate($value) {
        $this->valid = true;

        $len = $this->getLength($value);

        if($this->validValue > 0 && $len < $this->validValue) {
            $this->valid = false;
        }

        return $this->valid;
    }
}

class MaxValueValidator extends ValidatorBase {
    public function validate($value) {
        $this->valid = true;

        if($this->validValue > 0 && $value > $this->validValue) {
            $this->valid = false;
        }

        return $this->valid;
    }
}

class MinValueValidator extends ValidatorBase {
    public function validate($value) {
        $this->valid = true;

        if($this->validValue > 0 && $value < $this->validValue) {
            $this->valid = false;
        }

        return $this->valid;
    }
}



class RegexpValidator extends ValidatorBase {
    
    public function validate($value) {
        $regexp = $this->validValue;
        
        $exclude = $this->rule[2] ? true : false;

        if ($exclude) {
            //符合正则表达式则异常
            $this->valid = !preg_match($regexp, $value);
        } else {
            //不符合正则表达式则异常
            $this->valid = preg_match($regexp, $value); 
        }

        return $this->valid;
    }
}

class FormatValidator extends ValidatorBase {
    public function validate($value) {
        $this->valid = false;
        
        //获取正则表达式
        $formatType = strtoupper($this->rule[0]);
        include_once dirname(__FILE__) . '/Regexp.config.php';

        $regexp = constant("RegexpConfig::{$formatType}");
        if (empty($regexp)) {
            return $this->valid;
        }

        if(preg_match($regexp, $value)) {
            $this->valid = true;
        }

        return $this->valid;
    }
}

/**
 * 
 * Class CompareValidator
 * 
 * array('compare', 'this <= maxValueField', '价格必须小于哦', $this),
 * array('compare', 'this > 0', '价格必须大于0哦'),
 */
class CompareValidator extends ValidatorBase {
    private $form = null;
    
    public function __construct($rule, $ruleOption, $form) {
        parent::__construct($rule, $ruleOption);
        
        $this->form = $form;
    }
    
    /**
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function validate($value) {
        $expression = new Expression($this->rule[0], $this->form, $value);
        $this->valid = $expression->execute();
        return $this->valid;
    }
}

class CustomValidator extends ValidatorBase {

    public function __construct($rule, $ruleOption) {
        $this->rule = $rule;
        $this->ruleOption = $ruleOption;
    }
    
    public function validate($value) {
        $module = $this->rule[0];                 //类名or对象
        $function = $this->rule[1];               //回调函数
        $paramList = (array)$this->rule[2];       //回调函数传参
        array_unshift($paramList, $value);        //将value补充到第一个，传递给回调函数
        
        //回调
        list($this->valid, $this->errorMessage) = call_user_func_array(array($module, $function), $paramList);
        return $this->valid;
    }
}

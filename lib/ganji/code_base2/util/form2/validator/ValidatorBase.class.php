<?php

abstract class ValidatorBase {

    protected $validValue = null;
    protected $valid = true;
    protected $rule = null;

    protected $ruleOption;

    public function __construct($rule, $ruleOption) {
        $this->validValue = $rule[0];
        $this->errorMessage = $rule[1];

        $this->rule = $rule;
        $this->ruleOption = $ruleOption;
    }

    public function getErrorMessage() {
        if($this->valid) {
            return null;
        }

        return $this->errorMessage;
    }

    public function getLength($value) {
        if(!extension_loaded('mbstring')) {
            die('php-mbstring should be install !!!');
        }

        if($this->ruleOption['strLenType'] == 'SYMBOL') {
            return $this->strLenUtf8($value);
        } else {
            return strlen(mb_convert_encoding($value, "GBK", "UTF-8"));
        }
    }

    private function strLenUtf8($str) {
        $str = str_replace("\n\r", "", stripslashes($str));
        return mb_strlen($str, 'utf-8');
    }
}
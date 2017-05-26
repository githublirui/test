<?php

/**
 * 表达式模块
 */
class Expression {
    
    private $exp = '';
    private $form = null;
    private $thisfieldValue = null;

    public function __construct($exp, $form, $thisfieldValue) {
        $this->exp = $exp;
        
        //form，用于表达式内字段替换
        if (!($form instanceof Form)) {
            throw new Exception('form should be instance of Form.class.php');
        }
        $this->form = $form;
        
        //用于表达式内this关键字替换
        $this->thisfieldValue = $thisfieldValue;
    }


    /**
     * 执行布尔表达式，支持算术运算符、关系运算符、逻辑运算符
     * this > 3
     * this < price
     * !!need_check_title && 1>1 && need_check_title >= 1 || 3 > 2
     * @param      $expression
     * @param null $form
     * @return bool
     * @throws Exception
     */
    public function execute() {
        //匹配出变量，进行替换
        $transformedExpression = preg_replace_callback('/[a-zA-Z]\w*/', array($this, 'replaceVariable'), $this->exp);
        
        $expressionReturn = false;
        eval("\$expressionReturn = (bool)({$transformedExpression});");
        return $expressionReturn;
    }


    /**
     * 替换变量
     * 例如!!need_check_title && 1>1 && need_check_title >= 1 || 3 > 2 && this > 0 && title=="232"
     * 变成 !!1 && 1>1 && 1 >= 1 || 3 > 2 && '232' > 0 && '232'=="232"
     * 
     * @param $matches
     * @return null|string
     * @throws Exception
     */
    private function replaceVariable($matches) {
        
        if ($matches[0] == 'this') {
            $fieldValue = $this->thisfieldValue;
        } else {
            $fieldValue = $this->form->getFieldValueByName($matches[0]);
        }
        
        //输出
        if ($fieldValue === null) {
            return 'null';
        } elseif ($fieldValue === '') {
            return '""';
        } elseif (is_string($fieldValue)) {
            return "'{$fieldValue}'";
        } else {
            return $fieldValue;
        }
    }
    
}
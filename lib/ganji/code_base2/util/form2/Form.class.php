<?php

/**
 * 表单类
 * 一个Form拥有多个Fields
 * 一个Field拥有多个rules
 */
require_once dirname(__FILE__) . '/field/Field.class.php';

class Form {

    /**
     * @var array
     */
    private $errors = array();

    /**
     * 字段
     * @var array
     */
    private $fields = array();

    /**
     * 表单数据
     * @var array
     */
    private $formData = array();

    /**
     * 构造form
     * @param $formConfig 表单配置
     * array(
        'title' => array(
            'rules' => array(
                array('maxLength', 100, '最长不超过10位   title'),
                array('minLength', 15, '最短不低于15   title'),
                array('required', true, '必填写   title'),
                array('regexp', 'xxx', 'regexp   title'),
                array('costomize', 'url xxxx', 'err url1 xxxx   title'),
            ),
        ),
        'description' => array(
            'rules' => array(
                array('regexp', 'regexp', 'regexp description', false, 'ig'),
                array('ajax', 'url', 'error description'),
            )
        ),
     * )
     */
    function __construct($formConfig) {
        //注册fields
        foreach($formConfig as $fieldName => $fieldConfig) {
            $this->addField($fieldName, $fieldConfig);
        }
    }

    /**
     * 注册field
     * @param $fieldName 字段名
     * @param $fieldConfig 字段配置 see __construct
     * @throws Exception
     */
    public function addField($fieldName, $fieldConfig) {
        if($fieldName == 'this') {
            throw new Exception('forbidden use this!');
        }
        
        $this->fields[$fieldName] = new Field($fieldName, $fieldConfig, $this);
    }

    /**
     * 去掉field
     * @param $fieldName
     */
    public function removeField($fieldName) {
        unset($this->fields[$fieldName]);
    }

    /**
     * 根据name获取field对象
     * @param $fieldName
     * @return mixed
     */
    public function getField($fieldName) {
        return $this->fields[$fieldName];
    }

    /**
     * 填充表单数据，用于验证
     * @param $datas
     */
    public function setFormData($datas) {
        //只保留注册字段
        $this->formData = array_intersect_key($datas, $this->fields);
    }

    public function getFieldValueByName($key) {
        return isset($this->formData[$key]) ? $this->formData[$key] : null;
    }

    /**
     * 获取表单数据
     */
    public function getFormData() {
        return $this->formData;
    }

    /**
     * 表单验证
     * @return bool
     */
    public function validate() {
        $valid = true;
        $this->errors = array();

        if(count($this->fields) > 0) {
            foreach($this->fields as $fieldName => $fieldObj) {
                $fieldValue = $this->getFieldValueByName($fieldName);
                if(!$fieldObj->validate($fieldValue)) {
                    $valid = false;
                    $errorMessage = $fieldObj->getErrorMessage();
                    $this->setErrors($fieldName, $errorMessage);
                }
            }
        }

        return $valid;
    }

    /**
     * error setter
     * @param $fieldName
     * @param $errorMessage
     */
    public function setErrors($fieldName, $errorMessage) {
        $this->errors[$fieldName] = $errorMessage;
    }

    /**
     * 获取validate之后的错误
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
}

<?php

/**
 * @breif
 */
require_once '/server/www/ganji/ganji_online/code_base2/config/config.inc.php';
require_once CODE_BASE2 . '/util/form/FormNamespace.class.php';
require_once CODE_BASE2 . '/app/post/form/PostValidatorConfig.php';
require_once GANJI_V5 . '/app/vehicle/ershouche/include/ErshoucheWantedVars.class.php';
require_once CODE_BASE2 . '/app/post/adapter/vehicle/include/VehicleVars.class.php';

class DemoForm extends FormNamespace {
    
    public function __construct() {
        
        //ajax验证
        $this->addField(array(
            'name' => 'ajax',
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_AJAX,
                    'ajaxUrl'         => '/ajax/checkPhoneLimitInfo.php',
                    //'errorMessage'    => '此手机已被绑定限制发帖，只有绑定的账号才能使用发帖！', //废弃，msg由ajax返回
                    'success' => 'app/ms_v2/xxx.js#dddd',
                ),
            ),
        ));
        
        //regexp验证
        $this->addField(array(
            'name' => 'regexp',
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => RegexpConfig::INT,
                    'errorMessage'    => '要填写正整数哦111',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => RegexpConfig::INT,
                    'errorMessage'    => '要填写正整数哦222',
                    'exclude'         => true,
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => '/([0-9-]){7,20}/',
                    'exclude'         => true,
                    'errorMessage'    => '描述不能填写电话',
                )
            )
        ));
        
        //价格下限
        $this->addField(array(
            'name' => 'price_begin',
            'value' => 'sss',
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '忘记填写价格下限啦',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => RegexpConfig::INT,
                    'errorMessage'    => '要填写正整数哦',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_COMPARE_VALUE,
                    'operator'        => '!=',
                    'toValue'         => 0,
                    'errorMessage'    => '价格不能为“0”',
                )
            )
        ));
        
        //购车用途
        $this->addField(array(
            'name'              => 'purpose',
            'dataSource'        => ErshoucheWantedVars::$CAR_PURPOSE,
            'value'             => array(1, 3),
            'rules'             => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择买车用途哦',
                )
            )
        ));
        
        //车型
        $this->addField(array(
            'type'              => 'checkboxlist',
            'name'              => 'auto_type',
            'dataSource'        => VehicleVars::$CHEXINGTYPE,
            //'value'             => 1,
            'rules'             => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择类型哦',
                )
            )
        ));
        
        //变速箱
        $this->addField(array(
            'type'              => 'checkboxlist',
            'name'              => 'gearbox',
            'dataSource'        => VehicleVars::$GEARBOX,
            //'value'             => 1,
            'rules'             => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择变速箱哦',
                )
            )
        ));
        
        //车厢数量
        $this->addField(array(
            'type'              => 'checkboxlist',
            'name'              => 'carriage',
            'dataSource'        => ErshoucheWantedVars::$CARRIAGE,
            //'value'             => 1,
            'rules'             => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择车辆结构哦',
                )
            )
        ));
        
        //车龄
        $this->addField(array(
            'type' => 'select',
            'name' => 'car_age',
            'firstText' => '请选择',
            'dataSource' => VehicleVars::$ESC_LICENSE_DATE,
            'value' => 3,
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择车龄哦',
                )
            )
        ));
        
        //价格选项
        $this->addField(array(
            'name' => 'price',
            'dataSource' => VehicleVars::$PRICE_TYPE_CAR_IN_URL
        ));

        //价格上限
        $this->addField(array(
            'name' => 'price_end',
            'value' => 'sss',
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '忘记填写价格上限啦',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => RegexpConfig::INT,
                    'errorMessage'    => '要填写正整数哦',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_COMPARE_VALUE,
                    'operator'        => '!=',
                    'toValue'         => 0,
                    'errorMessage'    => '价格不能为“0”',
                )
            )
        ));
        
        //描述
        $descriptionField = PostValidatorConfig::description(array(
            'focusMessage' => '10-800字，不能填写电话、特殊符号',
            'noPhone' => true,
        ));
        $descriptionField['value'] = 'textaressssa';
        $this->addField($descriptionField);
        
        //联系人
        $this->addField(PostValidatorConfig::person());
        
        //联系电话
        $this->addField(array(//电话
            'type'              => 'text',
            'name'              => 'phone',
            'rules'             => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '忘记填写联系电话啦',
                ),
                array(
                    'mode'            => ValidatorConfig::MODE_REGEXP,
                    'regexp'          => RegexpConfig::PHONE,
                    'errorMessage'    => '电话格式错误，如87654321-001或400-1234-5678或138********',
                ),
                PostValidatorConfig::$PHONE_LIMIT_RULE,
            )
        ));
        
        //来源
        $this->addField(array(
            'name' => 'agent',
            'dataSource' => array(0 => '我是个人', 1 => '我是商家'),
            'rules' => array(
                array(
                    'mode'            => ValidatorConfig::MODE_REQUIRED,
                    'errorMessage'    => '您还没有选择发布人哦',
                )
            )
        ));
    }
}

$form = new DemoForm();

$ret = $form->getField('ajax')->toHtml('text');
print_r($ret);
echo "\n\n";

$ret = $form->getField('regexp')->toHtml('text');
print_r($ret);
echo "\n\n";


$ret = $form->getField('description')->toHtml('textarea', array('class' => 'sss', 'disabled', 'size' => 4, 'data-tip-span' => '#sss'));
print_r($ret);
echo "\n\n";

$ret = $form->getField('agent')->toHtml('radio_list');
print_r($ret);
echo "\n\n";

$ret = $form->getField('price_begin')->toHtml('text', array('readonly', 'class' => 'input-text', 'style' => 'width:68px;'));
print_r($ret);
echo "\n\n";

$ret = $form->getField('phone')->toHtml('text');
print_r($ret);
echo "\n\n";

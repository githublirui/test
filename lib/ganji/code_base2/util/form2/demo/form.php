<?php
require_once '/server/www/ganji/ganji_online/code_base2/config/config.inc.php';
require_once dirname(__FILE__) . '/../Form.class.php';
require_once CODE_BASE2 . '/util/form/validator/Regexp.config.php';

//1.配置表单
class TestForm extends Form {

    public function __construct() {
        parent::__construct(array(
            'im' => array(
                'rules' => array(
                    array('required', false),
                    array('regexp', RegexpConfig::QQ, 'im格式有误!'),
                )
            ),
            'title' => array(
                'rules' => array(
                    array('required', 'need_check_title>1', '必填写'),
                    array('maxLength', 100, '最长不超过100位'),
                    array('minLength', 15, '最短不低于15'),
                    array('regexp', RegexpConfig::NO_SPECIAL, 'regexp description', true),
                    array('regexp', RegexpConfig::INCLUDE_PHONE, '标题不能出现电话号码哦', true),

                ),
            ),
            'need_check_title' => array(
                'rules' => array(),
            ),
            'requiredField' => array(
                'rules' => array(
                    array('required', true, '必填字段 required1'),
                ),
            ),
            'minValueField' => array(
                'rules' => array(
                    array('min', 10, 'must > 10 minValue'),
                ),
            ),
            'maxValueField' => array(
                'rules' => array(
                    array('max', 100, 'must < 10 maxValue'),
                ),
            ),
            'formatValue' => array(
                'rules' => array(
                    array('format', 'chinese_english', 'should be chinese or english'),
                )
            ),
            'compareValue' => array(
                'rules' => array(
                    array('compare', 'maxValueField >= this', '价格必须小于maxValueField哦'),
                    array('compare', 'this > 0 && 1>0', '价格必须大于0哦'),
                )
            ),
            'username' => array(
                'rules' => array(
                    array('custom', 'xxxx.js#xxFn', '前端验证不通过'),
                    array('ajax', array('url' => 'http:/xxxxx', 'type' => 'GET', 'dataType' => 'json'))
                )
            ),
            'password' => array(
                'rules' => array(
                    array('minLength', 6, '最短不低于6'),
                    array('php_custom', $this, 'checkPassword', array($this, 1, 2))
                )
            ),
        ));
    }

    /**
     * 重写validate，用于其他校验
     * @return bool|boolen
     */
    public function validate() {
        //原有校验
        $formValid = parent::validate();
        
        //独立校验im
        $im = $this->getFieldValueByName('im');
        if ($im != 123) {
            $this->setErrors('im', 'im wrong');
            return false;
        }

        return $formValid;
    }

    /**
     * custom回调函数
     * @param $fieldValue 对应formData当前值
     * @param $thisForm
     * @param $param1
     * @param $param2
     * @return array 是否验证通过,错误信息
     */
    public function checkPassword($fieldValue, $thisForm, $param1, $param2) {
        $username = $thisForm->getFieldValueByName('username');
        $password = $fieldValue;
        if ($username != 'wangchuanzheng') {
            return array(false, '用户名或密码错误');
        }
        
        if ($password != '123456') {
            return array(false, '用户名或密码错误');
        }
        
        return array(true, '验证成功');
    }
}

//2.实例化表单
$form = new TestForm();

//动态添加field
$form->addField('displacement', array(
    'rules' => array(
        array('required', true, '忘记选择排量了'),
    )
));

//3.用户数据填充到表单
$form->setFormData(array(
    'need_check_title' => '2',
    'title' => '186ssssssssssssss05958635',
    'im' => 'dsd',
    'description' => 'desc value',
    'requiredField' => '<html></html></span>   </span>',
    'minValueField' => '6',
    'maxValueField' => '110',
    'formatValue' => '你好2',
    'compareValue' => '1',
    'username' => 'wangchuanzheng',
    'password' => '1234562',
    'displacement' => 'dd',
));

//4.校验
$rs = $form->validate();

//5.获取错误信息
$errs = $form->getErrors();

//6.获取验证过的表单数据
$formData = $form->getFormData();

var_dump($rs, $errs);




//rule传递给前端，smarty对应文件mobile/mobile_base/apps/wap/plugin/smarty/function.get_data_rules.php
function getDataRules($form, $fieldName) {
    if (empty($form) || !is_object($form) || !($form instanceof Form) || empty($fieldName)) {
        return '';
    }

    $rules = $form->getField($fieldName)->getRules();
    foreach ($rules as $k => &$rule) {
        switch ($rule[0]) {
            case 'php_custom' :
                //php_custom属于后端校验逻辑，不必带给前端
                unset($rules[$k]);
                break;
            case 'regexp':
                //正则前端不接受前后斜杠
                $rule[1] = trim($rule[1], '/');
                break;
            case 'compare':
            case 'required':
                //前端不需要this对象
                unset($rule[3]);
                break;
        }
    }
    
    return json_encode($rules);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form</title>
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
</head>
<body>
    <form>
        <input name='title' id="title" data-rules="<?php echo getDataRules($form, 'title')?>" />
        <input name='username' id="username" data-rules="<?php echo getDataRules($form, 'username')?>" />
        <input name='password' id="password" data-rules="<?php echo getDataRules($form, 'password')?>" />

    </form>
</body>
</html>

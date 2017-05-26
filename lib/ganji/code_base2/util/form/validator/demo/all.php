<?php 

require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';


class MyForm extends Form 
{
	function init()
	{
		$this->validatorConfig = array(
			array(
			    'mode'            => ValidatorConfig::MODE_REQUIRED,
			    'fieldName'       => 'username',
	            'errorMessage'    => '用户名不能为空',
	            'defaultMessage'  => '请填写用户名',
	            'focusMessage'    => '请填写用户名,3-20个字符',
			),		
			array(
                'mode'            => ValidatorConfig::MODE_LENGTH,
	            'fieldName'       => 'username',
	            'minLength'       => 3,
	            'maxLength'       => 20,
	            'errorMessage'    => '字符长度要在3-20之间',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_AJAX,
	            'fieldName'       => 'username',
	            'ajaxUrl'         => 'check_username.php',
	            'errorMessage'    => '用户名不允许',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_CUSTOM,
	            'fieldName'       => 'username',
	            'phpCallback'     => array($this, 'usernamePhpCallback'),
	            'jsCallback'      => 'usernameJsCallback',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
	            'fieldName'       => 'password',
	            'errorMessage'    => '密码不能为空',
	            'defaultMessage'  => '请填写密码',
	            'focusMessage'    => '请填写密码，只能是数字',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REGEXP,
	            'fieldName'       => 'password',
	            'regexp'          => '/^([0-9]+)(\.\d)?$/',
	            'errorMessage'    => '密码只能是数字',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
	            'fieldName'       => 'password2',
	            'errorMessage'    => '必须再填写一次密码', '请再次填写密码',
	            'defaultMessage'  => '请再次填写密码',
	            'focusMessage'    => '请再次填写密码，确保两次填写的一致',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_COMPARE_FIELD,
	            'fieldName'       => 'password2',
	            'operator'        => '==',
	            'toFieldName'     => 'password',
	            'errorMessage'    => '再次填定的密码不正确',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_DEFINED_REGEXP,
	            'fieldName'       => 'email',
	            'regexpType'      => RegexpConfig::EMAIL,
	            'errorMessage'    => 'Email不正确',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
	            'fieldName'       => 'sex',
	            'errorMessage'    => '必须选择性别',
	            'defaultMessage'  => '请选择性别',
	            'tipSpanId'       => 'tip_span_sex',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED, 
	            'fieldName'       => 'tag[]',
	            'errorMessage'    => '请至少选择一个栏目',
	            'defaultMessage'  => '请选择栏目',
	            'tipSpanId'       => 'tip_span_tag',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
	            'fieldName'       => 'area',
	            'errorMessage'    => '必须选择地区',
	            'defaultMessage'  => '请选择地区',
	            'emptyValue'      => '0',
	        ),	        
	        array(
                'mode'            => ValidatorConfig::MODE_COMPARE_VALUE,
	            'fieldName'       => 'area',
	            'operator'        => '>=',
	            'toValue'         => '2',
	            'errorMessage'    => '地区只能选上海',
	        ),
	    );
	}
	
	function usernamePhpCallback($value){
		if (preg_match('/^\w+$/', $value))
		{
			return true;
		}
		else {
			return '用户名只能是字母、下划线和数字';
		}
	}
	
}
$form = new MyForm();
$form->init();

if ($_SERVER['REQUEST_METHOD']=='POST'){
    if (!$form->validate()){
    	$errMsg = '* ' . implode("<br />* ", $form->getValidatorErrorMessages());
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>表单验证</title>
<link href="/css/validator.css" rel="stylesheet" type="text/css" />
<script src="../../../../apps/media/js/jquery.min.js" type="text/javascript"></script>
<script src="../../../../apps/media/js/validator/validator.js" type="text/javascript"></script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <h3>比较全面的表单验证示例</h3>
  <div style="color:#f00"><?= $errMsg ?></div>
  <p>
    <input type="text" name="username" />
  </p>
  <p>
    <input type="text" name="password" />
  </p>
  <p>
    <input type="text" name="password2" />
  </p>
  <p>
    <input type="text" name="email" />
  </p>
  <p>
    <input type="radio" name="sex" value="1" />男 
    <input type="radio" name="sex" value="2" />女 
    <span id="tip_span_sex"></span>
  </p>
  <p>
    <input type="checkbox" name="tag[]" value="1" />娱乐 
    <input type="checkbox" name="tag[]" value="2" />财经 
    <span id="tip_span_tag"></span>
  </p>
  <p>
    <select name="area">
        <option value="0">地区</option>
        <option value="1">北京</option>
        <option value="2">上海</option>
    </select>
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>

<script type="text/javascript">
function usernameJsCallback(value){
    if (/^\w+$/.test(value)){
        return true;
    }
    else {
        return '用户名只能是字母、下划线和数字';
    }
}
$.validator.addFormCallback('form1', function(){
    $('#submitBtn').parent().html('<span style="color:red">正在登录···</span>');
});
</script>
<?= $form->getValidatorJs() ?>

</body>
</html>
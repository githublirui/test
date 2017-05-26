<?php 
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm extends Form 
{
	function __construct()
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
	    );
	}
}
$form = new MyForm();

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
<link  href="/css/validator.css" rel="stylesheet" type="text/css" />
<script src="../../../../apps/media/js/jquery.min.js" type="text/javascript"></script>
<script src="../../../../apps/media/js/validator/validator.js" type="text/javascript"></script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <h3>为表单字段添加回调函数示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <input type="text" name="username" />
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>

<?= $form->getValidatorJs() ?>

<script type="text/javascript">
//反回布尔值的函数
function username_callback1(value){
    return /^\w+$/.test(value);
}
$.validator('username').setCallback(username_callback1, '用户名只能是字母、下划线和数字');

//返回true或错误信息的函数
function username_callback2(value){
    if (/^(admin|manager|webmaster)+$/.test(value)){
        return '不接受此用户名';
    }
    else {
        return true;
    }
}
$.validator('username').setCallback(username_callback2);
</script>

</body>
</html>
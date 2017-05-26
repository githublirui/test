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
                'fieldName'       => 'password',
                'errorMessage'    => '密码不能为空',
                'defaultMessage'  => '请输入密码',
                'focusMessage'    => '为了确保你输入的密码正确，请再次输入密码',
	        ),
	        array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
                'fieldName'       => 'password2',
                'errorMessage'    => '请再输一遍密码',
                'defaultMessage'  => '请再输一遍密码',
                'focusMessage'    => '为了确保你输入的密码正确，请再次输入密码',
            ),
	        array(
                'mode'            => ValidatorConfig::MODE_COMPARE_FIELD,
                'fieldName'       => 'password2',
                'operator'        => '==',
                'toFieldName'     => 'password',
                'errorMessage'    => '第二次写入的密码不对',
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
  <h3>与其它表单字段值对比的表单验证示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <input type="text" name="password" />
  </p>
  <p>
    <input type="text" name="password2" />
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>

<?= $form->getValidatorJs() ?>

</body>
</html>
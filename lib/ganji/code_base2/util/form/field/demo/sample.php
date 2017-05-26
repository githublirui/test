<?php 
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm extends Form 
{
    function __construct()
    {
        $this->addField(FieldFactory::creatField(array(
			'name'            => 'username',
			//'type'            => 'radio',
			//'value'           => '2',
			//'checked'         => true,
			'defaultMessage'  => '请填写用户名',
			'focusMessage'    => '请填写用户名,3-20个字符',
			'validateRules'   => array(
				array(
					'mode'            => ValidatorConfig::MODE_REQUIRED,
					'errorMessage'    => '用户名不能为空',
				),      
				array(
					'mode'            => ValidatorConfig::MODE_LENGTH,
					'minLength'       => 3,
					'maxLength'       => 20,
					'errorMessage'    => '字符长度要在3-20之间',
				), 
			),
		)));
    }
    
}
$form = new MyForm();

if ($_SERVER['REQUEST_METHOD']=='POST'){
    //if (!$form->validate()){
    //    $errMsg = '* ' . implode("<br />* ", $form->getValidatorErrorMessages());
    //}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>表单验证</title>
<link  href="../../../../apps/media/js/validator/validator.css" rel="stylesheet" type="text/css" />
<script src="../../../../apps/media/js/jquery.min.js" type="text/javascript"></script>
<script src="../../../../apps/media/js/validator/validator.js" type="text/javascript"></script>
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <h3>表单验证示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <?= $form->getField('username') ?>
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>


<?= $form->getValidatorJs() ?>

</body>
</html>
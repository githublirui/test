<?php 
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm1 extends Form 
{
    function __construct()
    {
        $this->validatorConfig = array(
            array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
                'fieldName'       => 'form1::username',
                'errorMessage'    => '用户名不能为空',
                'defaultMessage'  => '请填写用户名',
                'focusMessage'    => '请填写用户名,3-20个字符',
            ),  
        );
    }    
}

class MyForm2 extends Form 
{
    function __construct()
    {
        $this->validatorConfig = array(
            array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
                'fieldName'       => 'form2::username',
                'errorMessage'    => '用户名不能为空',
                'defaultMessage'  => '请填写用户名',
                'focusMessage'    => '请填写用户名,3-20个字符',
            ), 
        );
    }    
}

$form1 = new MyForm1();
$form2 = new MyForm2();
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
  <h3>多表单验证示例</h3>
  <p>
    表单一：
  </p>
  <p>
    <input type="text" name="username" id="username_login" />
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="登录" />
  </p>
</form>

<form id="form2" name="form2" method="post" action="">
  <p>
    表单二：
  </p>
  <p>
    <input type="text" name="username" id="username_register" />
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="注册" />
  </p>
</form>

<?= $form1->getValidatorJs() ?>

<?= $form2->getValidatorJs() ?>

</body>
</html>
<?php 
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm extends Form 
{
	function __construct()
	{
		$this->validatorConfig = array(
			array(
                'mode'              => ValidatorConfig::MODE_REQUIRED,
                'fieldName'         => 'username',
                'errorMessage'      => '用户名不能为空',
                'defaultMessage'    => '请填写用户名',
                'focusMessage'      => '请填写用户名,3-20个字符',
            ),
            array(
                'mode'              => ValidatorConfig::MODE_REQUIRED,
                'fieldName'         => 'nickname',
                'errorMessage'      => '昵称不能为空',
                'defaultMessage'    => '请填写昵称',
                'focusMessage'      => '请填写昵称',
                'disabledPhpCallback' => array($this, 'phpCallback'),
                'disabledJsCallback'  => 'jsCallback',
            ), 
	    );
	}
	
    //php回调函数
	function phpCallback()
    {
        return $_POST['"show_nickname"'] ? false : true;
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
  <h3>设置禁用已设置的验证规则的示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <input type="text" name="username" />
  </p>
  <p>
    <input name="show_nickname" id="show_nickname" type="checkbox" value="1" />  显示昵称
  </p>
  <p id="nickname_p" style="display:none">
    <input type="text" name="nickname" />
  </p>  
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>

<script type="text/javascript">
$("#show_nickname").click(function(){
    if ($(this).attr('checked')){
        $("#nickname_p").show();
    }
    else {
        $("#nickname_p").hide();
    }
});

//js回调函数
function jsCallback(value){
	return $('#show_nickname').attr('checked') ? false : true;
}
</script>

<?= $form->getValidatorJs() ?>

</body>
</html>
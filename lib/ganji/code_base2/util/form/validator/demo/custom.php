<?php 
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm extends Form 
{
	function __construct()
	{
		$this->validatorConfig = array(
			array(
                'mode'            => ValidatorConfig::MODE_CUSTOM,
                'fieldName'       => 'username',
                'phpCallback'     => array($this, 'usernamePhpCallback'),
                'jsCallback'      => 'usernameJsCallback',
			    'errorMessage'    => '有错',
	        ),
	    );
	}
	
    function usernamePhpCallback($value)
    {
        $bool = preg_match('/^\w+$/', $value);
    	
    	//返回布尔值
        //return $bool;

		if ($value == ''){
			return '用户名不能为空';
		}
    	
    	if ($bool) {
            return true;
        }
        else {
            //出错时返回错误信息
        	return '用户名只能是字母、下划线和数字';
        }
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
  <h3>自定义回调函数的表单验证示例</h3>
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

<script type="text/javascript">
function usernameJsCallback(value){
	var bool = /^\w+$/.test(value);

	//return bool;
	
	if (value == ''){
		return '用户名不能为空';
	}

    if (bool){
        return true;
    }
    else {
        return '用户名只能是字母、下划线和数字';
    }
}
</script>

<?= $form->getValidatorJs() ?>

</body>
</html>
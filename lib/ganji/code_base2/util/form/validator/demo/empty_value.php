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
                'fieldName'       => 'search',
                'errorMessage'    => '搜索关键词不能为空',
                'emptyValue'      => '请输入搜索关键词',
	        ),
			array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
                'fieldName'       => 'url',
                'errorMessage'    => 'url不能为空',
                'emptyValue'      => 'http://',
				'focusValue'      => 'http://',
	        ),
            array(
                'mode'            => ValidatorConfig::MODE_REQUIRED,
                'fieldName'       => 'area',
                'errorMessage'    => '必须选择地区',
                'defaultMessage'  => '请选择地区',
                'emptyValue'      => '0',
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
  <h3>设置表示空值的示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <input type="text" name="search" />
  </p>
  <p>
    <input type="text" name="url" />
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

<?= $form->getValidatorJs() ?>

</body>
</html>
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
                'fieldName'       => 'tag[]',
                'errorMessage'    => '请至少选择一个栏目',
                'defaultMessage'  => '请选择栏目',
                'tipSpanId'       => 'tip_span_tag',
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
  <h3>name中含有[]的表单验证示例</h3>
  <div style="color:#f00">
    <?= $errMsg ?>
  </div>
  <p>
    <input type="checkbox" name="tag[]" value="1" />娱乐 
    <input type="checkbox" name="tag[]" value="2" />财经 
    <span id="tip_span_tag"></span>
  </p>
  <p>
    <input id="submitBtn" type="submit" name="Submit" value="提交" />
  </p>
</form>

<?= $form->getValidatorJs() ?>

</body>
</html>
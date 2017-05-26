<?php 
require_once '../Validator.class.php';
require_once '../RequiredValidator.class.php';
require_once '../CompareFieldValidator.class.php';
require_once '../CompareValueValidator.class.php';
require_once '../LengthValidator.class.php';
require_once '../RegexpValidator.class.php';
require_once '../AjaxValidator.class.php';


//不为空的验证实例
$requiredValidator = new RequiredValidator('username', '用户名不能为空', '请填写用户名', '请填写用户名,3-20个字符', '请输入用户名', 'tip_id_username');
//相应js
echo $requiredValidator->renderJs();
echo '<br /><br />';

//模拟表单提交数据
//$_POST['username'] = '';
//$_POST['username'] = '请输入用户名';
$_GET['username'] = 'longweiguo';

//执行验证
$requiredValidator->validate();
//是否通过验证
echo ($requiredValidator->isValid()) ? 'ok' : $requiredValidator->getErrorMessage();
echo '<br /><br />';


//字段值对比验证实例
$compareFieldValidator = new CompareFieldValidator('password', '==', 'password2', '再次填定的密码不正确');
echo $compareFieldValidator->renderJs();
echo '<br /><br />';
$_POST['password']  = '123';
$_POST['password2'] = '123';
$compareFieldValidator->validate();
echo ($compareFieldValidator->isValid()) ? 'ok' : $compareFieldValidator->getErrorMessage();
echo '<br /><br />';


//字段值对比验证实例
$compareValueValidator = new CompareValueValidator('date', '<=', date("Y-m-d"), '日期不能超过今天');
echo $compareValueValidator->renderJs();
echo '<br /><br />';
$_POST['date']  = '2009-10-01';
$compareValueValidator->validate();
echo ($compareValueValidator->isValid()) ? 'ok' : $compareValueValidator->getErrorMessage();
echo '<br /><br />';


//字符长度验证实例
$lengthValidator = new LengthValidator('username', '3', '20', '字符长度要在3-20之间');
echo $lengthValidator->renderJs();
echo '<br /><br />';
$_POST['username']  = '1155555555555555';
$lengthValidator->validate();
echo ($lengthValidator->isValid()) ? 'ok' : $lengthValidator->getErrorMessage();
echo '<br /><br />';


//正则表达式验证实例
//$regexpValidator = new RegexpValidator('email', '/^\w+((-|.)\w+)*@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/', 'Email不正确');
$regexpValidator = new RegexpValidator('email', 'email', 'Email不正确');
echo $regexpValidator->renderJs();
echo '<br /><br />';
$_POST['email']  = 'lwg_8088@yahoo.com.cn';
$regexpValidator->validate();
echo ($regexpValidator->isValid()) ? 'ok' : $regexpValidator->getErrorMessage();
echo '<br /><br />';


//ajax验证实例
$ajaxValidator = new AjaxValidator('username', 'check_username.php', '用户名不允许');
echo $ajaxValidator->renderJs();
echo '<br /><br />';
$_POST['username']  = 'longweiguo';
$ajaxValidator->validate();
echo ($ajaxValidator->isValid()) ? 'ok' : $ajaxValidator->getErrorMessage();
echo '<br /><br />';

?>
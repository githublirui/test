<?php
require_once dirname(__FILE__) . '/../FieldFactory.class.php';


$inputText = FieldFactory::creatField(array(
    'type'  => 'text',
    'name'  => 'username',
    'id'    => 'username',
    'size'  => 5
));
echo $inputText;
echo "<br><br>\n";

$inputPassword = FieldFactory::creatField(array(
    'type'  => 'password',
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 5
));
echo $inputPassword;
echo "<br><br>\n";

$inputHidden = FieldFactory::creatField(array(
    'type'  => 'hidden',
    'name'  => 'fromUrl',
    'id'    => 'fromUrl',
));
echo $inputHidden;
echo "<br><br>\n";

$inputRadio = FieldFactory::creatField(array(
    'type'  => 'radio',
    'name'  => 'sex',
    'id'    => 'sex',
    'value' => '1',
));
echo $inputRadio;
echo "<br><br>\n";

$inputCheckbox = FieldFactory::creatField(array(
    'type'  => 'checkbox',
    'name'  => 'area',
    'id'    => 'area',
    'value' => '1',
));
echo $inputCheckbox;
echo "<br><br>\n";

$textarea = FieldFactory::creatField(array(
    'type'  => 'textarea',
    'name'  => 'body',
    'id'    => 'body',
    'cols'  => 10,
    'rows'  => 5,
    'wrap'  => "virtual",
));
echo $textarea;
echo "<br><br>\n";

$_POST['price'] = 1;
$select = FieldFactory::creatField(array(
    'type'  => 'select',
    'name'  => 'price',
    'id'    => 'price',
    'dataSource' => array('上海', '北京'),
));
echo $select;
echo "<br><br>\n";

$_POST['cagegory'] = 1;
$radioList = FieldFactory::creatField(array(
    'type'  => 'radio_list',
    'name'  => 'cagegory',
    'id'    => 'cagegory',
    'dataSource' => array('出租', '求租'),
));
echo $radioList;
echo "<br><br>\n";

$_POST['sort'] = 2;
$checkboxList = FieldFactory::creatField(array(
    'type'  => 'checkbox_list',
    'name'  => 'sort',
    'id'    => 'sort',
    'dataSource' => array(1=>'招聘', 2=>'求职'),
));
echo $checkboxList;
echo "<br><br>\n";

?>
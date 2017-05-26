<?php
require_once '../../../../apps/bootstrap.php';
require_once FW_PATH . '/base/Form.class.php';

class MyForm extends Form 
{
	
}
$form = new MyForm();

$inputText = $form->getField(array(
    'type'  => 'text',
    'name'  => 'username',
    'id'    => 'username',
    'size'  => 5
));
echo $inputText;
echo "<br><br>\n";

$inputPassword = $form->getField(array(
    'type'  => 'password',
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 5
));
echo $inputPassword;
echo "<br><br>\n";

$inputHidden = $form->getField(array(
    'type'  => 'hidden',
    'name'  => 'fromUrl',
    'id'    => 'fromUrl',
));
echo $inputHidden;
echo "<br><br>\n";

$inputRadio = $form->getField(array(
    'type'  => 'radio',
    'name'  => 'sex',
    'id'    => 'sex',
    'value' => '1',
));
echo $inputRadio;
echo "<br><br>\n";

$inputCheckbox = $form->getField(array(
    'type'  => 'checkbox',
    'name'  => 'area',
    'id'    => 'area',
    'value' => '1',
));
echo $inputCheckbox;
echo "<br><br>\n";

$textarea = $form->getField(array(
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
$select = $form->getField(array(
    'type'  => 'select',
    'name'  => 'price',
    'id'    => 'price',
    'dataSource' => array('上海', '北京'),
));
echo $select;
echo "<br><br>\n";

$_POST['cagegory'] = 1;
$radioList = $form->getField(array(
    'type'  => 'radio_list',
    'name'  => 'cagegory',
    'id'    => 'cagegory',
    'dataSource' => array('出租', '求租'),
));
echo $radioList;
echo "<br><br>\n";

$_POST['sort'] = 2;
$checkboxList = $form->getField(array(
    'type'  => 'checkbox_list',
    'name'  => 'sort',
    'id'    => 'sort',
    'dataSource' => array(1=>'招聘', 2=>'求职'),
));
echo $checkboxList;
echo "<br><br>\n";

?>
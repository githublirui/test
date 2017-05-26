<?php
/**
 *  可选按钮列表模板，
 * 示例：
 * $form->getField('purpose')->toHtml('checkbox_list', array('class' => 'sss', 'disabled', 'size' => 4, 'data-tip-span' => '#sss'));
 * 生成：
 *   <label for="purpose_0">
 *       <input id="purpose_0" name="purpose[]" value="1" type="checkbox"  checked  data-required='[true,"您还没有选择买车用途哦"]' class='sss' disabled  size='4' data-tip-span='#sss'/>上下班代步
 *   </label>    
 *   <label for="purpose_1">
 *       <input id="purpose_1" name="purpose[]" value="2" type="checkbox"   class='sss' disabled  size='4'/>家用出游
 *   </label>    
 *   <label for="purpose_2">
 *       <input id="purpose_2" name="purpose[]" value="3" type="checkbox"  checked  class='sss' disabled  size='4'/>接送客户
 *   </label>   
 *   <label for="purpose_3">
 *       <input id="purpose_3" name="purpose[]" value="4" type="checkbox"   class='sss' disabled  size='4'/>新手练车
 *   </label>    
 *   <label for="purpose_4">
 *       <input id="purpose_4" name="purpose[]" value="5" type="checkbox"   class='sss' disabled  size='4'/>接送家人
 *   </label>   
 */


//数据源
$dataSource = $field->getDataSource();
if (empty($dataSource)) {
    return '';
}

//属性
$attributes = is_array($htmlAttrs) ? array_merge($field->getAttributes(), $htmlAttrs) : $field->getAttributes();
unset($attributes['id'], $attributes['value'], $attributes['name'], $attributes['type'], $attributes['disabled']);

//字段值，字段名称
$fieldValue = $field->getValue();
$fieldName = $field->getName();

$i = 0;
foreach ($dataSource as $key => $text) {
    $checked = is_array($fieldValue) && in_array($key, $fieldValue) || $fieldValue !== null && $fieldValue == $key ? ' checked' : '';
    $attrString = '';
    foreach ($attributes as $attrName => $attrValue) {
        if (!is_string($attrName)) {
            //例如disabled
            $attrString .= " {$attrValue} ";
        } else {
            //data-开头的只需放置第一个input即可
            $attrString .= strpos($attrName, 'data-') !== false && $i > 0 ? '' : sprintf(" %s='%s'", $attrName, $attrValue);
        }
    }
    $attrString = is_string($htmlAttrs) ? "{$attrString} {$htmlAttrs}" : $attrString;

    
    echo <<<filedTemplate
    <label for="{$fieldName}_{$i}">
        <input id="{$fieldName}_{$i}" name="{$fieldName}[]" value="{$key}" type="checkbox" {$checked} {$attrString}/>{$text}
    </label>
filedTemplate;
    
    $i++;
}

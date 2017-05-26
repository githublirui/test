<?php

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
    $checked = is_array($fieldValue) && in_array($key, $fieldValue) || $fieldValue !== null && $fieldValue !== '' && $fieldValue == $key ? ' checked' : '';
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
        <input id="{$fieldName}_{$i}" name="{$fieldName}" value="{$key}" type="radio" {$checked} {$attrString}/>{$text}
    </label>
filedTemplate;
    
    $i++;
}

<?php

//属性
$attributes = is_array($htmlAttrs) ? array_merge($field->getAttributes(), $htmlAttrs) : $field->getAttributes();
unset($attributes['value'], $attributes['name'], $attributes['type']);

$attrString = '';
foreach ($attributes as $attrName => $attrValue) {
    $attrString .= is_string($attrName) ? sprintf(" %s='%s'", $attrName, $attrValue) : " {$attrValue} ";
}
$attrString = is_string($htmlAttrs) ? "{$attrString} {$htmlAttrs}" : $attrString;
?>
<input type="text" value="<?php echo $field->getValue();?>" name="<?php echo $field->getName();?>" <?php echo $attrString;?> />
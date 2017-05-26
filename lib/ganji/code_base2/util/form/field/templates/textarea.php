<?php

//属性
$attributes = array_merge($field->getAttributes(), $htmlAttrs);
unset($attributes['value'], $attributes['name'], $attributes['type']);
$attrString = '';
foreach ($attributes as $attrName => $attrValue) {
    $attrString .= is_string($attrName) ? sprintf(" %s='%s'", $attrName, $attrValue) : " {$attrValue} ";
}

?>

<textarea name="<?php echo $field->getName();?>" <?php echo $attrString;?>><?php echo $field->getValue();?></textarea>
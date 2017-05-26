<?php
/**
 * 下拉框模板
 * 示例：
 * $form->getField('price')->toHtml('select', array('class' => 'sss', 'disabled', 'size' => 4, 'data-tip-span' => '#sss'));
 * <select name="price" type="select"  class='sss' disabled  size='4' data-tip-span='#sss'>
            <option value="9">1万以下</option>
            <option value="1">1-3万</option>
            <option value="2">3-5万</option>
            <option value="3">5-10万</option>
            <option value="4">10-15万</option>
            <option value="5">15-20万</option>
            <option value="6">20-25万</option>
            <option value="7">25-40万</option>
            <option value="8">40万以上</option>
    </select>

 */


    $dataSource = $field->getDataSource();
    $firstText  = $field->getFirstText();
    if (!is_array($dataSource) || (count($dataSource) == 0 && empty($firstText))){
        return '';
    }

    $fieldName = $field->getName();
    $fieldValue = $field->getValue();

    $attributes = is_array($htmlAttrs) ? array_merge($field->getAttributes(), $htmlAttrs) : $field->getAttributes();
    unset($attributes['id'], $attributes['value'], $attributes['name'], $attributes['type'], $attributes['disabled']);
    foreach ($attributes as $attrName => $attrValue) {
        $attrString .= is_string($attrName) ? sprintf(" %s='%s'", $attrName, $attrValue) : " {$attrValue} ";
    }
    $attrString = is_string($htmlAttrs) ? "{$attrString} {$htmlAttrs}" : $attrString;
?>

<select name="<?php echo $fieldName;?>" type="select" <?php echo $attrString;?>>
    
    <?php if (!empty($firstText)) { ?>
        <option value="<?php echo $field->getFirstValue();?>"><?php echo $firstText;?></option>
    <?php } ?>
    
        
    <?php foreach ($dataSource as $key => $text) { 
        $selected = $key == $fieldValue ? ' selected' : '';
    ?>
        <option value="<?php echo $key;?>"<?php echo $selected;?>><?php echo $text;?></option>
    <?php } ?>
</select>

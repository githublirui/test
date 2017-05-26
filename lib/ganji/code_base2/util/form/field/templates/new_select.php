<?php
/**
 * 新版发布页样式select模板
 *
 */

    $dataSource = $field->getDataSource();
    $firstText = $field->getFirstText();
    if (!is_array($dataSource) || (count($dataSource) == 0 && empty($firstText))) {
        return '';
    }

    $fieldName = $field->getName();
    $fieldValue = $field->getValue();
    $enableEdit = isset($attributes['enableEdit']) && $attributes['enableEdit'];
    if (!empty($fieldValue)) {
        if (!$enableEdit && !empty($dataSource[$fieldValue])) {
            $firstText = is_string($dataSource[$fieldValue]) ? $dataSource[$fieldValue] : $dataSource[$fieldValue]['text'];
        } else {
            $firstText = $fieldValue;
        }
    }
    if (empty($firstText)) {
        $firstText = $field->getFirstValue();
    }

    $attributes = is_array($htmlAttrs) ? array_merge($field->getAttributes(), $htmlAttrs) : $field->getAttributes();
    $width = isset($attributes['width']) ? intval($attributes['width']) : '';
    $height = isset($attributes['height']) ? intval($attributes['height']) : '';
    $disabled = $field->getDisabled();
    unset($attributes['enableEdit'], $attributes['type'], $attributes['value'], $attributes['name'], $attributes['width']);
    foreach($attributes as $attrName => $attrValue) {
        $attrString .= is_string($attrName) ? sprintf(" %s='%s'", $attrName, $attrValue) : " {$attrValue} ";
    }
    $attrString = is_string($htmlAttrs) ? "{$attrString} {$htmlAttrs}" : $attrString;
?>

<div class="comselect" data-widget="app/ms_v2/widget/form/select.js" <?php echo $attrString;?>>
    <input <?php echo ($disabled && empty($dataSource)) ? ' disabled="disabled"' : '';?> type="text" data-role="text" <?php echo empty($width) ? '' : "style='width:{$width}px;'"; ?> name="<?php echo $enableEdit ? $fieldName : ''?>" class="inputype" value="<?php echo $firstText;?>" <?php echo $enableEdit ? '' : 'readonly';?>>
    <?php if (!$enableEdit) {?>
    <input type="hidden" data-role="input" name="<?php echo $fieldName;?>" value="<?php echo isset($dataSource[$fieldValue]['value']) ? $dataSource[$fieldValue]['value'] : $fieldValue;?>">
    <?php }?>
    <div class="p-rl">
        <div class="downbox">
            <ul <?php echo empty($height) ? '' : "style='height:{$height}px;'"; ?> data-role="list">
                <?php foreach($dataSource as $key => $val) {?>
                <li data-value="<?php echo is_array($val) && isset($val['value']) ? $val['value'] : $key;?>" data-role="item"><a href="javascript:void(0);"><?php echo is_array($val) && isset($val['text']) ? $val['text'] : $val;?></a></li>
                <?php }?>
            </ul>
        </div>
    </div>
</div>

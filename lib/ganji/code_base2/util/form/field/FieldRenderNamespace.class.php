<?php

/**
 * @breif
 */
require_once CODE_BASE2 . '/util/tpl/PhpTemplate.class.php';

class FieldRenderNamespace {
    
    /**
     * 由filed对象和模板生成html代码
     * @param type $field FieldNamespace对象，表示一个配置好的字段
     * @param type $templateName 模板名称，例如checkbox_list
     * @param array $htmlAttrs html附加属性，例如id,class,disabled
     * @return string html代码
     */
    public static function getHtml($field, $templateName, $htmlAttrs = array()) {
        if (empty($templateName) || !($field instanceof FieldNamespace)) {
            return '';
        }

        //$field与$htmlAttrs作为参数带到模板
        ob_start();
        include CODE_BASE2 . "/util/form/field/templates/{$templateName}.php";
        $ret = ob_get_contents();
        ob_end_clean();
        
        return $ret;
    }
}
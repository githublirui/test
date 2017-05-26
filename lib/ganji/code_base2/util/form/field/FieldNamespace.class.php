<?php
/**
 * 表单字段类
 * @file code_base2/util/from/field/FieldNamespace.class.php
 * @author longweiguo
 * @date 2011-03-21
 * @version 2.0
 *
 */

/**
 * 表单字段类
 * @class FieldNamespace
 *
 */
class FieldNamespace
{
	/**
	 * 表单字段类型
	 */
	const TYPE_TEXT             = 'text';
    const TYPE_PASSWORD         = 'password';
    const TYPE_HIDDEN           = 'hidden';
    const TYPE_RADIO            = 'radio';
    const TYPE_CHECKBOX         = 'checkbox';
    const TYPE_FILE             = 'file';
    const TYPE_TEXTAREA         = 'textarea';
    const TYPE_SELECT           = 'select';
    const TYPE_RADIO_LIST       = 'radiolist';     ///< 一组单选钮
    const TYPE_CHECKBOX_LIST    = 'checkboxlist';  ///< 一组复选框
    const TYPE_HOVERSELECT      = 'hoverselect';
    const TYPE_NEWSELECT        = 'newselect';


	protected $name;

    protected $postName;

    /// 表单字段的id
	public $id;

    /// 表单字段的值
    public $value               = NULL;

    private static $initValue   = array();

    protected $attributes       = array();

	/// 该表单字段是否显示
    public $display             = true;

	// 该表单字段是否禁用
	public $disabled            = false;

    protected $type;

	/// 该表单字段的数据源，用于select、radiolist、checkboxlist
    public $dataSource          = array();

    protected $firstText        = NULL;        //select专用

    protected $firstValue       = NULL;        //select专用

    protected $htmlTagEnabled   = false;       //提交的值中，是否允许html标签

    /**
     * 表示为空的值
     *
     * @var string
     */
    protected $emptyValue = NULL;

    protected $defaultDbValue = NULL;

	protected $dbFieldName = '';

    /**
     * 构造函数
     *
     * @param array $params ['name'=>'username', type='text'...]用来创建一个表单字段的属性，必须包含name下标
     * @return void
     */
    public function __construct($params)
    {
        if (empty($params['name'])){
            die('[FieldNamespace::__construct] Param "name" should not be empty.');
        }
        $this->setName($params['name']);
        unset($params['name']);

    	$this->setAttributes($params);
    }

    public function setType($type=self::TYPE_TEXT)
    {
        switch ($type){
            case self::TYPE_TEXT:
                self::setAttribute('type', 'text');
                break;
            case self::TYPE_SELECT:
                self::setAttribute('type', 'select');
                break;
            case self::TYPE_CHECKBOX_LIST:
            case self::TYPE_CHECKBOX:
                self::setAttribute('type', 'checkbox');
                break;
            case self::TYPE_FILE:
                self::setAttribute('type', 'file');
                break;
            case self::TYPE_TEXTAREA:
                self::setAttribute('type', 'textarea');
                break;
            case self::TYPE_HIDDEN:
                self::setAttribute('type', 'hidden');
                break;
            case self::TYPE_PASSWORD:
                self::setAttribute('type', 'password');
                break;
            case self::TYPE_RADIO:
            case self::TYPE_RADIO_LIST:
                self::setAttribute('type', 'radio');
                break;
            case self::TYPE_HOVERSELECT;
                self::setAttribute('type', 'hoverselect');
                break;
            case self::TYPE_NEWSELECT:
                self::setAttribute('type', 'newselect');
                break;
            default:
				$type = 'text';
                self::setAttribute('type', 'text');
            	break;
        }

        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * 生成html标签字段（支持id,class等html属性）
     * @param string $templateName 模板名称，对应CODE_BASE2 . '/util/form/field/templates下的文件名，例如select
     * @param array|string $htmlAttrs html附加属性（字符串class='xxx'，数组均可array('class' => 'xxxx')）
     * @return string
     */
    public function toHtml($templateName, $htmlAttrs = array()) {
        if (empty($templateName)) {
            return '';
        }

        require_once CODE_BASE2 . '/util/form/field/FieldRenderNamespace.class.php';
        $html = FieldRenderNamespace::getHtml($this, $templateName, $htmlAttrs);

        return $html;
    }

    public function __toString()
    {
        if ($this->getDisplay() == false) {
            return "";
        }

    	if (empty($this->type)){
    		$this->type = self::TYPE_TEXT;
    	}

    	$str = '';
        switch ($this->type){
            case self::TYPE_CHECKBOX_LIST:
            case self::TYPE_RADIO_LIST:
            	$str = $this->renderCheckList();
            	break;
            case self::TYPE_CHECKBOX:
            case self::TYPE_FILE:
            case self::TYPE_HIDDEN:
            case self::TYPE_PASSWORD:
            case self::TYPE_RADIO:
            case self::TYPE_TEXT:
                $str = $this->renderInput();
                break;
            case self::TYPE_SELECT:
                $str = $this->renderSelect();
                break;
            case self::TYPE_TEXTAREA:
                $str = $this->renderTextarea();
                break;
            case self::TYPE_HOVERSELECT:
                $str = $this->renderHoverSelect();
                break;
            case self::TYPE_NEWSELECT:
                $str = $this->renderNewSelect();
                break;
            default:
                $str = $this->renderInput();
                break;
        }
        return $str;
    }

    /**
     * 设置属性
     *
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value)
    {
    	$name  = strtolower($name);

    	switch ($name){
    		case 'name':
    			$this->setName((string)$value);
    			break;
    		case 'id':
    			$this->setId((string)$value);
    		    break;
    		case 'value':
    			$this->setValue($value);
    			break;
    		default:
    			$this->attributes[$name] = self::formatHtmlAttrString((string)$value);
    			break;
    	}
    }

    /**
     * 取得属性
     *
     * @param string $name
     * @return string
     */
    public function getAttribute($name)
    {
    	$name = strtolower($name);
    	return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
    }

    public function removeAttribute($name)
    {
    	$name = strtolower($name);
    	if (isset($this->attributes[$name])){
    		unset($this->attributes[$name]);
    	}
    }

    public function setAttributes($params)
    {
        $params = $this->filterAttributes($params);
		foreach ($params as $key=>$val){
        	$this->setAttribute($key, $val);
        }
    }

    public function getAttributes()
    {
    	return $this->attributes;
    }

	protected function filterAttributes($params)
	{
		if (array_key_exists('dataSource', $params)){
            $this->setDataSource($params['dataSource']);
            unset($params['dataSource']);
        }

        if (array_key_exists('firstText', $params)){
            $this->setFirstText($params['firstText']);
            unset($params['firstText']);
        }

        if (array_key_exists('firstValue', $params)){
            $this->setFirstValue($params['firstValue']);
            unset($params['firstValue']);
        }

        if (array_key_exists('type', $params)){
            $this->setType($params['type']);
            unset($params['type']);
        }

        if (array_key_exists('disabled', $params)){
            $this->setDisabled($params['disabled']);
            unset($params['disabled']);
        }

        if (array_key_exists('emptyValue', $params)){
            $this->setEmptyValue($params['emptyValue']);
            unset($params['emptyValue']);
        }

        if (array_key_exists('defaultDbValue', $params)){
            $this->setDefaultDbValue($params['defaultDbValue']);
            unset($params['defaultDbValue']);
        }

        if (array_key_exists('dbFieldName', $params)){
            $this->setDbFieldName($params['dbFieldName']);
            unset($params['dbFieldName']);
        }

        if (array_key_exists('initValue', $params)){
            $this->setInitValue($params['initValue']);
            unset($params['initValue']);
        }

        if (array_key_exists('htmlTagEnabled', $params)){
            $this->setHtmlTagEnabled($params['htmlTagEnabled']);
            unset($params['htmlTagEnabled']);
        }

		return $params;
	}

    private function setName($name)
    {
    	$this->name = self::formatHtmlAttrString($name);
    	$names = explode("::", $name);
        $this->attributes['name'] = $names[count($names)-1];
        $names = explode("[", $this->attributes['name']);

        $this->postName = $names[0];
    }

    /**
     * 取得表单字段对象的name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPostName()
    {
        return $this->postName;
    }

    /**
     * 取得表单提交的值
     *
     * @return string
     */
    public function getPostValue()
    {
        $postName = $this->getPostName();

        $value = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if (array_key_exists($postName, $_POST)){
                $value = $this->_checkAndStripTags($_POST[$postName]);
            }
        }
        else if (array_key_exists($postName, $_GET)){
            $value = $this->_checkAndStripTags($_GET[$postName]);;
        }

        $defaultDbValue = $this->getDefaultDbValue();
        if (($value === '' || $value == $this->getEmptyValue()) && $defaultDbValue !== NULL){
            $value = $this->_checkAndStripTags($defaultDbValue);;
        }

        return $value;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = self::formatHtmlAttrString($id);
    	$this->attributes['id'] = $this->id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|array $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    	$this->attributes['value'] = $value;
    }

    /**
     * @return string|array
     */
    public function getValue()
    {
        return $this->_checkAndStripTags($this->value);
    }

    public static function setInitValue($initValue)
    {
    	self::$initValue = $initValue;
    }

    /**
     * @return string|array
     */
    public function getInitValue()
    {
        $postName = $this->getDbFieldName();
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	        if (array_key_exists($postName, $_POST)){
	            $value = $this->_checkAndStripTags($_POST[$postName]);
	        }
        }
        else if (array_key_exists($postName, $_GET)){
            $value = $this->_checkAndStripTags($_GET[$postName]);
        }
        else if (is_array(self::$initValue) && array_key_exists($postName, self::$initValue)){
        	$value = $this->_checkAndStripTags(self::$initValue[$postName]);
        }
        return $value;
    }

    private function _checkAndStripTags($value)
    {
    	if (is_array($value)) {
    		foreach ($value as &$val) {
    			$val = $this->getHtmlTagEnabled() ? $val : strip_tags($val);
    		}
    	} else {
    		$value = $this->getHtmlTagEnabled() ? $value : strip_tags($value);
    	}
        return $value;
    }

	public function setDisabled($bool)
	{
		$this->disabled = $bool ? true : false;
	}

	public function getDisabled()
	{
		return (bool)$this->disabled;
	}

	public function setHtmlTagEnabled($bool)
	{
		$this->htmlTagEnabled = $bool ? true : false;
	}

	public function getHtmlTagEnabled()
	{
		return (bool)$this->htmlTagEnabled;
	}


    /**
     * 设置表示空的值
     *
     * @param string $emptyValue
     */
    public function setEmptyValue($emptyValue)
    {
        $this->emptyValue = self::formatHtmlAttrString($emptyValue);
    }

    /**
     * 获取表示空的值
     *
     * @return string
     */
    public function getEmptyValue()
    {
        return $this->_checkAndStripTags($this->emptyValue);
    }

    /**
     * 设置表示为空的数据表字段的值
     *
     * @param string $defaultDbValue
     */
    public function setDefaultDbValue($defaultDbValue)
    {
        $this->defaultDbValue = self::formatHtmlAttrString($defaultDbValue);
    }

    /**
     * 获取表示为空的数据表字段的值
     *
     * @return string
     */
    public function getDefaultDbValue()
    {
        return $this->_checkAndStripTags($this->defaultDbValue);
    }

    /**
     * 设置是否在网页中显示
     *
     * @param boolen $boolen
     */
    public function setDisplay($boolen)
    {
        $this->display = $boolen;
    }

    /**
     * 获取是否在网页中显示
     *
     * @return boolen
     */
    public function getDisplay()
    {
        return $this->display;
    }

    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function getDataSource()
    {
        return $this->dataSource;
    }

    public function setFirstText($firstText)
    {
        $this->firstText = self::formatHtmlAttrString($firstText);
    }

    public function getFirstText()
    {
        return $this->firstText;
    }

    public function setFirstValue($firstValue)
    {
        $this->firstValue = self::formatHtmlAttrString($firstValue);
    }

    public function getFirstValue()
    {
        return $this->_checkAndStripTags($this->firstValue);
    }

	public function setDbFieldName($dbFieldName)
	{
		$this->dbFieldName = $dbFieldName;
	}

	public function getDbFieldName()
	{
		return !empty($this->dbFieldName) ? $this->dbFieldName : $this->getPostName();
	}

    /**
     * 提交的数据是否为空
     *
     * @return bool
     */
    protected function isEmpty()
    {
        $value = $this->getPostValue();
        $defaultDbValue = $this->getDefaultDbValue();
        if ($value === '' || $value == $this->getEmptyValue() || ($defaultDbValue !== NULL && $value == $defaultDbValue)){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 格式化网页元素属性值字串
     *
     * @param string $msg
     * @return string
     */
    protected static function formatHtmlAttrString($msg)
    {
        if (empty($msg)) return $msg;
    	$msg = str_replace(array('\\', '"', "\r","\n"), '', $msg);
        return $msg;
    }

    protected function renderInput()
    {
        $type       = strtolower($this->getAttribute('type'));
        if (empty($type)){
            $type = 'text';
        }

        $str        = '<input';
        $value      = $this->getValue();
        $initValue  = $this->getInitValue();


        if ($type == 'radio' || $type == 'checkbox'){
            if ($value !== NULL){
                $str .= sprintf(' value="%s"', self::formatHtmlAttrString($value));
                if ($initValue !== NULL){
                    if ((is_array($initValue) && in_array($value, $initValue)) || $value == $initValue){
						$str .= ' checked';
					}
                }
				else if (isset($this->attributes['checked']) && $this->attributes['checked'] == true) {
					$str .= ' checked';
				}
            }
        }
        else {
            if ($initValue !== NULL){
                $value = $initValue;
            }
            if ($value !== NULL){
                $str .= sprintf(' value="%s"', self::formatHtmlAttrString((string)$value));
            }
        }

		if ($this->getDisabled()) {
			$str .= ' disabled="disabled"';
		}

		$attributes = $this->getAttributes();
        unset($attributes['value'], $attributes['disabled']);
		if (isset($attributes['checked'])) {
			unset($attributes['checked']);
		}
        foreach ($attributes as $name => $value) {
            $str .= sprintf(' %s="%s"', $name, $value);
        }

        $str .= " />\n";
        return $str;
    }

    protected function renderSelect()
    {
        $dataSource = $this->getDataSource();
		$firstText  = $this->getFirstText();
        if (!is_array($dataSource) || (count($dataSource) == 0 && empty($firstText))){
            return "";
        }

        if (($value = $this->getInitValue()) === NULL){
            $value  = $this->getValue();
        }

        $str    = '<select';

		if ($this->getDisabled()) {
			$str .= ' disabled="disabled"';
		}

        $attributes = $this->getAttributes();
        unset($attributes['value'], $attributes['disabled']);
		if (isset($attributes['selected'])) {
			unset($attributes['selected']);
		}
        foreach ($attributes as $attrName => $attrValue) {
            $str .= sprintf(' %s="%s"', $attrName, $attrValue);
        }
        $str .= ">\n";

        $firstText  = $this->getFirstText();
        if (!empty($firstText)){
            $firstValue = $this->getFirstValue();
            $str .= sprintf('<option value="%s">%s</option>'."\n", $firstValue, $firstText);
        }

        foreach ($dataSource as $val => $text)
        {
            if (is_array($text)){
                $val  = $text['value'] ? $text['value'] : $text[0];
                $text = $text['text']  ? $text['text']  : $text[1];
            }

            $selected = '';
			if ($value !== NULL){
				if ((is_array($value) && in_array($val, $value)) || $value == $val){
					$selected = ' selected';
				}
			}
			else if (isset($this->attributes['selected']) && $this->attributes['selected'] == true) {
				$selected = ' selected';
			}

            $str .= sprintf('<option value="%s"%s>%s</option>'."\n", $val, $selected, $text);
        }

        $str .= "</select>\n";
        return $str;
    }

    protected function renderHoverSelect() {
        $dataSource = $this->getDataSource();
		$firstText  = $this->getFirstText();
        if (!is_array($dataSource) || (count($dataSource) == 0 && empty($firstText))){
            return "";
        }

        if (($value = $this->getInitValue()) === NULL){
            $value  = $this->getValue();
        }
        $attributes = $this->getAttributes();
        $firstText  = $this->getFirstText();
        if (!empty($firstText)){
            $firstValue = $this->getFirstValue();
        }
        if (!empty($value)) {
            $firstValue = $value;
            $firstText = $dataSource[$value];
        }
        $str = sprintf("<div class=\"%s\">\n", $attributes['class']);
        $str .= sprintf("<input type=\"text\" name=\"_unused\" value=\"%s\"/>\n", $firstText);
        $str .= sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\"/>\n", $attributes['name'], $firstValue);
        $str .= "<div>\n";

        foreach ($dataSource as $val => $text)
        {
            if (is_array($text)){
                $val  = $text['value'] ? $text['value'] : $text[0];
                $text = $text['text']  ? $text['text']  : $text[1];
            }

            $str .= sprintf("<a data=\"%s\" href=\"javascript: void(0);\">%s</a>\n", $val, $text);
        }

        $str .= "</div>\n";
        $str .= "</div>\n";
        return $str;
    }

    protected function renderTextarea()
    {
        $str    = '<textarea';

		if ($this->getDisabled()) {
			$str .= ' disabled="disabled"';
		}

        $attributes = $this->getAttributes();
        unset($attributes['value'], $attributes['disabled']);
        foreach ($attributes as $name => $value) {
            $str .= sprintf(' %s="%s"', $name, $value);
        }
        $str .= ">";

        if (($value = $this->getInitValue()) === NULL){
        	$value  = $this->getValue();
        }

        if ($value !== NULL){
            $str .= (string)$value;
        }

        $str .= "</textarea>\n";
        return $str;
    }

    protected function renderCheckList()
    {
        $dataSource = $this->getDataSource();
        if (!is_array($dataSource) || count($dataSource) == 0){
            return "";
        }

        if (($value = $this->getInitValue()) === NULL){
            $value  = $this->getValue();
        }

        $id         = $this->getId();
        if (empty($id)){
            $id     = $this->getName();
        }

        $attributes = $this->getAttributes();
        unset($attributes['id'], $attributes['value'], $attributes['disabled']);

        $i = 0;
        foreach ($dataSource as $val => $text)
        {
            if (is_array($val)){
                $val  = $text['value'] ? $text['value'] : $text[0];
                $text = $text['text']  ? $text['text']  : $text[1];
            }

            $checked = ($value !== NULL && ((is_array($value) && in_array($val, $value)) || $value == $val)) ? ' checked' : '';

            $str .= sprintf('<label for="%s"><input id="%s"', $id.'_'.$i, $id.'_'.$i);
            if ($this->getDisabled()) {
                $str .= ' disabled="disabled"';
            }
            foreach ($attributes as $attrName => $attrValue) {
                $str .= sprintf(' %s="%s"', $attrName, $attrValue);
            }
            $str .= sprintf(' value="%s"%s>%s</label>'."\n", $val, $checked, $text);

            $i++;
        }
        return $str;
    }

    protected function renderNewSelect() {
        require_once CODE_BASE2 . '/util/form/field/FieldRenderNamespace.class.php';
        $html = FieldRenderNamespace::getHtml($this, 'new_select');

        return $html;
    }

}

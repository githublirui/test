<?php

/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /ganji_v5/app/common/BasePage.class.php
 * @author        longweiguo@ganji.com
 * @date          2011-08-20
 *
 * 页面基类
 */

abstract class BasePage {

	/// 需要显示或传递给模板的值
	public static $viewData = array();

    public $customVars = array();

	/// 构造器
	final public function __construct($customVars = array()) { 
        $this->customVars = $customVars;
    }

	/**
	 * 初始化，在入口自动调用，方便子类初始设置
	 */
	public function init() { }

	/**
	 * 设置需要输出的值
	 *
	 * @param string|array $key
	 * @param mix $val
	 */
	public function setViewData($key, $val = null) {
		if (!is_array(self::$viewData)) {
			self::$viewData = (array)self::$viewData;
		}
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				self::$viewData[$k] = $v;
			}
		} else if (!empty($key)) {
			self::$viewData[$key] = $val;
		}
	}

	/**
	 * 输出页面，在ajax模式下输出json_encode后的字串，否则通过模板引擎输出
	 *
	 * @param array viewData 要输出的值
	 * @param string template 模板名
	 */
    protected function render($tpl='') {
        if (!empty($tpl)) {
            $content = ResponseNamespace::fetch($tpl, self::$viewData);
            ResponseNamespace::output($content);
        } else {
            ResponseNamespace::output(self::$viewData);
        }
	}
	
}

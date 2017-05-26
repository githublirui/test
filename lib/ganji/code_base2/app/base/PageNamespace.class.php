<?php

/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /ganji_v5/app/common/PageBase.class.php
 * @author        longweiguo@ganji.com
 * @date          2011-08-20
 *
 * 页面基类
 */

require_once dirname(__FILE__) . '/page_config.inc.php';
require_once CODE_BASE2 . '/util/http/HttpNamespace.class.php';


abstract class PageNamespace {

    /// 错误号
    public static $errorNo;

    /// 错语信息
    public static $errorMsg;

	/// 需要显示或传递给模板的值
	public static $viewData = array();

    /// 要跳转的url
    public static $redirectUrl;

	/// 构造器
	public function __construct() { }

	/**
	 * 初始化，在入口自动调用，方便子类初始设置
	 */
	public function init() { }

    public static function setPageConfig($key, $val) {
        $GLOBALS['PAGE_CONFIG'][$key] = $val;
    }

    public static function getPageConfig($key, $default=null) {
        if (array_key_exists($GLOBALS['PAGE_CONFIG'], $key)) {
            return $GLOBALS['PAGE_CONFIG'][$key];
        }
        return $default;
    }

    public static function setError($errorMsg, $errorNo=NULL) {
        self::$errorMsg = $errorMsg;
        self::$errorNo = $errorNo;
    }

    public static function hasError() {
        return !empty(self::$errorNo) || !empty(self::$errorMsg);
    }

    public static function setRedirectUrl($url) {
        self::$redirectUrl = $url;
    }

	/**
	 * 设置需要输出的值
	 *
	 * @param string|array $key
	 * @param mix $val
	 */
	public static function setViewData($key, $val) {
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

    public static function redirect($redirectUrl = '') {
        if (!empty($redirectUrl)) {
            self::$redirectUrl = $redirectUrl;
        }
        if (!empty(self::$redirectUrl)) {
            HttpNamespace::redirect(self::$redirectUrl);
        }
    }

	/**
	 * 输出页面，在ajax模式下输出json_encode后的字串，否则通过模板引擎输出
	 *
	 * @param array viewData 要输出的值
	 * @param string template 模板名
	 */
	public static function render($content='') {
        if (!empty($GLOBALS['PAGE_CONFIG']['tpl_file'])) {
            $tpl = $GLOBALS['PAGE_CONFIG']['tpl_file'];
        }

        if (self::hasError()) {
            self::setViewData('errorNo', self::$errorNo);
            self::setViewData('errorMsg', self::$errorMsg);

            if (self::$errorNo == 404) {
                if (!empty($GLOBALS['PAGE_CONFIG']['tpl_file_404']) && $GLOBALS['PAGE_CONFIG']['content_type'] == 'html') {
                    $tpl = $GLOBALS['PAGE_CONFIG']['tpl_file_404'];
                } else {
                    $tpl = '';
                }
            }
        }

        if (empty($content) && !empty($tpl)) {            
            include_once dirname(__FILE__) . '/../../util/tpl/TplNamespace.class.php';
            $tplEngine = TplNamespace::createTpl($GLOBALS['PAGE_CONFIG']['tpl_type'], $GLOBALS['PAGE_CONFIG']['tpl_config']);
            $content = $tplEngine->fetch($tpl, self::$viewData);
        }

        return $content;
	}

    public static function output($content='') {
        include_once dirname(__FILE__) . '/ResponseNamespace.class.php';
        $res = new ResponseNamespace();
		$res->output($content, self::$viewData, $GLOBALS['PAGE_CONFIG']['content_type'], $GLOBALS['PAGE_CONFIG']['jsonp_callback']);
    }
	
}

<?php


class ResponseNamespace {

    const CONTENT_TYPE_HTML = 'html';
    const CONTENT_TYPE_TEXT = 'txt';
    const CONTENT_TYPE_CSS = 'css';
    const CONTENT_TYPE_JS = 'js';
    const CONTENT_TYPE_JSON = 'json';
    const CONTENT_TYPE_JSONP = 'jsonp';
    const CONTENT_TYPE_XML = 'xml';
    const CONTENT_TYPE_RSS = 'rss';
    const CONTENT_TYPE_GZ = 'gz';
    const CONTENT_TYPE_TAR = 'tar';
    const CONTENT_TYPE_ZIP = 'zip';
    const CONTENT_TYPE_GIF = 'gif';
    const CONTENT_TYPE_PNG = 'png';
    const CONTENT_TYPE_JPG = 'jpg';
    const CONTENT_TYPE_JPEG = 'jpeg';
    
    public static $HEADERS      = array();
    public static $CHARSET      = 'UTF-8';
    public static $CONTENT_TYPE = self::CONTENT_TYPE_HTML;
    public static $JSONP_CALLBACK;
    public static $URL_404     = '';
    public static $MESSAGE_404 = 'Not Found!';
    public static $TPL_TYPE    = 'default';
    public static $TPL_CONFIG;
    public static $RETURN_KEY_ERROR_NO   = 'errorNo';
    public static $RETURN_KEY_ERROR_MSG  = 'errorMsg';
    public static $CONTENT_TYPE_NAME_QUERY = 'contentType';
    public static $JSONP_CALLBACK_NAME_QUERY = 'callback';
    protected static $TPL_ENGINE;
    
    private static $mimeTypes = array(
        self::CONTENT_TYPE_HTML => 'text/html',
        self::CONTENT_TYPE_TEXT => 'text/plain',
        self::CONTENT_TYPE_CSS => 'text/css',
        self::CONTENT_TYPE_JS => 'application/javascript',
        self::CONTENT_TYPE_JSON => 'text/javascript',
        self::CONTENT_TYPE_JSONP => 'application/javascript',
        self::CONTENT_TYPE_XML => 'text/xml',
        self::CONTENT_TYPE_RSS => 'application/rss+xml',
        self::CONTENT_TYPE_GZ => 'application/x-gzip',
        self::CONTENT_TYPE_TAR => 'application/x-tar',
        self::CONTENT_TYPE_ZIP => 'application/zip',
        self::CONTENT_TYPE_GIF => 'image/gif',
        self::CONTENT_TYPE_PNG => 'image/png',
        self::CONTENT_TYPE_JPG => 'image/jpeg',
        self::CONTENT_TYPE_JPEG => 'image/jpeg',
    );

    public static function setContentType($contentType, $jsonpCallback='') {
    	if ($contentType == self::CONTENT_TYPE_JSONP && empty($jsonpCallback)) {
    		$contentType = self::CONTENT_TYPE_JSON;
    	}
        self::$CONTENT_TYPE = $contentType;
        self::$JSONP_CALLBACK = $jsonpCallback;
        if (array_key_exists(self::$CONTENT_TYPE, self::$mimeTypes)) {
        	self::$HEADERS['Content-Type'] = self::$mimeTypes[self::$CONTENT_TYPE] . '; charset=' . self::$CHARSET;
        }
    }

    public static function isJsonContentType() {
        return self::$CONTENT_TYPE == self::CONTENT_TYPE_JSON || self::$CONTENT_TYPE == self::CONTENT_TYPE_JSONP;
    }

    public static function output($content, $contentType='', $jsonpCallback='') {
        if (!empty($contentType)) {
            self::setContentType($contentType, $jsonpCallback);
        }

        foreach (self::$HEADERS as $key => $value) {
            header($key . ": " . $value);
        }
        
    	switch (self::$CONTENT_TYPE) {
            case self::CONTENT_TYPE_HTML:
            case self::CONTENT_TYPE_JS:
                echo $content;
                break;
            case self::CONTENT_TYPE_JSON:
                echo json_encode($content);
                break;
            case self::CONTENT_TYPE_JSONP:
                echo self::$JSONP_CALLBACK . '(' . json_encode($content) . ');';
                break;
            default:
            	echo $content;
            	break;
        }
    }

    public static function fetch($tpl, $data=array()) {
        if (!self::$TPL_ENGINE) {
            if (empty(self::$TPL_TYPE) || empty(self::$TPL_CONFIG)) {
                throw new Exception('未指定模板类型或模板配置变量 ');
            }
            include_once dirname(__FILE__) . '/../tpl/TplNamespace.class.php';
            self::$TPL_ENGINE = TplNamespace::createTpl(self::$TPL_TYPE, self::$TPL_CONFIG);
        }
        return self::$TPL_ENGINE->fetch($tpl, $data);
    }

    public static function display404() {
        if (self::isJsonContentType()) {
            self::output(array(
                self::$RETURN_KEY_ERROR_NO  => 404,
                self::$RETURN_KEY_ERROR_MSG => self::$MESSAGE_404,
            ));
        } else if (!empty(self::$URL_404)) { 
            header ("HTTP/1.1 302 Moved Temporarily");
            header ('Location: ' . self::$URL_404);
        } else {
            self::output(self::$MESSAGE_404);
        }
        exit ();
    }

}
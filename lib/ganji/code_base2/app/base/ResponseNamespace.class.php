<?php

/**
 * PageResponse.class.php
 * 管理HTTP Response的类
 *
 * @author yyquick
 */
class ResponseNamespace {

    public static $headers = array();
    public static $charset = 'UTF-8';
    public static $content = "";
    public static $data = array();
    public static $contentType;
    public static $jsonpCallback;
    
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
    	if ($contentType == 'jsonp' && empty($jsonpCallback)) {
    		$contentType = 'json';
    	}
        self::$contentType = $contentType;
        self::$jsonpCallback = $jsonpCallback;
        if (array_key_exists($contentType, self::$mimeTypes)) {
        	self::$headers['Content-Type'] = self::$mimeTypes[$contentType] . '; charset=' . self::$charset;
        }
    }

    public static function setContent($content) {
    	self::$content = $content;
    }

    public static function setData($data) {
    	self::$data = $data;
    }

    public static function output() {
        foreach (self::$headers as $key => $value) {
            header($key . ": " . $value);
        }
        
    	switch (self::$contentType) {
            case 'html':
            case 'js':
                echo self::$content;
                break;
            case 'json':
                echo json_encode(self::$data);
                break;
            case 'jsonp':
                echo self::$jsonpCallback . '(' . json_encode(self::$data) . ');';
                break;
            default:
            	echo self::$content;
            	break;
        }
    }

}
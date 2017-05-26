<?php

/**
 * HttpResponse.class.php
 * 管理HTTP Response的类
 *
 * @author yyquick
 */
class HttpResponse {

    public $http_ver = "HTTP/1.1";
    public $status_code = 200;
    public $headers = array();
    public $content = "";
    public $type;
    public $mimetypes = array(
        'html' => 'text/html',
        'txt' => 'text/plain',
        'php' => 'application/php',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'jsonp' => 'text/html',
        'xml' => 'text/xml',
        'rss' => 'application/rss+xml',
        'atom' => 'application/atom+xml',
        'gz' => 'application/x-gzip',
        'tar' => 'application/x-tar',
        'zip' => 'application/zip',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        'avi' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mov' => 'video/quicktime',
        'mp3' => 'audio/mpeg'
    );
    public $messages = array(
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error'
    );

    public function setContent($object) {
        switch ($this->type) {
            case 'html':
            case 'txt':
            case 'php':
            case 'css':
            case 'js':
                $this->content = $object;
                break;
            case 'json':
                $this->content = json_encode($object);
                break;
            case 'jsonp':
                $this->content = $_REQUEST['func'] . '(' . json_encode($object) . ')';
                break;
        }

        $this->headers['Content-Length'] = strlen($this->content);
    }

    public function setContentType($type) {
        $this->type = $type;
        $this->headers['Content-Type'] = $this->mimetypes[$type];
    }

    public function output() {
        header($this->http_ver . " " . $this->status_code . " " . $this->messages[$this->status_code]);
        foreach ($this->headers as $key => $value) {
            header($key . ": " . $value);
        }
        echo $this->content;
    }

}
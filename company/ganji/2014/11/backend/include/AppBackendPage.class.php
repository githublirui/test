<?php

require_once CODE_BASE2 . '/util/http/HttpNamespace.class.php';
require_once dirname(__FILE__) . '/../config/BaseConf.class.php';

class AppBackendPage extends BackendPage {

    public function __construct() {
        $this->includeFiles();
        $this->preExecute();
        parent::__construct();
    }

    public function getParameter($key, $default = false, $enableHtml = false) {
        return HttpNamespace::getREQUEST($key, $default, $enableHtml);
    }

    public function getGetParameter($key, $default = false, $enableHtml = false) {
        return HttpNamespace::getGET($key, $default, $enableHtml);
    }

    public function getPostParameter($key, $default = false, $enableHtml = false) {
        return HttpNamespace::getPOST($key, $default, $enableHtml);
    }

    //解析url，读取当前url 并绑定返回
    public function getQueryString() {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $queryString = $url['query'];
        parse_str($queryString, $params);
        return http_build_query($params);
    }

    public function preExecute() {
        
    }

    public function includeFiles() {
        
    }

}

?>

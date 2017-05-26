<?php

header('content-type:text/html;charset=utf-8;');
include 'http.php';
// 微信公众账号 
$user = "zimumdm@163.com";
// 微信公众号登陆密码
$pass = md5("mdm516516");

$obj = new test($user, $pass);
//$obj->sendTextMsg("43431965", '你好');
$s = $obj->getFeckId();
var_dump($s);

class test {

    private $http; //请求实例
    private $cookieLog = './temp/cookie.log'; //cookie的保存文件
    private $tokenLog = './temp/token.log'; //token的保存文件
    private $user; //用户名
    private $pass; //密码

    /**
     * 构造函数
     * @param string $user  用户名
     * @param string $pass  密码
     */

    public function __construct($user, $pass) {
        $this->http = http::getInstance();
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * 模拟登陆
     */
    public function login() {
        $url = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
        $post = array();
        $post["username"] = $this->user;
        $post["pwd"] = $this->pass;
        $post["imgcode"] = '';
        $post["f"] = "json";
        $this->http->sendRequest($url, $post);
        $data = json_decode($this->http->getResultData(), TRUE);
        if (!(isset($data['ErrCode']) && $data['ErrCode'] == 0)) {
            echo "登陆失败";
        } else {
            echo "成功";
        }

        $arr = parse_url($data['ErrMsg']);
        $qarr = explode('&', $arr['query']);
        $token = '';
        foreach ($qarr as $k => $v) {
            $karr = explode("=", $v);
            if ($karr[0] == 'token') {
                $token = $karr[1];
            }
        }
        $this->write($this->tokenLog, $token);
        $cookie = '';
        if (preg_match_all("/set\-cookie: (.*) path/i", $this->http->getResultHeader(), $matches)) {
            if (isset($matches[1])) {
                foreach ($matches[1] as $k => $v) {
                    $cookie .= $v;
                }
            }
        }
        $this->write($this->cookieLog, $cookie);
        //$this->write($this->cookieLog, $contents);
    }

    /**
     * 指定好友发送消息
     * @param int $fakeId   fakeid
     * @param string $content   消息内容
     */
    public function sendTextMsg($fakeId, $content) {
        $this->checkLogin();

        $cookie = $this->read($this->cookieLog);
        $token = $this->read($this->tokenLog);
        $header = array();
        $header['Referer'] = "https://mp.weixin.qq.com/cgi-bin/singlemsgpage?token={$token}&fromfakeid={$fakeId}&msgid=&source=&count=20&t=wxm-singlechat&lang=zh_CN";
        $header['Cookie'] = $cookie;
        $this->http->setHeader($header);
        $post = array();
        $post['token'] = $token;
        $post['tofakeid'] = $fakeId;
        $post['type'] = 1;
        $post['content'] = $content;
        $post['ajax'] = 1;
        $post['error'] = 'false';
        $url = "https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response&lang=zh_CN";
        $this->http->sendRequest($url, $post);
        $data = json_decode($this->http->getResultData(), TRUE);
        if (isset($data['ret']) && $data['ret'] == 0) {
            echo '发送成功';
        } else {
            echo '发送失败';
        }
    }

    /**
     * 写文件
     * @param string $filename  文件名
     * @param string $content   内容
     */
    private function write($filename, $content) {
        $fp = fopen($filename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * 读取文件内容
     * @param string $filename
     * @return string 
     */
    private function read($filename) {
        $data = '';
        if (file_exists($filename)) {
            $fp = fopen($filename, 'r');
            $data = fread($fp, filesize($filename));
            fclose($fp);
        }
        return $data;
    }

    /**
     * 校验登陆
     * @return boolean true 
     */
    private function checkLogin() {
        $cookie = $this->read($this->cookieLog);
        $token = $this->read($this->tokenLog);
        $post = array();
        $post['token'] = $token;
        $post['ajax'] = 1;
        $url = 'https://mp.weixin.qq.com/cgi-bin/getnewmsgnum?t=ajax-getmsgnum&lastmsgid=100002402';
        $header['Referer'] = "https://mp.weixin.qq.com/cgi-bin/getmessage?t=wxm-message&token={$token}&lang=zh_CN&count=50"; //伪装来源页地址 http_referer   
        $header['Cookie'] = $cookie;
        $this->http->setHeader($header);
        $this->http->sendRequest($url, $post);
        $data = json_decode($this->http->getResultData(), TRUE);
        if (!(isset($data['ret']) && $data['ret'] == 0)) {
            $this->login();
        }
        return TRUE;
    }

    /**
     * 获取fake id
     */
    public function getFeckId() {
        $this->checkLogin();
        $url = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=0&token=1799199106&lang=zh_CN";
        $post = array();
        $this->http->sendRequest($url, $post, 'get');
        $data = $this->http->getResultData();
        $preg = "/friendsList\s*:\s*\(\{\s*\"contacts\"\:\s*\[\{\"id\"\:\s*(\d+)/is";
        preg_match_all($preg, $data, $match);
        return $match[1][0];
    }

}

?>
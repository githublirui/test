<?php
require_once dirname(__FILE__) . './test_data.php';

class Test {
    
    private static $_URL = '';

    private static $_REQUEST_PARAMS = array();
    
    public function init() {
        self::$_URL = $this->getUrl();
        self::$_REQUEST_PARAMS['header'] = $this->getHeaders();
        self::$_REQUEST_PARAMS['jsonArgs'] = $this->getjsonArgs();
        self::$_REQUEST_PARAMS['body'] = $this->getBody();
    }
    
    /**
     * 获取访问url
     */
    public function getUrl() {
        $url = self::_getArg('URL');
        if (empty($url)) {
            echo 'The URL is not set.';exit;
        }
        $path = InterfacePara::getInterfaceBelongHost(self::_getArg('interface'));
        $url = $url . $path;
        return $url;
    }
    /**
     * 请求头部初始化
     */
    public function getHeaders() {
        $headBase = array(
            'interface:' . self::_getArg('interface'),

            'agency:appstore',
            'clientAgent:iphone#320*480',
            'clientTest:true',
            'contentformat:json2',
            'customerId:' . self::_getArg('customerId'),
            'model:Generic/iphone',
            'userId:649B0B50A0C8D5ABF8A895D9BCDA343B',
            'versionId:' . self::_getArg('versionId'),
            'token:' . self::_getArg('token'),
        );
        return $headBase;
    }
    public function getjsonArgs() {
        $jsonArgs = array(
            'categoryId'=>'2',
            'cityScriptIndex'=>'0',
            'majorCategoryScriptIndex'=>'-2',
            'code' => 4435834,
        );
        return $jsonArgs;
    }
    /**
     * 处理post 主题参数
     */
    public function getBody() {
        $params = self::_getArg('postParam');
        foreach ($params as $item) {
            $postFileds[$item['k']] = $item['v'];
        }
        return $postFileds;
    }

	public function run() {
        $start = self::_t();

		$this->init();
		$result = $this->_executePost();

        $spendTime = (self::_t() - $start) * 1000;
        echo '执行时间: ', $spendTime , ' ms<br />------------------------------<br /><br />';
        $jsonResult = json_decode($result, true);
        if (is_array($jsonResult)) {
            $result = $jsonResult;
            if (self::_getArg('showType') == 'checked') {
                var_dump($result);
            } else {
                print_r($result);
            }
        } else {
            print_r($result);
        }
	}
	
    /**
     * 执行远程访问并返回接口
     */
	private function _executePost() {
        $showHeader = 0;
        if (self::_getArg('showHeader') == 'checked') {
            $showHeader = 1;
        }
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HEADER, $showHeader);
        curl_setopt($ch, CURLOPT_URL, self::$_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$_REQUEST_PARAMS['header']);
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, self::$_REQUEST_PARAMS['body']) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private static function _getArg($name) {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
    }

    private static function _t() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

$test = new Test();
$test->run();
exit();

/*
$URL = $_POST['URL'];

$interface = 'interface:' . $_POST['interface'];
$header = array(
    $interface,

    'contentformat:json2',
    'agency:appstore',
    'customerId:700',
    'clientAgent:iphone#640*480',
    'versionId:2.8.0',
    'GjData-Version:1.0',
    'model:Generic/iPhone',
    'userId:A00A52AB1C820CCADCA7EC4982CB290F',
    'deviceId:e2e7a3083b9e852ad53455a934b4a07d6993b678d679a3172d2cea62b493d913',
);

$postFieldsKeys = array(
    //UserPhoneAuth
    'method',
    'userId',
    'phone',

    //GetWebSearchCount
    'cityScriptIndex',
    'keywords',
    'categoryId',

    //VotePost
    'categoryId',
    'majorScriptIndex',
    'postId',
    'agent',
    'cityScriptIndex',
    'reasonId',
    'content',
);
foreach ($postFieldsKeys as $paraKey) {
    # code...
    if (isset($_POST[$paraKey])) {
        $postFields[$paraKey] = $_POST[$paraKey];
    }
}

var_dump($postFields);exit;
*/

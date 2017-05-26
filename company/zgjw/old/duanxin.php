<?php

class SendCard {
    /* private static $url = 'http://60.28.195.138/submitdata/service.asmx/g_Submit';
      private static $sname = 'dlqdcs02';
      private static $spwd = 'SjkQ35RS'; */

//		private static $url = 'http://chufa.lmobile.cn/submitdata/service.asmx';
    private static $url = 'http://chufa.lmobile.cn/submitdata/service.asmx/g_Submit';
    private static $sname = 'dlqdcs02';
    private static $spwd = 'qwAEIKMO';
    private static $sprdid = '1012818';      //产品代码
    private static $scorpid = '';            //企业代码

    /**
     * 发送短消息
     *
     * @param string $sdst
     *        	目标地址
     * @param string $smsg
     *        	消息内容
     * @param string $stime
     *        	要求发送时间
     * @param string $sender
     *        	发送人号码
     * @return bool
     */

    public function send_mms($sdst, $smsg, $stime = '') {
        $params = array(
            // 提交用户
            'sname' => self::$sname,
            // 提交密码
            'spwd' => self::$spwd,
            // 企业代码
            'scorpid' => self::$scorpid,
            // 产品编号
            'sprdid' => self::$sprdid,
            // 接收号码，多个以','分割,不可超过10000个号码
            'sdst' => $sdst,
            // 短信内容
            'smsg' => $smsg
        );

        $put = $this->_puts_header(self::$url, $params);
        $xmlObj = simplexml_load_string($put);

        if (empty($xmlObj)) {
            $res = array('errno' => 999, 'msg' => '发送后没有返回结果'
            );
        }

        if (intval($xmlObj->State)) {
            $res = array('errno' => intval($xmlObj->State), 'msg' => $xmlObj->MsgState
            );
        }

        $res = array('errno' => 0, 'msg' => '成功发送'
        );

//			$log = date('Y-m-d H:i:s') . ' ERROR: ' . $res['errno'] . '	' . iconv('utf-8', 'gbk', $res['msg']) . "\n";

        return $xmlObj->State;
    }

    /**
     * 通过crul功能在服务器上直接发送请求
     *
     * @param string $target
     *        	要访问的URL
     * @param string $msg
     *        	要发送的参数
     * @return string
     */
    private function _puts_header($target, $msg) {
        $params = array();
        foreach ($msg as $k => $m) {
            $params[] = $k . '=' . $m;
        }

        $query = join('&', $params);
        $target .= '?' . iconv('gbk', 'utf-8', $query);

        $cu = curl_init();
        curl_setopt($cu, CURLOPT_HEADER, 0);
        curl_setopt($cu, CURLOPT_VERBOSE, 0);
        curl_setopt($cu, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($cu, CURLOPT_URL, $target);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($cu);
        curl_close($cu);

        return $ret;
    }

}

$obj = new SendCard();
$ret_code = intval($obj->send_mms('13083056971', '中国家网测试'));

var_dump($ret_code);
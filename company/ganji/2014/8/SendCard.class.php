
<?php

// ����Ϣ������
class SendCard {
    /* private static $url = 'http://60.28.195.138/submitdata/service.asmx/g_Submit';
      private static $sname = 'dlqdcs02';
      private static $spwd = 'SjkQ35RS'; */

//		private static $url = 'http://chufa.lmobile.cn/submitdata/service.asmx';
    private static $url = 'http://chufa.lmobile.cn/submitdata/service.asmx/g_Submit';
    private static $sname = 'dlbjycsm';       //��ȷ�˻�
    private static $spwd = 'e2Mgn6VA';
    
//    private static $sname = 'dlbjycsmaa';
//    private static $spwd = 'e2Mgn6VAaa';
    private static $sprdid = '1012818';      //��Ʒ����
    private static $scorpid = '';            //��ҵ����

    /**
     * ���Ͷ���Ϣ
     *
     * @param string $sdst
     *        	Ŀ���ַ
     * @param string $smsg
     *        	��Ϣ����
     * @param string $stime
     *        	Ҫ����ʱ��
     * @param string $sender
     *        	�����˺���
     * @return bool
     */

    public function send_mms($sdst, $smsg, $stime = '') {
        $params = array(
// �ύ�û�
            'sname' => self::$sname,
// �ύ����
            'spwd' => self::$spwd,
// ��ҵ����
            'scorpid' => self::$scorpid,
// ��Ʒ���
            'sprdid' => self::$sprdid,
// ���պ��룬�����','�ָ�,���ɳ���10000������
            'sdst' => $sdst,
// ��������
            'smsg' => $smsg
        );

        $put = $this->_puts_header(self::$url, $params);
        $xmlObj = simplexml_load_string($put);

        if (empty($xmlObj)) {
            $res = array('errno' => 999, 'msg' => '���ͺ�û�з��ؽ��'
            );
        }

        if (intval($xmlObj->State)) {
            $res = array('errno' => intval($xmlObj->State), 'msg' => $xmlObj->MsgState
            );
        }

        $res = array('errno' => 0, 'msg' => '�ɹ�����'
        );

//			$log = date('Y-m-d H:i:s') . ' ERROR: ' . $res['errno'] . '	' . iconv('utf-8', 'gbk', $res['msg']) . "\n";

        return $xmlObj->State;
    }

    /**
     * ͨ��crul�����ڷ�������ֱ�ӷ�������
     *
     * @param string $target
     *        	Ҫ���ʵ�URL
     * @param string $msg
     *        	Ҫ���͵Ĳ���
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

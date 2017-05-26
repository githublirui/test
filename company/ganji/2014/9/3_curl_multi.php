<?php

/* curl多线程打开类
 * curl_multi 2012年3月15日 21:39:32做了一小修改..在非200 301 302的情况下返回空!
 * by wc1217
 */

class curl_multi {

    //Curl句柄
//private $curl_handle = null;
//网址
    private $url_list = array();
    //参数
    private $curl_setopt = array(
        'CURLOPT_RETURNTRANSFER' => 1, //结果返回给变量
        'CURLOPT_HEADER' => 0, //要HTTP头不?
        'CURLOPT_NOBODY' => 0, //不要内容?
        'CURLOPT_FOLLOWLOCATION' => 0, //不自动跟踪
        'CURLOPT_TIMEOUT' => 15//超时(s)
    );

    function __construct($seconds = 30) {
        set_time_limit($seconds);
    }

    /*
     * 设置网址
     * @list 数组
     */

    public function setUrlList($list = array()) {
        $this->url_list = $list;
    }

    /*
     * 设置参数
     * @cutPot array
     */

    public function setOpt($cutPot) {
        $this->curl_setopt = $cutPot + $this->curl_setopt;
    }

    /*
     * 执行
     * @return array
     */

    public function exec() {
        try {
            $mh = curl_multi_init();
            $conn = array();
            foreach ($this->url_list as $i => $url) {
                $conn[$i] = curl_init($url);
                if (strtolower(substr($url, 0, 5)) == 'https') {
                    curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false);
                }
                foreach ($this->curl_setopt as $key => $val) {
                    curl_setopt($conn[$i], preg_replace('/(CURLOPT_\w{1,})/ie', '$0', $key), $val);
                }
                curl_multi_add_handle($mh, $conn[$i]);
            }

            /* if($i == 0 && isset($conn[$i]))
              return array(curl_exec($conn[$i])); //一个网址的时候直接执行返回
             */
            $active = false;

            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            //执行状态
//$http_code = array();
            $res = array();

            while ($active and $mrc == CURLM_OK) {
                if (curl_multi_select($mh) != -1) {//php5.3.18+的某些版本会一直返回-1,请去掉此处的if
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                    //得到线程信息
                    /* $mhinfo = curl_multi_info_read($mh);
                      if(false !== $mhinfo){
                      $chinfo = curl_getinfo($mhinfo['handle']);
                      //$http_code[] = $chinfo['url'] . ' : ' . $chinfo['http_code']; // == 0 || $chinfo['http_code'] == 404;
                      //得到httpCode为200的内容 如果CURLOPT_FOLLOWLOCATION且有Location头标记的话$chinfo['url']会是最后一个URL
                      $res[array_search($chinfo['url'], $this->url_list)] = ($chinfo['http_code'] == 200 ? curl_multi_getcontent($mhinfo['handle']) : 'null');
                      curl_multi_remove_handle($mh, $mhinfo['handle']);   //用完马上释放资源
                      curl_close($mhinfo['handle']);
                      } */
                }
            }
            //print_r($http_code);
            foreach ($this->url_list as $i => $url) {
                $status = curl_getinfo($conn[$i], CURLINFO_HTTP_CODE);
                $res[$i] = (($status == 200 || $status == 302 || $status == 301) ? curl_multi_getcontent($conn[$i]) : null);
                curl_close($conn[$i]);
                curl_multi_remove_handle($mh, $conn[$i]);   //用完马上释放资源  
            }
            curl_multi_close($mh);
        } catch (Exception $e) {
            echo '错误: ' . $e->getMessage();
        }
        return $res;
    }

}

?>
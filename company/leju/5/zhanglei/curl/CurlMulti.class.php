<?php
/**
 * @description 多线程curl采集
 * @date 2014-09-11 14:50
 * @author zhanglei <zhanglei17@leju.com>
 */
class CurlMulti{

    private static $class = null;
    
    private $_observers = null;
    
    private $_method = null;

    /**
     * @param type $object 对象
     * @throws Exception
     */
    private function __construct($array = array()){
        if(!is_array($array)){
            throw new Exception('Params Type Is Not Array');
        }
        // 注册观察者
        $this->_observers = $array[0];
        $this->_method = $array[1];
    }
    
    /**
     * @descript 单线程采集
     * @param type $url 需要采集的url地址
     * @return type data 返回采集的源代码
     */
    public function curl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    /**
     * @param $urls 所有的url, 数组, curl多线程采集
     * @return type 所有url的内容
     */
    public function curlmulti($urls){
        if(count($urls) > 20){
            throw new Exception('The Curl Multi Thread is more than the limit');
        }
        $mh = curl_multi_init();
        foreach($urls as $key => $url){
            $conn[$key] = curl_init($url);
            curl_setopt($conn[$key], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($conn[$key], CURLOPT_HEADER, 0);
            curl_multi_add_handle($mh, $conn[$key]);
        }

		$active = null;
		// 执行批处理句柄
		do{
			$mrc = curl_multi_exec($mh, $active);
		}while($mrc == CURLM_CALL_MULTI_PERFORM);

		while($active && $mrc == CURLM_OK){
			do{
				$mrc = curl_multi_exec($mh, $active);
			}while($mrc == CURLM_CALL_MULTI_PERFORM);
		}

        if($mrc != CURLM_OK){
            throw new Exception('Curl Multi Exec Batch Resource Is Failed');
        }
 
        foreach($urls as $key => $url){
            if(!curl_error($conn[$key])){
                $content = curl_multi_getcontent($conn[$key]);
                
				// 回调函数, 处理采集的内容, php 反射机制, $this->_observer->$this->_method 报错
				$class = new ReflectionClass($this->_observers);
				$model = $class->newInstanceArgs();
				$method = $class->getMethod($this->_method);
				$method->invoke($model, $content);
            }
            curl_multi_remove_handle($mh, $conn[$key]);
            curl_close($conn[$key]);
        }
        return true;
    }

    /**
     * @brief 单例
     * @return type
     */
    public static function getInstance($array = array()){
        if(self::$class === null){
            self::$class = new CurlMulti($array);
        }
        return self::$class;
    }

}
?>
<?php

date_default_timezone_set('PRC');

/**
 * 属性验证类
 * @author 刘宇峰<liuyufeng@ganji.com>
 * @version 1.0
 * @package unit
 * @copyright ganji.com
 */
class Attribute {

    public function __construct($conf) {
        $this->conf = $conf;
        $this->char = 'utf-8';
    }

    /**
     * 针对属性的验证
     *
     * @param string $str
     * @return unknown
     */
    public function __call($funname, $para) {
        $_Conf = $this->conf [$funname];
        $_type = strtolower($_Conf ['type']);
        return $this->$_type($para [0], $_Conf, $funname);
    }

    /**
     * 针对手机号的单独验证
     * 
     * @param string $content
     * @return boolean
     */
    public function phone($content) {
        $content = trim($content);
        $ereg = "/^1[34578]\d{9}$|^(0\d{2,4}-?)?[2-9]\d{6,7}(-\d{2,5})?$|^(?!\d+(-\d+){3,})[48]00(-?\d){7,10}$/";
        $re = preg_match($ereg, $content);
        return 0 == $re ? $this->Err('phone', $content, __FUNCTION__) : true;
    }
    /**
     * 针对价格的验证
     *
     * @param string $content
     * @return boolean 
     */
    public function price($content) {
        $selfConf = $this->conf[__FUNCTION__];
        if (1 == $selfConf ['isreg']) {
            if (false == preg_match($selfConf ['reg'], $content)) {
                if(14==$selfConf['category_major_id']){
                    return true;
                }
                return $this->Err('error', $content, __FUNCTION__, $selfConf['errormsg']);
            }
            return true;
        } else {
            if (false === $this->len($content, $selfConf ['max'], $selfConf ['min']))
                return $this->Err('len', $content, __FUNCTION__);
            if ('int' == $selfConf['datatype'] && (false == preg_match('/^\d*$/', $content) || empty($content))) {
                return $this->Err('datatype', $content, __FUNCTION__);
            }
            return true;
        }
        return true;
    }

    /**
     *
     * 针对排气量做的验证
     * 
     * @param string $content
     * @return boolean 
     */
    public function air_displacement($content) {
        if (false == preg_match('/(^[1-9]\d?(\.\d)?$)|(^0\.[1-9]$)/', $content)) {
            return $this->Err('paiqiliang', $content, __FUNCTION__);
        }
        return true;
    }

    /**
     * 针对所有联系人的验证
     *
     * @param string $content
     * @return boolean 
     */
    public function person($content) {
        //if (false == preg_match('/^([a-zA-Z_\x{4e00}-\x{9fa5}]){2,6}$/u', $content)) {
        //由于上面的判断对不同字符集，结果不一样，做如下修改
        $len = mb_strlen($content, $this->char);
        if ($len < 2 || $len > 6) {
            return $this->Err('error', $content, __FUNCTION__, ATT_ERR_MESSAGE_PERSON);
        }
        return true;
    }

    public function niandai($content) {
        if (false == preg_match('/^[1-9]\d*$/', $content)) {
            return $this->Err('error', $content, __FUNCTION__, '要填写4位数字哦');
        }
        if (mb_strlen($content, 'utf-8') > 4) {
            return $this->Err('error', $content, __FUNCTION__, '要填写4位数字哦');
        }
        if ($content > date('Y', time())) {
            return $this->Err('error', $content, __FUNCTION__, '建筑年代不能大于当前年份哦');
        }
        return true;
    }

    /**
     *
     * 针对楼层做的处理
     * 
     * @param string $content
     * @return boolean 
     */
    public function ceng($content) {
        if (false == preg_match('/(^\-[1-9]$|^[1-9][0-9]*$)/', $content)) {
            return $this->Err('error', $content, __FUNCTION__);
        }
        return true;
    }

    /**
     *
     * 针对楼层总数做的处理
     * 
     * @param string $content
     * @return boolean 
     */
    public function ceng_total($content) {
        if (false == preg_match('/^\d{1,2}$/', $content) || $content < 0) {
            return $this->Err('error', $content, __FUNCTION__);
        }
        return true;
    }

    public function huxing_shi($content) {
        if (false == preg_match('/^[0-9]+$/', $content) || $content <= 0) {
            return $this->Err('error', $content, __FUNCTION__, '要填写正整数哦');
        }
        return true;
    }

    public function huxing_ting($content) {
        if (false == preg_match('/^[0-9]+$/', $content)) {
            return $this->Err('error', $content, __FUNCTION__, '要填写“0”或正整数哦');
        }
        return true;
    }

    public function huxing_wei($content) {
        if (false == preg_match('/^[0-9]+$/', $content)) {
            return $this->Err('error', $content, __FUNCTION__, '要填写“0”或正整数哦');
        }
        return true;
    }

    /**
     * 针对text类型的处理
     *
     * @param string $content
     * @param arr $_conf
     * @return boolean[true|false]
     */
    private function text($content, $_conf, $fun) {
        if($_conf['category_id']==6 && $fun=='title'  && $this->conf['name']=='拼车') {
            return true;
        }else{
            if (1 == $_conf ['isreg']) {
            if (false == preg_match($_conf ['reg'], $content)) {
                return $this->Err('error', $content, $fun, $_conf['errormsg']);
            } else {
                return true;
            }
        } else {
            if (false === $this->len($content, $_conf ['max'], $_conf ['min']))
                return $this->Err('len', $content, $fun);
            if (true == $_conf ['checkchar']) {
                if (false == $this->special($content))
                    return $this->Err('special', $content, $fun);
            }
            return true;
        }

        }
        return true;
    }

    /**
     * 针对radio类型的处理
     *
     * @param string $content
     * @param arr $_conf
     * @return boolean[true|false]
     */
    private function radio($content, $_conf, $fun) {
        if (null == $content && 0 !== $content) {
            return $this->Err('empty', $content, $fun);
        } elseif (!in_array($content, $GLOBALS['values'][$_conf['values']])) {
            return $this->Err('in_array', $content, $fun);
        }
        return true;
    }

    /**
     * 针对select类型的处理
     *
     * @param string $content
     * @param arr $_conf
     * @return boolean[true|false]
     */
    private function select($content, $_conf, $fun) {
        return true;
    }

    /**
     * 针String类型的处理
     *
     * @return boolean[true|false]
     */
    private function str() {
        return true;
    }

    /**
     * 针对
     *
     * @return boolean[true|false]
     */
    private function int() {
        return true;
    }

    /**
     * 针对长度的验证
     * 
     * @param string $str
     * @return boolean[true|false]
     */
    private function len($str, $max, $min) {
        $len = mb_strlen($str, $this->char);
        if ($len > $max || $len < $min) {
            return false;
        }
        return true;
    }

    /**
     * 针对年龄做单独验证
     *
     * @param type $str 
     */
    public function age($str) {
        if (false == preg_match('/([0-9-]){2}/', $str)) {
            return $this->Err('age_error_1', $str, __FUNCTION__);
        }
        if (16 >= $str) {
            return $this->Err('age_error_2', $str, __FUNCTION__);
        }
        return true;
    }

    /**
     * 验证特殊字符
     *
     * @param string $str
     * @return boolean
     */
    private function special($str) {
        $ereg = "/^1[3458]\d{9}|(0\d{2,4}-?)?[2-9]\d{6,7}(-\d{2,5})?|(?!\d+(-\d+){3,})[48]00(-?\d){7,10}$/";
        $re = preg_match($ereg, $str);
        return 0 == $re ? true : false;
    }

    /**
     * 输出错误
     *
     * @param unknown_type $type
     * @param unknown_type $conf
     * @param unknown_type $content
     * @param unknown_type $fun
     * @return unknown
     */
    private function Err($type, $content, $fun, $exmsg='') {
        $ErrArr = array('state' => false);
        $header = $this->conf ['name'] . ' ' . $this->conf [$fun]['name'];
        switch ($type) {
            case 'error':
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_ERR, $header, $exmsg);
                break;
            case 'len' :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_LEN, $header, $this->conf [$fun] ['min'], $this->conf [$fun] ['max']);
                break;
            case 'special' :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_SPECIAL, $header);
                break;
            case 'empty' :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_EMPTY, $header);
                break;
            case 'in_array':
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_IN_ARRAY, $header, $this->conf [$fun]['min'], $this->conf [$fun]['max']);
                break;
            case 'phone' :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_PHONE, $header);
                break;
            case 'datatype' :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_TYPE, $header, $GLOBALS['datatype'][$this->conf [$fun]['datatype']]);
                break;
            case 'paiqiliang':
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_PAIQILIANG, $header);
                break;
            case 'age_error_1':
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_AGE_1);
                break;
            case 'age_error_2':
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_AGE_2);
                break;
            default :
                $ErrArr ['message'] = sprintf(ATT_ERR_MESSAGE_ERR, $header, $exmsg);
                break;
        }
        return $ErrArr;
    }

}

?>

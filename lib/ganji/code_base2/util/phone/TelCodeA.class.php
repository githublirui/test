<?php
/**
 * 电话号码加密实现方案之一
 * 
 * @author  caifeng@ganji.com
 * @version v5
 * @since   2011-3-10
 * 
 */
/**
 *@brief 电话号码图片加密解密类
 *@copyright 北京飞翔人信息技术有限公司,2008-8-12
 *@author 张宴
 *@example <img src="http://image.ganji.com/tel/016000600e6c5c310d6201330169023500650d330138.png">
 *          其中 016000600e6c5c310d6201330169023500650d330138 这一部分等于 TelImage::tel_encrypt("15901283960",$tel_key)
 **/
class TelCodeA {
    const ENCRYPT_KEY = "GaN-@J#i_85%!]"; //密钥

    protected function telHexToBin($data) {
        $len = strlen($data);
        return pack("H" . $len, $data);
    }

    protected function telKeyED($txt, $encryptKey) {
        $encryptKey = md5($encryptKey);
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encryptKey))
                $ctr = 0;
            $tmp .= substr($txt, $i, 1) ^ substr($encryptKey, $ctr, 1);
            $ctr++;
        }
        return $tmp;
    }

    public function Encode($txt, $telKey = TelCodeA :: ENCRYPT_KEY) {
        #srand((double)microtime()*1000000);
        $rand = date('l jS h');
        #$rand = rand(0,32000);
        $encryptKey = md5($rand);
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encryptKey))
                $ctr = 0;
            $tmp .= substr($encryptKey, $ctr, 1) .
             (substr($txt, $i, 1) ^ substr($encryptKey, $ctr, 1));
            $ctr++;
        }
        return bin2hex(self :: telKeyED($tmp, $telKey));
    }

    public function Decode($txt, $telKey = TelCodeA :: ENCRYPT_KEY) {
        $txt = self :: telKeyED(self :: telHexToBin($txt), $telKey);
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = substr($txt, $i, 1);
            $i++;
            $tmp .= (substr($txt, $i, 1) ^ $md5);
        }
        return $tmp;
    }
}
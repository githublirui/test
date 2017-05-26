<?php
/**
 * 有别于Crypt3DesNamespace.class.php，使用string（十进制）
 * 单例模式提供加密、解密
 * 常用3des
 * 密钥来源：按照移动全站规定，统一使用借此管理此密钥
 * @author    wangjian
 * @touch     2012-9-13
 * @category  wap
 * @package   WapCrypt3DesStaticNamespace.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */

class WapCrypt3DesStaticNamespace
{
    static $_instance;
    private  static function _staticConstruct()
    {
        if(empty(self::$_instance))
        {
            self::$_instance=new WapCrypt3DesStaticNamespace(null,null);
        }
    }
    /***便于大规模调用，提供静态**/
    /**
     *  加密
     *
     */
    public static function  encryptStatic($input)
    {
        self::_staticConstruct();
        $encrpt= self::$_instance->encrypt($input);
        if(is_string($encrpt))
        {
            $t=bin2hex($encrpt);
            return $t;
        }
    }
    /**
     * 解密
     * @param string $input
     * @param bool $isHexStr 描述加密字符串
     * @return string
     */
    public  static  function decryptStatic($encrypted,  $isHexStr)
    {
        self::_staticConstruct();
        $encryptedBin=pack("H*",$encrypted);
        $decrpt=  self::$_instance->decrypt($encryptedBin, $isHexStr);
        //         if(is_numeric($decrpt))
        //         {
        return $decrpt;
        //         }

    }

    public  function  _construct($key ,$iv)
    {
        // 		$this->key=$key;
        // 		$this->iv=$iv;

    }
    public  $key="%$#(*N@MHGPL><NRMvMghsO*";
    public $iv="s(2L@f!o";

    //加密
    public function encrypt($input)
    {
        $input = $this->padding( $input );
        $key = base64_decode($this->key);
        $td = mcrypt_module_open( MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        //使用MCRYPT_3DES算法,cbc模式
        mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $data = mcrypt_generic($td, $input);
        //加密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $data = $this->removeBR(base64_encode($data));
        return $data;
    }

    //解密
    public function decrypt($encrypted)
    {
        $encrypted = base64_decode($encrypted);
        $key = base64_decode($this->key);
        $td = mcrypt_module_open( MCRYPT_3DES,'',MCRYPT_MODE_CBC,'');
        //使用MCRYPT_3DES算法,cbc模式
        mcrypt_generic_init($td, $key, $this->iv);
        //初始处理
        $decrypted = mdecrypt_generic($td, $encrypted);
        //解密
        mcrypt_generic_deinit($td);
        //结束
        mcrypt_module_close($td);
        $decrypted = $this->removePadding($decrypted);
        return $decrypted;
    }

    //填充密码，填充至8的倍数
    public function padding( $str )
    {
        $len = 8 - strlen( $str ) % 8;
        for ( $i = 0; $i < $len; $i++ )
        {
            $str .= chr( 0 );
        }
        return $str ;
    }

    //删除填充符
    public function removePadding( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != chr( 0 ))
            {
                $newstr .= $str[$i];
            }
        }
        return $newstr;
    }

    //删除回车和换行
    public function removeBR( $str )
    {
        $len = strlen( $str );
        $newstr = "";
        $str = str_split($str);
        for ($i = 0; $i < $len; $i++ )
        {
            if ($str[$i] != '\n' and $str[$i] != '\r')
            {
                $newstr .= $str[$i];
            }
        }

        return $newstr;
    }
}



?>
<?PHP
/**
 * @brief 加密类, DES 数据加密标准（data encryption standard）；
 * @category   
 * @copyright (c) 2011 Ganji Inc.
 * @author    duxiang duxiang@ganji.com
 * @date      时间: 2012-11-28:下午03:18:15
 * @version    1.0 
 *
 */
class DesNamespace {
    /** AES */
    const MODE_AES = 1;

    /** DES */
    const MODE_DES = 2;

    /** 3DES */
    const MODE_3DES = 3;

    private static $_HANDLE_ARRAY   = array();    

    private static function _getHandleKey($params) {
        ksort($params);
        return md5(implode('_' , $params));
    }

    /**
     * @brief 创建一个加密对象
     * @param integer $mode  加密的类型
     * @param string $secretKey 密钥
     * @param string $iv Des3EncryptAdapter 需要IV
     * @return  DesEncryptAdapter|AesEncryptAdapter|Des3EncryptAdapter
     * @see code_base2/util/des/unittest/DesTest.php 
     */
    public static function create($mode, $secretKey, $iv = null) {
        $handle_key = self::_getHandleKey(array(
            'mode'      => $mode,
            'secretKey'   => $secretKey,
        ));

        if (isset(self::$_HANDLE_ARRAY[$handle_key])) {
            return self::$_HANDLE_ARRAY[$handle_key];
        }

        switch($mode) {
            case self::MODE_DES:
                require_once dirname(__FILE__) . '/adapter/DesEncryptAdapter.class.php';
                $objCache = new DesEncryptAdapter($secretKey);
                break;
            case self::MODE_3DES:
                require_once dirname(__FILE__) . '/adapter/Des3EncryptAdapter.class.php';
                $objCache = new Des3EncryptAdapter($secretKey, $iv);
                break;
            case self::MODE_AES:
                require_once dirname(__FILE__) . '/adapter/AesEncryptAdapter.class.php';
                $objCache = new AesEncryptAdapter($secretKey);
                break;
            default:
                require_once dirname(__FILE__) . '/adapter/AesEncryptAdapter.class.php';
                $objCache = new AesEncryptAdapter($secretKey);
                break;
        }

        self::$_HANDLE_ARRAY[$handle_key]    = $objCache;
        return $objCache;
    }
}

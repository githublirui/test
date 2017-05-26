<?PHP
/**
 * 电话号码加密类工厂，用于创建不同的加密算法
 * 
 * @author  caifeng@ganji.com
 * @version v5
 * @since   2011-3-10
 * 
 */


/**
 * @brief 对各种不同的电话加密算法进行封装
 */
class TelCodeFactory {
    const TEL_IMAGE = 0;
    const TEL_URL = 1;

    /**
     * @brief 创建一个实例
     * @param int usage 该实例的用途
     * @return object 返回实例
     */
    public static function createInstance($usage) {
        if ($usage == TelCodeFactory :: TEL_IMAGE) {
//            include_once dirname(__FILE__) . "/TelCodeA.class.php";
//            return new TelCodeA();
            include_once dirname(__FILE__) . "/TelCodeC.class.php";
            return new TelCodeC();
        } else
            if ($usage == TelCodeFactory :: TEL_URL) {
                include_once dirname(__FILE__) . "/TelCodeB.class.php";
                return new TelCodeB();
            }
    }
}
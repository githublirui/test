<?PHP
/**
 * @brief 构造template对象
 * @package              ganji.cache
 * @author               yangyu
 * @file                 $RCSfile: TplNamespace.class.php,v $
 * @version              $Revision: 50890 $
 * @modifiedby           $Author: longweiguo $
 * @lastmodified         $Date: 2011-03-11 17:29:21 +0800 (五, 2011-03-11) $
 * @copyright            Copyright (c) 2011, ganji.com
 */

class TplNamespace
{
	const TYPE_DEFAULT	= 'default';
	const TYPE_SMARTY	= 'smarty';
    
    private static $_TPLS   = array();	
    
    /**
     * @brief 创建一个模板对象
     * @param $mode int 模板的类型
     * @param $config array 配置参数
     * @return  TplAdapter
     */
	public static function createTpl($type, $config=array()) {
        if (isset(self::$_TPLS[$type])) {
            return self::$_TPLS[$type];
        }
	    
		switch($type) {
			case self::TYPE_DEFAULT:
				require_once dirname(__FILE__) . '/adapter/PhpTplAdapter.class.php';
				$objTpl = new PhpTplAdapter($config);
				break;
			case self::TYPE_SMARTY:
			    require_once dirname(__FILE__) . '/adapter/SmartyTplAdapter.class.php';
				$objTpl = new SmartyTplAdapter($config);
				break;
			default:
			    require_once dirname(__FILE__) . '/adapter/PhpTplAdapter.class.php';
				$objTpl = new PhpTplAdapter($config);
				break;
		}
		
		self::$_TPLS[$type]    = $objTpl;
		
		return $objTpl;
	}

}


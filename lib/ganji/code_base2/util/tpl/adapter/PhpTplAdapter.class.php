<?php
require_once dirname(__FILE__) . '/../../../app/base/HelperNamespace.class.php';

class PhpTplAdapter
{
    private static $templateDirPath;
    private static $helperDirPath = array();

	public function __construct($config)
	{
		self::$templateDirPath = $config['template_path'];
		
		if (!empty($config['helper_path'])) {
           	if (is_string($config['helper_path'])) {
           		HelperNamespace::addDir($config['helper_path']);
           	} else if (is_array($config['helper_path'])) {
	            foreach ($config['helper_path'] as $dir) {
	            	HelperNamespace::addDir($dir);
	            }
           	}
        }
	}
	
	public function addHelperDir($dir) {
		return HelperNamespace::addDir($dir);
	}
	
	public function fetch($templateFile, &$values)
	{
        foreach ((array)$values as $k => $v){
            $this->$k = $v;
        }

		ob_start();
        include self::$templateDirPath . '/' . $templateFile;
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
	}
	
	/**
	 * 使用插件
	 * @param 插件名称 $funcName 对应plugin/helper/$funcName_helper.php中的$funcName_helper方法
	 * @param mix $params
	 * @return mix
	 */
	public function helper($funcName, $params=array())
    {
        return HelperNamespace::call($funcName, $params);
    }

    /**
     * 载入子模板，在模板中使用
     * @param string $fileName 子模板文件名，相对于模板目录指定
     * @param array $params 要传给子模板的变量
     */
	protected function load($fileName, $params=array())
    {
        $filePath = self::$templateDirPath . '/' . $fileName;
        if (!file_exists($filePath)){
            trigger_error('文件"'.$filePath.'"不存在', E_USER_NOTICE);
        }
        else {
            extract((array)$params);
            include $filePath;
        }
    }
}

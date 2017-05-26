<?PHP

/** 
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /code_base2/app/base/DispatchNamespace.class.php
 * @author        longweiguo@ganji.com
 * @date          2011-08-20
 *
 * 流程分发
 */

/**
 * @class: DispatchNamespace
 * 流程分发类
 * 所有用户的访问都是通过此类进行分发到app下不同目录下的BasePage派生类的*Action()方法执行
 */
class DispatchNamespace {
    public static $APP_PATH             = '';          // app所在的路径，结尾不要“/” 
    public static $MODULE_NAME_QUERY    = 'mod';       // QUERY_STRING中表示分发到指定模块的下标
    public static $ACTION_NAME_QUERY    = 'act';       // QUERY_STRING中表示分发到指定方法的下标
    public static $MODULE_NAME_DEFAULT  = 'Index';     // 默认要执行的模块
    public static $MODULE_NAME_EXT      = 'Page';      // 模块名称的后缀
    public static $ACTION_NAME_DEFAULT  = 'default';   // 默认要执行的模块中的方法
    public static $ACTION_NAME_EXT      = 'Action';    // 方法名称的后缀

    
    /// 执行分发
    /// @param[in] array  config 自定义参数
    public static function run($module_dir='', $module_name='', $action_name='', $custom_vars=array(), $contentType='', $jsonpCallback='') {
        if (!empty($contentType)) {
            ResponseNamespace::setContentType($contentType, $jsonpCallback);
        }

        $moduleDirPath = self::getModuleDirPath($module_dir);

        $className = self::getModuleName($module_name);

        $filePath = $moduleDirPath . "/" . $className . ".class.php";
        if (!file_exists($filePath)) {
            trigger_error("文件{$filePath}不存在", E_USER_ERROR);
        }

        $actionName = self::getActionName($action_name);
        include_once $filePath;
        $instance = new $className($custom_vars);

        if (method_exists($instance, 'init')) {
            $instance->init();
        }
        
        if (method_exists($instance, $actionName)) {
            $instance->$actionName();
        }
    }

    protected static function getModuleDirPath($module_dir) {
        $moduleDirPath = self::$APP_PATH;
        if (!empty($module_dir)) {
            $moduleDirPath .= '/' . $module_dir;
        }
        if (!is_dir($moduleDirPath)) {
            trigger_error("目录{$moduleDirPath}不存在", E_USER_ERROR);
        }
        return $moduleDirPath;
    }

    protected static function getModuleName($module_name) {
        $moduleName = HttpNamespace::getREQUEST(self::$MODULE_NAME_QUERY);
        if (empty($moduleName)) {
            if (!empty($module_name)) {
                $moduleName = $module_name;
            }
            else {
                $moduleName = self::$MODULE_NAME_DEFAULT;
            }
        }
        if (empty($moduleName)) {
            trigger_error("未指定{$moduleName}", E_USER_ERROR);
        }

        if (strpos($moduleName, '_') === false) {
            return ucfirst($moduleName) . self::$MODULE_NAME_EXT;
        }
        
        $modules = explode("_", $moduleName);
        $str = '';
        foreach ($modules as $module) {
            $str .= ucfirst($module);
        }
        return $str . self::$MODULE_NAME_EXT;
    }

    protected static function getActionName($action_name) {
        $actionName = HttpNamespace::getREQUEST(self::$ACTION_NAME_QUERY);
        if (empty($actionName)) {
            if (!empty($action_name)) {
                $actionName = $action_name;
            }
            else {
                $actionName = self::$ACTION_NAME_DEFAULT;
            }
        }
        return $actionName . self::$ACTION_NAME_EXT;
    }

}

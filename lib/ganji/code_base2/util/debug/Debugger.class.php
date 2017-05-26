<?php
/**
 * @brief     调试输出工具（基于SESSION进行过滤，避免影响其他用户）
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-8-20
 * @example
 * 1、在要调试的页面中加入以下代码：
 *   <?php require_once CODE_BASE2 . '/util/debug/Debugger.class.php'; Debugger::init('zhangshenjia')->var_dump('aaaa');?>
 * 2、从浏览器中访问要调试的页面，在URL尾部加上GET参数?debug=zhangshenjia
 * 3、此后只要不清空SESSION（比如退出登录等），就可一直进行调试，其他用户不受影响。
 */
require_once CODE_BASE2 . '/util/session/SessionNamespace.class.php';

class Debugger {

    /**
     * SESSION键值
     * @var string
     */
    const SESSION_KEY = 'ganji_debugger_id';

    /**
     * 调试模式开关
     * @var boolean
     */
    private $_DEBUGGING = false;

    /**
     * 入口方法，传入一个字符串用做唯一标识（推荐使用域帐号名称）
     * @param string $uniqueId
     * @return Debugger
     */
    public static function init($uniqueId) {
        return new Debugger($uniqueId);
    }

    /**
     * 构造方法
     * @param string $uniqueId
     */
    public function __construct($uniqueId) {
        SessionNamespace::init();
        // 如果传入GET参数匹配，将唯一标识写入SESSION
        if (isset($_GET['debug']) && $uniqueId === $_GET['debug']) {
            SessionNamespace::setValue(self::SESSION_KEY, $uniqueId);
        }
        // 如果SESSION与唯一标识匹配，调试模式启动
        if ($uniqueId === SessionNamespace::getValue(self::SESSION_KEY)) {
            $this->_DEBUGGING = true;
        }
    }

    /**
     * 以下为封装的系统函数，目前支持：
     * var_dump，var_export，print_r
     */
    public function var_dump()   { $args = func_get_args(); return self::_invoke(__FUNCTION__ , $args); }
    public function var_export() { $args = func_get_args(); return self::_invoke(__FUNCTION__ , $args); }
    public function print_r()    { $args = func_get_args(); return self::_invoke(__FUNCTION__ , $args); }

    /**
     * 调用系统函数（如果调试模式已启动）
     * @param string $function
     * @param array $arguments
     * @return mixed
     */
    private function _invoke($function, $arguments) {
        if ($this->_DEBUGGING) {
            return call_user_func_array($function, $arguments);
        }
        return false;
    }
}
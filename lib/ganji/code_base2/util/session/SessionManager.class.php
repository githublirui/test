<?php
/**
 * 所有使用session的文件，必须require_one本文件
 * 
 * @author nkapproach@gmail.com 2009-6-11
 *
 */

class SessionManager
{

    public static function init() {
        static $initFinish = FALSE;
        if (!$initFinish) {
            $initFinish = TRUE;
            // 打开session支持
            session_start();

            if( session_id()=='deleted' )
            {
                $newId = 's_' . str_replace('.', '_', microtime(true)) . '_' . rand(1000, 9999);
                session_unset();
                session_destroy();
                session_id($newId);
                setcookie(session_name(), $newId, 0, '/', 'ganji.com');
                error_log(date('Y-m-d H:i:s ') . 'fix session:' . $newId . "\n");
            }
        }
    }

	/**
	 * 读取或写入session值，注意：仅存取一次。
	 * $value为空时，表示读取。
	 * 
	 * 示例：
	 * 	这个一般用于在redirect到某个页面前，先存入某个临时值，到新页面取出
	 * 
	 * @param $key				SESSION中的key
	 * @param $value			要存入SESSION的值
	 * @return unknown_type
	 * 
	 */
	public static function flashData($key, $value=NULL)
    {
        self::init();
		if($value===NULL)
		{
			if (isset($_SESSION[$key])) 
			{
				$value = $_SESSION[$key];
				unset($_SESSION[$key]);
			}
		} 
		else 
		{
			$_SESSION[$key] = $value;
		}
		return $value;
	}
	
	/**
	 * 向session中存入某个值
	 * 
	 * 返回当前key对应的旧值，如果原来key不存在，则返回NULL
	 * 
	 */
	public static function setValue($key, $value)
	{
        self::init();
		$ret = NULL;
		if (isset($_SESSION[$key])) 
		{
			$ret = $_SESSION[$key];
		}
		$_SESSION[$key] = $value;
		return $ret;
	}
	
	/**
	 * 从session中获取某个值
	 *
	 */
	public static function getValue($key, $default=NULL)
	{
        self::init();
		if (isset($_SESSION[$key]))
		{
			return $_SESSION[$key];
		}
		return $default;
	}
	
	public static function exists($key)
	{
        self::init();
		return isset($_SESSION[$key]);
	}
	
	/**
	 * 从session中删除某个值
	 *
	 */
	public static function delete($key)
	{
        self::init();
		if (isset($_SESSION[$key]))
		{
			unset($_SESSION[$key]);
		}		
	}
	
	/**
	 * 清空Session
	 *
	 */
	public static function destroy()
	{
        self::init();
		session_unset();
		session_destroy();
	}
	
}

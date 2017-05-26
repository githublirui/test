<?PHP

/**
 * @Copyright (c) 2010 Ganji Inc.
 * @file          /code_base2/util/db/DBMysqlNamespace.class.php
 * @author        haohuang
 * @date          2011-03-29
 *
 * 数据库操作的类库
 */
require_once(dirname(__FILE__) . '/../datetime/DateTimeNamespace.class.php');

define('SLOW_QUERY_MIN', 200);
define('SLOW_QUERY_SAMPLE', 10);

/**
 * @class: DBConstNamespace
 * @PURPOSE:  DBConstNamespace 可以认为是一个名字空间， 其中定义了若干数据库相关的常量， 如编码等
 */
class DBConstNamespace {

    // 数据库编码相关
    const ENCODING_GBK = 0; ///< GBK 编码定义
    const ENCODING_UTF8 = 1; ///< UTF8 编码定义
    const ENCODING_LATIN = 2; ///< LATIN1 编码定义
    // 数据库句柄需要ping
    const HANDLE_PING = 100;

}

/**
 * @class: DBMysqlNamespace
 * @PURPOSE:  DBMysqlNamespace 可以认为是一个名字空间， 其中定义了若干操作数据库的静态方法
 */
class DBMysqlNamespace {

    /**
     * @biref 数据库句柄是否需要ping
     * @var mix
     */
    private static $_HANDLE_PING = false;

    /**
     * 已打开的db handle
     * @var array
     */
    private static $_HANDLE_ARRAY = array();

    private static function _getHandleKey($params) {
        ksort($params);
        return md5(implode('_', $params));
    }

    /**
     * @brief 设置 handle ping的属性
     * @param $value
     * @return unknown_type
     * @example 
     *      如果是crontabe 建议在开始是增加
     *      DBMysqlNamespace::setHandlePing(DBConstNamespace::HANDLE_PING);
     *      如果关闭可以设置 false, 默认是不开启的
     *      DBMysqlNamespace::setHandlePing();
     */
    public static function setHandlePing($value = false) {
        self::$_HANDLE_PING = $value;
    }

    /**
     * 创建一个面向对象的DBHandle，参数描述同createDBHandle
     * @param $db_config_array
     * @param $db_name
     * @param int $encoding
     * @return bool|DBHandle
     */
    public static function createDBHandle2($db_config_array, $db_name, $encoding = DBConstNamespace::ENCODING_UTF8) {
        $handle = self::createDBHandle($db_config_array, $db_name, $encoding);
        if ($handle) {
            include_once( dirname(__FILE__) . "/DBHandle.class.php");
            return new DBHandle($handle);
        }
        return FALSE;
    }

    /// 根据数据库表述的参数获取数据库操作句柄
    /// @param[in] array $db_config_array, 是一个array类型的数据结构，必须有host, username, password 三个熟悉, port为可选属性， 缺省值分别为3306
    /// @param[in] string $db_name, 数据库名称
    /// @param[in] enum $encoding, 从$DBConstNamespace中数据库编码相关的常量定义获取, 有缺省值 $DBConstNamespace::ENCODING_UTF8
    /// @return 非FALSE表示成功获取hadnle， 否则返回FALSE
    public static function createDBHandle($db_config_array, $db_name, $encoding = DBConstNamespace::ENCODING_UTF8) {
        $db_config_array['db_name'] = $db_name;
        $db_config_array['encoding'] = $encoding;
        $handle_key = self::_getHandleKey($db_config_array);

        if (isset(self::$_HANDLE_ARRAY[$handle_key]) && self::_checkHandle(self::$_HANDLE_ARRAY[$handle_key])) {
            return self::$_HANDLE_ARRAY[$handle_key];
        }

        $port = 3306;
        do {
            if (!is_array($db_config_array))
                break;
            if (!is_string($db_name))
                break;
            if (strlen($db_name) == 0)
                break;
            if (!array_key_exists('host', $db_config_array))
                break;
            if (!array_key_exists('username', $db_config_array))
                break;
//            if (!array_key_exists('password', $db_config_array))
//                break;
            if (array_key_exists('port', $db_config_array)) {
                $port = (int) ($db_config_array['port']);
                if (($port < 1024) || ($port > 65535))
                    break;
            }
            $host = $db_config_array['host'];
            if (strlen($host) == 0)
                break;
            $username = $db_config_array['username'];
            if (strlen($username) == 0)
                break;
            $password = $db_config_array['password'];
            if (strlen($password) == 0)
                break;

            //$conn_time = DateTimeNamespace::getMicrosecond();
            // mysqli_connect(); will also throw a warning on an unsuccessfull connect. To avoid such warnings being shown prefix it with an "@" symbol.
            $handle = @mysqli_connect($host, $username, $password, $db_name, $port);

            // 如果连接失败，再重试2次
            for ($i = 1; ($i < 3) && (FALSE === $handle); $i++) {
                // 重试前需要sleep 50毫秒
                usleep(50000);
                $handle = @mysqli_connect($host, $username, $password, $db_name, $port);
            }
            //$conn_time = DateTimeNamespace::getMicrosecond() - $conn_time;
            if (FALSE === $handle)
                break;

            $is_encoding_set_success = true;
            switch ($encoding) {
                case DBConstNamespace::ENCODING_UTF8 :
                    $is_encoding_set_success = mysqli_set_charset($handle, "utf8");
                    break;
                case DBConstNamespace::ENCODING_GBK :
                    $is_encoding_set_success = mysqli_set_charset($handle, "gbk");
                    break;
                default:
            }
            if (FALSE === $is_encoding_set_success) {
                mysqli_close($handle);
                break;
            }
            self::$_HANDLE_ARRAY[$handle_key] = $handle;
            return $handle;
        } while (FALSE);
        // to_do, 连接失败，需要记log
        $password_part = isset($password) ? substr($password, 0, 5) . '...' : '';
//        self::logError( "Connect failed: $username@$host:$port,pw=$password_part,$db_name", 'mysqlns.connect');
        $logArray = $db_config_array;
        $logArray['password'] = $password_part;
        self::logError(sprintf("Connect failed:db_config_array=%s", var_export($logArray, true)), 'mysqlns.connect');
        return FALSE;
    }

    /// 释放通过getDBHandle或者getDBHandleByName 返回的句柄资源
    /// @param[in] handle $handle, 你懂的
    /// @return void
    public static function releaseDBHandle($handle) {
        if (!self::_checkHandle($handle))
            return;
        foreach (self::$_HANDLE_ARRAY as $handle_key => $handleObj) {
            if ($handleObj->thread_id == $handle->thread_id) {
                unset(self::$_HANDLE_ARRAY[$handle_key]);
            }
        }
        mysqli_close($handle);
    }

    public static function insert($handle, $table, $data) {
        if (!is_array($data) || count($data) == 0)
            return false;
        $keys = '`' . implode('`,`', array_keys($data)) . '`';
        $values = "'" . implode('\',\'', array_values($data)) . "'";
        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";
        return self::insertAndGetID($handle, $sql);
    }

    /**
     * 根据条件删除数据
     * @param array|string $condition
     * @return boolean|string
     */
    public static function delete($handle, $table, $condition) {
        if (empty($condition))
            return false;
        $condition = self::format_condition($condition);
        $sql = "DELETE FROM `{$table}` WHERE {$condition}";
        return self::execute($handle, $sql);
    }

    public static function update($handle, $table, $data, $condition) {
        if (!is_array($data) || count($data) == 0 || empty($condition))
            return false;
        $set_info = array();
        foreach ($data as $key => $value) {
            $set_info[] = '`' . $key . '` = ' . "'$value'";
        }
        $set_str = implode(',', $set_info);
        $condition = self::format_condition($condition);
        $sql = "UPDATE `{$table}` SET {$set_str} WHERE {$condition}";
        return self::execute($handle, $sql);
    }

    /**
     * 格式化条件
     * @param array|string $data
     */
    public static function format_condition($data) {
        $condition = '';
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $condition .= "AND `{$key}`='{$value}' ";
            }
            $condition = ltrim($condition, 'AND');
        } else {
            $condition = $data;
        }
        return $condition;
    }

    /// 执行sql语句， 该语句必须是insert, update, delete, create table, drop table等更新语句
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @param[in] string $sql, 具体执行的sql语句
    /// @return TRUE:表示成功， FALSE:表示失败
    public static function execute($handle, $sql) {
        if (!self::_checkHandle($handle))
            return FALSE;
        $tm = DateTimeNamespace::getMicrosecond();
        if (mysqli_query($handle, $sql)) {
            $tm_used = intval((DateTimeNamespace::getMicrosecond() - $tm) / 1000);
            if ($tm_used > SLOW_QUERY_MIN && rand(0, SLOW_QUERY_SAMPLE) == 1) {
                self::logWarn("ms=$tm_used, SQL=$sql", 'mysqlns.slow');
            }
            @self::logFang($sql);
            return TRUE;
        }
        // to_do, execute sql语句失败， 需要记log
        self::logError("SQL Error: $sql, errno=" . self::getLastError($handle), 'mysqlns.sql');

        return FALSE;
    }

    /// 执行insert sql语句，并获取执行成功后插入记录的id
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @param[in] string $sql, 具体执行的sql语句
    /// @return FALSE表示执行失败， 否则返回insert的ID
    public static function insertAndGetID($handle, $sql) {
        if (!self::_checkHandle($handle))
            return false;
        do {
            if (mysqli_query($handle, $sql) === FALSE)
                break;
            if (($result = mysqli_query($handle, 'select LAST_INSERT_ID() AS LastID')) === FALSE)
                break;
            $row = mysqli_fetch_assoc($result);
            $lastid = $row['LastID'];
            mysqli_free_result($result);
            @self::logFang($sql);
            return $lastid;
        } while (FALSE);
        // to_do, execute sql语句失败， 需要记log
        self::logError("SQL Error: $sql, errno=" . self::getLastError($handle), 'mysqlns.sql');
        return FALSE;
    }

    /// 将所有结果存入数组返回
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @param[in] string $sql, 具体执行的sql语句
    /// @return FALSE表示执行失败， 否则返回执行的结果, 结果格式为一个数组，数组中每个元素都是mysqli_fetch_assoc的一条结果
    public static function query($handle, $sql) {
        if (!self::_checkHandle($handle))
            return FALSE;
        do {
            $tm = DateTimeNamespace::getMicrosecond();
            if (($result = mysqli_query($handle, $sql)) === FALSE)
                break;
            $tm_used = intval((DateTimeNamespace::getMicrosecond() - $tm) / 1000);
            if ($tm_used > SLOW_QUERY_MIN && rand(0, SLOW_QUERY_SAMPLE) == 1) {
                self::logWarn("ms=$tm_used, SQL=$sql", 'mysqlns.slow');
            }

            $res = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $res[] = $row;
            }
            @self::logFang($sql);
            mysqli_free_result($result);
            return $res;
        } while (FALSE);

        // to_do, execute sql语句失败， 需要记log
        self::logError("SQL Error: $sql, errno=" . self::getLastError($handle), 'mysqlns.sql');

        return FALSE;
    }

    /// 将查询的第一条结果返回
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @param[in] string $sql, 具体执行的sql语句
    /// @return FALSE表示执行失败， 否则返回执行的结果, 执行结果就是mysqli_fetch_assoc的结果
    public static function queryFirst($handle, $sql) {
        if (!self::_checkHandle($handle))
            return FALSE;
        do {
            $tm = DateTimeNamespace::getMicrosecond();
            if (($result = mysqli_query($handle, $sql)) === FALSE)
                break;
            $tm_used = intval((DateTimeNamespace::getMicrosecond() - $tm) / 1000);
            if ($tm_used > SLOW_QUERY_MIN && rand(0, SLOW_QUERY_SAMPLE) == 1) {
                self::logWarn("ms=$tm_used, SQL=$sql", 'mysqlns.slow');
            }

            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            @self::logFang($sql);
            return $row;
        } while (FALSE);
        // to_do, execute sql语句失败， 需要记log
        self::logError("SQL Error: $sql," . self::getLastError($handle), 'mysqlns.sql');
        return FALSE;
    }

    /**
     * 将所有结果存入数组返回
     * @param Mysqli $handle 句柄
     * @param string $sql 查询语句
     * @return FALSE表示执行失败， 否则返回执行的结果, 结果格式为一个数组，数组中每个元素都是mysqli_fetch_assoc的一条结果
     */
    public static function getAll($handle, $sql) {
        return self::query($handle, $sql);
    }

    /**
     * 将查询的第一条结果返回
     * @param[in] Mysqli $handle, 操作数据库的句柄
     * @param[in] string $sql, 具体执行的sql语句
     * @return FALSE表示执行失败， 否则返回执行的结果, 执行结果就是mysqli_fetch_assoc的结果
     */
    public static function getRow($handle, $sql) {
        return self::queryFirst($handle, $sql);
    }

    /**
     * 查询第一条结果的第一列
     * @param Mysqli $handle, 操作数据库的句柄
     * @param string $sql, 具体执行的sql语句
     */
    public static function getOne($handle, $sql) {
        $row = self::getRow($handle, $sql);
        if (is_array($row))
            return current($row);
        return $row;
    }

    /// 得到最近一次操作影响的行数
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @return FALSE表示执行失败， 否则返回影响的行数
    public static function lastAffected($handle) {
        if (!is_object($handle))
            return FALSE;
        $affected_rows = mysqli_affected_rows($handle);
        if ($affected_rows < 0)
            return FALSE;
        return $affected_rows;
    }

    /*
     *  返回最后一次查询自动生成并使用的id
     *  @param[in] handle $handle, 操作数据库的句柄
     *  @return FALSE表示执行失败， 否则id
     */

    public static function getLastInsertId($handle) {
        if (!is_object($handle)) {
            return false;
        }
        if (($lastInsertId = mysqli_insert_id($handle)) <= 0) {
            return false;
        }
        return $lastInsertId;
    }

    /**
     * @breif 模拟 mysql escape， 推荐使用 mysqli_real_escape_string
     * @param mix $inp
     */
    public static function mysqlEscapeMimic($inp) {
        if (is_array($inp)) {
            return array_map(__METHOD__, $inp);
        }
        if (!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }
        return $inp;
    }

    /// 得到最近一次操作错误的信息
    /// @param[in] handle $handle, 操作数据库的句柄
    /// @return FALSE表示执行失败， 否则返回 'errorno: errormessage'
    public static function getLastError($handle) {
//        if (!self::_checkHandle($handle))
//            return FALSE;
        if (mysqli_errno($handle)) {
            return mysqli_errno($handle) . ': ' . mysqli_error($handle);
        }
        return FALSE;
    }

    /**
     * @brief 检查handle
     * @param[in] handle $handle, 操作数据库的句柄
     * @return boolean true|成功, false|失败
     */
    private static function _checkHandle($handle) {
        if (!is_object($handle)) {
            self::logError(sprintf("handle Error: handle='%s'", var_export($handle, true)), 'mysqlns.handle');
            return false;
        }

        // MSC-2077 命令行模式 或者 手工指定 handle_ping
        if ((PHP_SAPI === 'cli' || self::$_HANDLE_PING === DBConstNamespace::HANDLE_PING) && method_exists($handle, 'ping')) {
            // 做3次ping
            $tryCount = 3;
            $pingRet = false;
            do {
                $pingRet = $handle->ping();
                if ($pingRet) {
                    break;
                }
                $tryCount --;
            } while ($tryCount > 0);
            if (!$pingRet) {
                self::logError(sprintf("handle_ping Error: handle='%s'", var_export($handle, true)), 'mysqlns.handle.ping');
                return false;
            }
        }
        return true;
    }

    public static function logFang($sql) {
        //检查是否存在标注目录
        if (!is_dir('/ganji/ganji_log/ms_mysql_log_trace')) {
            return;
        }
        //查询是否与house相关
        if (strpos($sql, 'house') === false) {
            return;
        }
        //去除推广库的查询
        if (strpos($sql, 'premier') !== false) {
            return;
        }
        $sql = str_replace(array("\n", "\t"), " ", $sql);
        $trace = debug_backtrace();
        $trace_msg = '';
        foreach ($trace as $key => $line) {
            if ($key >= 6) {
                break;
            }
            if ($key <= 1) {
                continue;
            }
            $func = isset($line['function']) ? $line['function'] : '';
            $class = isset($line['class']) ? $line['class'] : '';
            $trace_format = $key . ">" . substr($line['file'], 22) . ':' . $line['line'] . ">" . $class . "::" . $func . "|";
            $trace_msg .= $trace_format;
        }
        $log_line = implode("\t", array(date('Y-m-d H:i:s'), $sql, $trace_msg)) . "\n";
        file_put_contents('/ganji/ganji_log/ms_mysql_log_trace/ms_mysql_log_trace-' . date('Y_m_d') . '.log', $log_line, FILE_APPEND);
    }

    /// 记录统一的错误日志
    /// @param[in] string $message, 错误消息
    /// @param[in] string $category, 错误的子类别
    protected static function logError($message, $category) {
        if (class_exists('Logger')) {
            Logger::logError($message, $category);
        }
    }

    /// 记录统一的警告日志
    /// @param[in] string $message, 错误消息
    /// @param[in] string $category, 错误的子类别
    protected static function logWarn($message, $category) {
        if (class_exists('Logger')) {
            Logger::logWarn($message, $category);
        }
    }

    /**
     * @author shanghui@leju.sina.com.cn 2011-03-21
     * 根据ID更新记录
     * @param int $id
     * @param array $data
     * @return int or false
     */
    public static function update_by_id($handle, $table, $id, $data) {
        if (!is_numeric($id) || !is_array($data))
            return false;
        return self::update($handle, $table, $data, "id = '{$id}'");
    }

    /**
     * @author shanghui@leju.sina.com.cn 2011-03-21
     * 根据ID删除记录
     * @param int $id
     * @return true or false
     */
    public static function delete_by_id($handle, $table, $id) {
        if (!is_numeric($id))
            return false;
        return $this->delete($handle, $table, "id = '{$id}'");
    }

    /**
     * 根据条件获取一条记录
     * @param array|string $condition
     * @return multitype:
     */
    public static function fetch_row($handle, $table, $condition = '', $field = '*', $order = '') {
        $condition = self::format_condition($condition);
        $condition = !empty($condition) ? " WHERE {$condition} " : '';
        $order = !empty($order) ? ' ORDER BY ' . $order : '';
        $sql = "SELECT {$field} FROM `{$table}` {$condition} {$order} LIMIT 1";
        return self::getRow($handle, $sql);
    }

    /**
     * 根据条件获取指点字段的数据
     * @param array|string $condition
     * @param string $field
     * @return multitype:
     */
    public static function fetch_field_one($handle, $table, $condition = '', $field) {
        $condition = self::format_condition($condition);
        $condition = !empty($condition) ? " WHERE {$condition} " : '';
        $sql = "SELECT {$field} FROM `{$table}` {$condition} LIMIT 1";
        return self::getRow($handle, $table, $sql);
    }

    /**
     * 根据条件及分组获取指定记录并排序
     * @param array|string $condition
     * @param string $order
     * @param string $limit
     * @param string $group
     * @return multitype:
     */
    public static function fetch_all($handle, $table, $condition = '', $order = '', $limit = '', $group = '') {
        $condition = self::format_condition($condition);
        $condition = !empty($condition) ? " WHERE {$condition} " : '';
        $order = !empty($order) ? " ORDER BY {$order} " : '';
        $limit = !empty($limit) ? " LIMIT {$limit} " : '';
        $group = !empty($group) ? " GROUP BY {$group} " : '';
        $sql = "SELECT * FROM `{$table}` {$condition} {$group} {$order} {$limit}";
        return self::getAll($handle, $sql);
    }

    /**
     * 根据条件获取指定字段的所有记录
     * @param array|string $condition
     * @param string $fields
     * @return multitype:
     */
    public static function fetch_field_all($handle, $table, $condition = '', $fields = '*', $order = '', $limit = '', $group = '') {
        $condition = self::format_condition($condition);
        $condition = !empty($condition) ? " WHERE {$condition} " : '';
        $fields = empty($fields) ? '*' : $fields;
        $order = !empty($order) ? " ORDER BY {$order} " : '';
        $limit = !empty($limit) ? " LIMIT {$limit} " : '';
        $group = !empty($group) ? " GROUP BY {$group} " : '';
        $sql = "SELECT {$fields} FROM `{$table}` {$condition} {$group} {$order} {$limit}";
        return self::getAll($handle, $sql);
    }

    /**
     * 根据条件获取记录总数
     * @param array|string $condition
     * @return int
     */
    public static function fetch_count($handle, $table, $condition = '') {
        $condition = self::format_condition($condition);
        $condition = !empty($condition) ? " WHERE {$condition} " : '';
        $sql = "SELECT COUNT(*) AS count FROM `{$table}` {$condition}";
        return self::getOne($handle, $sql);
    }

}

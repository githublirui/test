<?php
/**
 * @Copyright (c) 2011 Ganji Inc.
 * @brief               帖子全局ID模块
 * @author              caifeng
 * @file                /code_base2/util/post_uid/PostUIDNamespace.class.php
 * @version             $Revision: $
 * @modifiedby          $Author:  $
 * @lastmodified        $Date:  $
 *
 * 封装了帖子全局ID相关的操作
 */
 
require_once(dirname(__FILE__).'/../db/DBMysqlNamespace.class.php');
require_once(dirname(__FILE__).'/../../interface/id_server/IdServerInterface.class.php');
require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/util/cache/CacheNamespace.class.php';

/**
 * for Mock , add a instance
 */
class PostUID {
    /**
     * @brief apc db key
     */
    private static $_APC_DBC_KEY = 'puiddbc_v1_';

    /**
     * @brief apc table key
     */
    private static $_APC_TBC_KEY = 'puidtbc_v1_';

    /**
     * @brief db handle
     */
    private $_dbHandleM = null;
    private $_dbHandleS = null;

    /**
     * instance method goes here
     */


    /**
     * @brief 创建一个唯一的PUID
     * @return int 新生成的puid
     *
     * 在将帖子写入到DB之前，先创建PUID
     */
    public function generateId() {
        return IdServerInterface::generateId("puid");
    }

    /**
     * @brief 将puid和帖子id写入post_index表
     * @param int puid
     * @param string 数据库名，必须是在db_config中有记录的库名，不同系统的库名不允许相同
     * @param string 表名，必须是在table_config中有记录的表名
     * @param int post_id，帖子的主键id，0 表示暂时无法获得post_id
     * @return TRUE 成功，FALSE 失败
     *
     * 在将帖子写入到DB之前，先写入index，确保关系不会丢失。
     * 系统会定期清理无效的关系。
     */
    public function insertIndex($puid, $dbName, $tableName, $postId=0) {
        $dbHandle = $this->getDBHandle(true);

        $sqlDb = "select `id` from db_config where `name`='$dbName'";
        $dbId = $dbHandle->getOne( $sqlDb );
        if( FALSE === $dbId || null == $dbId ) {
            Logger::logError("Unknown db name ($dbName) specified", 'puid');
            return FALSE;
        }

        $sqlTable = "select `id` from table_config where `name`='$tableName'";
        $tableId = $dbHandle->getOne( $sqlTable);
        if( FALSE === $tableId || null == $tableId ) {
            Logger::logError("Unknown table name ($tableName) specified", 'puid');
            return FALSE;
        }

        $table_no = str_pad( $puid % 100, 2 , '0', STR_PAD_LEFT);
        $sqlIndex = "insert into post_index_$table_no (`puid`, `db_id`, `table_id`, `post_id` ) values ( $puid, $dbId, $tableId, $postId )";

        $ret = $dbHandle->execute($sqlIndex );
        if( FALSE === $ret ) {
            Logger::logError("Insert failed, sql=$sqlIndex", 'puid');
        }

        return $ret;
    }

    /**
     * @brief 将帖子id更新到post_index表
     * @param int puid
     * @param int post_id，帖子的主键id
     * @return TRUE 成功，FALSE 失败
     *
     * 在获得帖子id后，将帖子id写入索引表
     */
    public function updateIndex($puid, $postId) {
        $dbHandle = $this->getDBHandle(true);

        $table_no = str_pad( $puid % 100, 2 , '0', STR_PAD_LEFT);
        $sqlIndex = "update post_index_$table_no set `post_id`=$postId where `puid`=$puid and `post_id`=0";

        $ret = $dbHandle->execute($sqlIndex );
        if( FALSE === $ret ) {
            Logger::logError("Update failed, sql=$sqlIndex", 'puid');
        }

        return $ret;
    }

    /**
     * @brief 物理删除时清除puid的index信息
     * @param int puid
     * @return TRUE 成功， FALSE 失败
     */
    public function deleteIndex($puid) {
        $puid = intval($puid);
        $dbHandle = $this->getDBHandle(true);

        $table_no = str_pad( $puid % 100, 2 , '0', STR_PAD_LEFT);
        $sql = "delete from post_index_{$table_no} where puid={$puid}";

        $ret = $dbHandle->execute($sql);
        if (FALSE === $ret) {
            Logger::logError("Delete puid index failed, sql={$sql}", 'puid');
        }

        return $ret;
    }


    /**
     * @brief 根据puid查找帖子存储位置
     * @param int puid
     * @return Array(
     *  'system_id' : 0 -- 主站， 1 -- 推广
     *  'db_name' : 库名
     *  'table_name' : 表名
     *  'post_id' : 帖子id，（建议不使用）
     * )
     */
    public function lookUpIndex($puid) {
        $table_no = str_pad( $puid % 100, 2 , '0', STR_PAD_LEFT);
//        $sql = "select db_config.system_id system_id, db_config.name db_name, table_config.name table_name, P.post_id post_id from post_index_$table_no P left join db_config on db_config.id=P.db_id left join table_config on table_config.id=P.table_id where `puid`=$puid";
        $sql = sprintf("select * from post_index_%s where `puid`=%d", $table_no, $puid);
        $dbHandle = $this->getDBHandle(false);
        $ret = $dbHandle->getRow( $sql );
        if( FALSE === $ret || NULL === $ret ) {
            $dbHandle = $this->getDBHandle(true);
            $ret = $dbHandle->getRow($sql);
        }
        if(is_array($ret)) {
            $dbInfo = $this->getDbInfoById($ret['db_id']);
            $tableName = $this->getTableNameById($ret['table_id']);
            if (!$dbInfo || !$tableName) {
                return null;
            }
            $puidInfo = array();
            $puidInfo['system_id']  = $dbInfo['system_id'];
            $puidInfo['db_name']    = $dbInfo['name'];
            $puidInfo['table_name'] = $tableName;
            $puidInfo['post_id']    = $ret['post_id'];
            return $puidInfo;
        }
        return null;
    }

    /**
     * @brief 获取数据库配置信息
     * @param $id
     * @return mix
     *  - false 参数错误 或者未找到
     *  - array('id', 'name', 'system_id')
     */
    public function getDbInfoById($id) {
        $id = (int) $id;
        if ($id < 0) {
            return false;
        }
        // apc缓存
        $apcKey = self::$_APC_DBC_KEY . $id;
        $cacheHandle = $this->getCacheHandle();
        $data   = $cacheHandle->read($apcKey);
        if ($data) {
            return $data;
        }
        $dbHandle = $this->getDBHandle(false);
        $sql = sprintf("select * from db_config where `id`=%d", $id);
        $ret = $dbHandle->getRow( $sql);
        if( FALSE === $ret || NULL === $ret ) {
            $dbHandle = $this->getDBHandle(true);
            $ret = $dbHandle->getRow($sql);
        }

        if (is_array($ret)) {
            $cacheHandle->write($apcKey, $ret, 7200);
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * @brief 获取表名
     * @param $id
     * @return mix
     *  - false 参数错误 或者未找到
     *  - string 表名
     */
    public function getTableNameById( $id ) {
        $id = (int) $id;
        if ($id < 0) {
            return false;
        }
        // apc缓存
        $apcKey = self::$_APC_TBC_KEY . $id;
        $cacheHandle = $this->getCacheHandle();
        $data   = $cacheHandle->read($apcKey);
        if ($data) {
            return $data;
        }
        $dbHandle = $this->getDBHandle(false);
        $sql = sprintf("select name from table_config where `id`=%d", $id);
        $ret = $dbHandle->getRow( $sql);
        if( FALSE === $ret || NULL === $ret ) {
            $dbHandle = $this->getDBHandle(true);
            $ret = $dbHandle->getRow( $sql);
        }
        if (isset($ret['name'])) {
            $cacheHandle->write($apcKey, $ret['name'], 7200);
            return $ret['name'];
        } else {
            return false;
        }
    }

    public function getDBHandle($isMaster) {
        $rep = 0;
        while( $rep++<3 ) {
            $dbHandle = $this->real_getDBHandle($isMaster);
            if( $dbHandle ) return $dbHandle;
            usleep(5*1000);
        }
        throw new Exception("connecting to db failed in PostUIDNamespace isMaster=$isMaster");
    }
    /**
     * 返回主从的DB句柄
     * @param $isMaster
     * @return DBHandle|null
     */
    protected function real_getDBHandle($isMaster) {
        if( $isMaster ) {
            if( $this->_dbHandleM === null )
                $this->_dbHandleM = DBMysqlNamespace::createDBHandle2(DBConfig::$SERVER_MS_MASTER, "puid");
            return $this->_dbHandleM;
        }
        if( $this->_dbHandleS === null )
            $this->_dbHandleS = DBMysqlNamespace::createDBHandle2(DBConfig::$SERVER_MS_SLAVE, "puid");
        return $this->_dbHandleS;
    }
    
    public function releaseDBHandle() {
        if($this->_dbHandleM) {
            $this->_dbHandleM->close();
            $this->_dbHandleM = null;
        }
        if($this->_dbHandleS) {
            $this->_dbHandleS->close();
            $this->_dbHandleS = null;
        }
    }

    /**
     * 创建缓存对象，用于Mock
     * @return ApcCacheAdapter
     */
    protected function getCacheHandle() {
        return CacheNamespace::createCache(CacheNamespace::MODE_APC);
    }
}

/**
 * the implementation has moeve to PostUID
 */
class PostUIDNamespace {

    /**
     * @brief global instance
     */
    private static $_theInstance = null;

    /**
     * @return mixed return the global instance
     */
    public static function getInst() {
        if( self::$_theInstance == null )
            self::$_theInstance = new PostUID();
        return self::$_theInstance;
    }

    public static function generateId() {
        return self::getInst()->generateId();
    }

    public static function insertIndex($puid, $dbName, $tableName, $postId=0) {
        return self::getInst()->insertIndex($puid, $dbName, $tableName, $postId);
    }

    public static function updateIndex($puid, $postId) {
        return self::getInst()->updateIndex($puid, $postId);
    }

    public static function lookUpIndex($puid) {
        return self::getInst()->lookUpIndex($puid);
    }

    public static function getDbInfoById($id) {
        return self::getInst()->getDbInfoById($id);
    }

    public static function getTableNameById($id) {
        return self::getInst()->getTableNameById($id);
    }

    public static function deleteIndex($puid) {
        return self::getInst()->deleteIndex($puid);
    }

    public static function releaseDBHandle() {
        return self::getInst()->releaseDBHandle();
    }

}

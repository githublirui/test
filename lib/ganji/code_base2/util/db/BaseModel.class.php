<?PHP
/** 
 * @Copyright (c) 2010 Ganji Inc.
 * @file          /code_base2/util/db/BaseModel.class.php
 * @author        chenchaofei
 * @date          2011-06-31
 *
 * 使用修改过的 Active Record 数据库模式。
 * 这种模式是以较少的程序代码来实现信息在数据库中的获取，插入，更改。
 * 有时只用一两行的代码就能完成对数据库的操作。
 * 最终生成SQL语句
 */


/**
 * @class: BaseModel 
 * 其中定义了若干操作生成SQL语句的方法
 */
abstract class BaseModel
{
	const MODIFIER_DEFAULT 			= "'";							///< 默认的边界符
	
	private static $_hashSet 		= array(' SET ', ', ');			///< update中set时的关键字以及分割符
	private static $_hashWhere 		= array(' WHERE ', ' AND ');	///< where时的关键字以及分割符
	private static $_noModType		= array(						///< 不用分割符号的数据类型(全大写)，如：INT
										'TINYINT',
										'SMALLINT',
										'MEDIUMINT',
										'INT',
										'BIGINT'
									);
	
	protected static $dbName 		= '';							///< 数据库名
	protected static $tableName 	= '';							///< 表名
	protected static $fieldTypes	= array();						///< 表所有的 字段=>类型 集合
	
	/**
	 * 
	 * 取得数据库名
	 * @return string
	 */
	public static function getDbName() {
		return self::$dbName;
	}
	
	/**
	 * 
	 * 取得数据表名
	 * @return string
	 */
	public static function getTableName() {
		return self::$tableName;
	}
	
	/**
	 * 
	 * 检查字段的类型，确定要不要用边界符
	 * 
	 * @param String $fieldName
	 * 
	 * @return boolean
	 */
	private static function _getFieldTypeMod($fieldName) {
		$pos 		= strrpos($fieldName, '.');
		if($pos !== false) {
			$fieldName = substr($fieldName, $pos+1);
		}
		return in_array(strtoupper(self::$fieldTypes[$fieldName]), self::$_noModType) ? '' : self::MODIFIER_DEFAULT;
	}
	
	/**
	 * 
	 * 可以获取一个表的数据。
	 * 
	 * @param String $field 字段
	 * @param String or Array $where 条件
	 * @param int $limit 结果集每页纪录数
	 * @param int $offset 结果集的偏移
	 * 
	 * @return String SQL
	 */
    public static function createSelectSql($field = '*', $where = '', $orderBy = '', $limit = 0, $offset = 0) {
    	
    	if(empty($orderBy)) {
    		$orderBy = '';
    	} else {
    		$orderBy = ' ORDER BY ' . $orderBy;
    	}
    	
    	if($limit > 0) {
    		$offset = (int) $offset;
    		$limit  = " LIMIT {$offset}, $limit";
    	} else {
    		$limit  = '';
    	}
    	
    	return 'SELECT ' . $field . ' FROM ' . self::_checkTableName() . self::createWhereSql($where) . $orderBy . $limit;
    	
    }
    
    /**
     * 数据插入
     * 
     * @param Array $data 要插入的数据
     * 
     * @return String sql
     */
    public static function createInsertSql($data) {
    	self::filterFields($data);
    	
    	$arrKeys 	= array_keys($data); 
    	$arrVals 	= array();
    	foreach ($arrKeys as $f) {
    		$mod 		= self::_checkEmptyValue($f, $data[$f]);
    		if($mod === false ) {
    			unset($arrKeys[array_search($f, $arrKeys)]);
    			continue;
    		}
    		$arrVals[]	= $mod . self::mysqlEscapeMimic($data[$f]) . $mod;
    	}
    	$field 	= implode('`, `', $arrKeys);
    	$values = implode(', ', $arrVals);
    	return 'INSERT INTO ' . self::_checkTableName() . ' (`' . $field . '`) VALUES (' . $values . ')';
    }
    
    /**
     * 数据更新
     * 
     * @param String or Array $setData 要插入的数据
     * @param String or Array $where 条件
     * 
     * @return String sql
     */
    public static function createUpdateSql($setData, $where) {
    	return 'UPDATE ' . self::_checkTableName() . self::createSetSql($setData, true) . self::createWhereSql($where);
    }
    
    /**
     * 删除数据
     * 
     * @param $where WHERE
     * 
     * @return String sql
     */
    public static function createDeleteSql($where) {
    	return "DELETE FROM " . self::_checkTableName() . self::createWhereSql($where);
    }
    
    /**
     * 
     * 生成Where数据集
     * @param String or Array $data
     */
    public static function createWhereSql($data) {
    	return self::_parseHash($data, self::$_hashWhere, false);
    }
    
    /**
     * 
     * 生成Set数据集
     * @param String or Array $data
     */
    protected static function createSetSql($data) {
    	return self::_parseHash($data, self::$_hashSet);
    }
    
    /**
     * 检查表名
     * 
     * @return String
     */
    protected static function _checkTableName() {
    	return "`" . self::$dbName . "`.`" . self::$tableName . "`";    	
    }
    
	/**
	 * 
	 * 过滤没用的字段
	 * @param Array $fields
	 */
	protected static function filterFields(&$data) {
		$fields = array_keys($data);
		foreach($fields as $f) {
			if(!array_key_exists($f, self::$fieldTypes)) {
				unset($data[$f]);
			}
		}
	}
    
    /**
     * 将数组数据生成键值对形式的字符串
     * 
     * @param String or Array $data
     * @param Array $ws Where 和 Set时的关键字和分割符
     * 
     * @return String
     */
    private static function _parseHash($data, $ws, $filter = true) {
    	    	
    	$strHash = '';
    	if(is_string($data)) {
    		$strHash = $data;
    	} else if(is_array($data)) {
    		
    		if($filter) {
    			self::filterFields($data);
    		}
    		
    		$arrHash = array();
    		foreach ($data as $k => $v) {
    			$mod 		= self::_checkEmptyValue($k, $v);
    			
    			if($filter && $mod === false) {
    				continue;
    			}
    			
    			$arrHash[] 	= self::_checkField($k) . "=" . $mod . self::mysqlEscapeMimic($v) . $mod;
    		}
    		$strHash = implode($ws[1], $arrHash);
    	}
    	return $strHash ? " {$ws[0]} {$strHash}" : ' ';
    }
    
    /**
     * 
     * 检查字段名
     * 过滤掉特殊字符（不包含.） 得到合法的字符
     * @param String $field
     */
    private static function _checkField($field) {
    	$field 	= preg_replace('/[^\w\.]/', '', $field);
    	$pos 	= strpos($field, '.');
    	if($pos > 0){
    		$field = str_replace('.', "`.`", $field);
    	} else if($pos === 0){
    		$field = substr($field, 1);
    	}
    	
    	return "`" . $field . "`";
    }
    
    /**
     * 
     * 检查不使用边界符时的数据是否合法（要为数字）
     * 
     * @param String $field 字段
     * @param String $val	字段值
     * 
     * @return boolean or char
     */
    private static function _checkEmptyValue($field, $val) {
    	$mod = self::_getFieldTypeMod($field);

    	if(empty($mod) && !preg_match('/\d+/', $val)) {
    		return false;
    	}
    	return $mod;
    }
    
	/**
	 * @breif 模拟 mysql escape， 推荐使用 mysqli_real_escape_string
	 * @param mix $inp
	 */
	public static function mysqlEscapeMimic($inp) {
        if(is_array($inp)) {
            return array_map(__METHOD__, $inp);
        }
        if(!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }
        return $inp;
    }
}

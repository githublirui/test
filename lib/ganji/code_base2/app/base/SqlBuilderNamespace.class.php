<?php
/**
 * SQL语句Builder类，内含SQL语句的生成以及外部变量的安全过滤
 */
class SqlBuilderNamespace
{
	/**
	 * 操作类型
	 */
	const SELECT_OPT = 'SELECT';
	const INSERT_OPT = 'INSERT INTO';
	const UPDATE_OPT = 'UPDATE';
	const DELETE_OPT = 'DELETE';

	/**
	 * filter类型
	 */
	const FILTER_EQUAL        = '=';
	const FILTER_UNEQUAL      = '<>';
	const FILTER_LESS_THAN    = '<';
	const FILTER_LESS_EQUAL   = '<=';
	const FILTER_LARGER       = '>';
	const FILTER_LARGER_EQUAL = '>=';
	const FILTER_IN           = 'IN';
	const FILTER_NOT_IN       = 'NOT IN';
	const FILTER_LIKE         = 'LIKE';

	/**
	 * order类型
	 */
	const SORT_ASC  = 'ASC';
	const SORT_DESC = 'DESC';

	/**
	 * 选择通配符的模式
	 */
	const WILDCARD_LEFT  = 0; // 通配符放到左侧,形如：%keyword
	const WILDCARD_RIGHT = 1; // 通配符放到右侧,形如：keyword%
	const WILDCARD_BOTH  = 2; // 通配符放到两侧,形如：%keyword%

	/** {{{ buildInsertSql
	 * 生成Insert语句
	 * @param $tableName string 数据表名称
	 * @param $columnsAndValues array [column1=>value1,column2=>value2,...] 需要插入的字段名称及要插入的值
	 * @return string 返回Insert语句
	 */
	public static function buildInsertSql($tableName, $columnsAndValues, $isDelay = false)
	{
		if (!$tableName && !is_array($columnsAndValues) && empty($columnsAndValues))
			return '';

		$columns = array_keys($columnsAndValues);
		$values  = self::safeFilter(array_values($columnsAndValues));
		array_walk($values, "SqlBuilderNamespace::addSingleQuote");

		$sql = self::INSERT_OPT . " `{$tableName}` ( ";
		$sql .= implode(",", $columns);
		$sql .= " ) values ( ";
		$sql .= implode(",", $values);
		$sql .= " )";

		return $sql;
	}//}}}

	/** {{{ buildUpdateSql
	 * 生成Update语句
	 * @param $tableName string 数据表名称
	 * @param $columnsAndValues array [column1=>value1,column2=>value2,...] 需要更新的字段名称及要更新的值
	 * @param $filter array，[column, filter, value] 需要过滤的字段名称、操作类型以及阈值
	 * @return string 返回Update语句
	 */
	public static function buildUpdateSql($tableName, $columnsAndValues, $filters = array())
	{
		if (!$tableName && !is_array($columnsAndValues) && empty($columnsAndValues)
				&& !is_array($filters))
			return '';

		// 更新ad_types位操作
		$adTypeStatus = array();
		if (isset($columnsAndValues['ad_types']) && is_array($columnsAndValues['ad_types'])) {
		    $adTypeStatus['ad_types'] = $columnsAndValues['ad_types'];
		    unset($columnsAndValues['ad_types']);
		}

		// 更新ad_status位操作
		if (isset($columnsAndValues['ad_status']) && is_array($columnsAndValues['ad_status'])) {
		    $adTypeStatus['ad_status'] = $columnsAndValues['ad_status'];
		    unset($columnsAndValues['ad_status']);
		}

		$sql = self::UPDATE_OPT . " `{$tableName}` set ";
		$columnsAndValues = self::safeFilter($columnsAndValues);

		/**
		 * 拼接set部分
		 */
		array_walk($columnsAndValues, "SqlBuilderNamespace::addSingleQuote");
		$sqlSetSegment = array();
		foreach($columnsAndValues as $column => $value)
		{
			$sqlSetSegment[] = "{$column}={$value}";
		}

        if ($adTypeStatus) {
            include_once CODE_BASE2 . '/app/adtype/include/AdTypeVars.class.php';
            foreach ($adTypeStatus as $field => $dos) {
                $adSqlRem = '';
                $adSqlAdd = '';
                foreach ($dos as $bin => $do) {
                    if ($do) {
                        $adSqlAdd .= '|'.$bin;
                    } else {
                        $adSqlRem .= '&~'.$bin;
                    }
                }
                $sqlSetSegment[] = sprintf(' `%s` = ((`%s`%s)%s) ', $field, $field, $adSqlRem, $adSqlAdd);
//                if ($field == 'ad_types' && !isset($columnsAndValues['post_type'])) {
//                    $sqlSetSegment[] = sprintf(' `post_type` = %d ', $do?AdTypeVars::getPostType($bin):0);
//                }
            }
        }

		$sql .= implode(", ", $sqlSetSegment);

		/**
		 * 拼接filter部分
		 */
		if(!empty($filters))
		{
    		$filters = self::safeFilter($filters);
			$sqlFilterSegment = self::buildFilterSegment($filters);
			$sql .= $sqlFilterSegment;
		}
		return $sql;
	}//}}}

	/** {{{ buildDeleteSql
	 * 生成Delete语句
	 * @param $tableName string 数据表名称
	 * @param $filter array，[column, filter, value] 需要过滤的字段名称、操作类型以及阈值
	 * @return string 返回Delete语句
	 */
	public static function buildDeleteSql($tableName, $filters)
	{
		if(!$tableName && !is_array($filters))
			return '';

		$sql = self::DELETE_OPT . " from `{$tableName}` ";

		/**
		 * 拼接filter部分
		 */
		if(!empty($filters))
		{
		    $filters = self::safeFilter($filters);
			$sqlFilterSegment = self::buildFilterSegment($filters);
			$sql .= $sqlFilterSegment;
		}

		return $sql;
	}//}}}

    /** {{{ buildSelectSql
	 * 生成Select语句
	 * @param $tableName string 数据表名称
	 * @param $columns array [column1,column2,...] 需要检索的字段名称
	 * @param $filter array，[column, filter, value] 需要过滤的字段名称、操作类型以及阈值
	 * @param $offsetLimit array [offset,limit],如果数组仅包含一个值，那么将其视为limit
	 * @param $orders array [column1->ASC,column2->DESC,...] 需要排序的字段名称及排序类型
	 * @param $groupBy array [column1,column2,...] 需要分组的字段名称
	 * @return string 返回Select语句
	 */
	public static function buildSelectSql($tableName, $columns = "*",
			$filters = array(), $offsetLimit = array(), $orders = '', $groupBy = '', $useIndex = '')
	{
		if(!$tableName)
			return '';

		/**
		 * 处理columns部分
		 */
		if(is_array($columns))
		{
			//$columns = self::safeFilter($columns);
			$columns = implode(",", $columns);
		}

		$sql = self::SELECT_OPT . " {$columns} from `{$tableName}` ";
		if ($useIndex) {
		    $sql .= " use index({$useIndex}) ";
		}
		/**
		 * 拼接filter部分
		 */
		if(!empty($filters))
		{
			$filters = self::safeFilter($filters);
			$sqlFilterSegment = self::buildFilterSegment($filters);
			$sql .= $sqlFilterSegment;
		}

		/**
		 * 处理groupby部分
		 */
		if(!empty($groupBy))
		{
			$sqlGroupBySegment = " group by ";
			//$groupBy = self::safeFilter($groupBy);
			if(is_array($groupBy))
				$sqlGroupBySegment .= implode(",", $groupBy);
			else
				$sqlGroupBySegment .= $groupBy;

			$sql .= $sqlGroupBySegment;
		}

		/**
		 * 处理orders部分
		 */
		if(is_array($orders) && !empty($orders))
		{
			//$orders = self::safeFilter($orders);
			$sqlOrderSegment = " order by ";
			$temp = array();
			foreach($orders as $column => $sort)
			{
				$temp[] = "{$column} {$sort}";
			}
			$sqlOrderSegment .= implode(",", $temp);

			$sql .= $sqlOrderSegment;
		}

		/**
		 * 处理offset,limit部分
		 */
		if(is_array($offsetLimit) && !empty($offsetLimit))
		{
			$count = count($offsetLimit);
			if(1 == $count)
				$sql .= " limit {$offsetLimit[0]}";
			else if(2 == $count)
				$sql .= " limit {$offsetLimit[0]} , {$offsetLimit[1]}";
		}

		return $sql;
	}//}}}

	/** {{{ buildFilterSegment
	 * 拼接filter部分语句
	 * @param $filters array [column, filter, value]
	 *        如果filter是in,value是一个array[value1,value2,value3,...]
	 *        如果filter是like,value是一个array[value,pattern]
	 *	      pattern: self::WILDCARD_LEFT,WILDCARD_RIGHT,WILDCARD_BOTH
	 * @return $sqlFilterSegment string 返回filter部分语句
	 */
	public static function buildFilterSegment($filters)
	{
		if(empty($filters))
			return '';
		$sqlFilterSegment = array();
		$countFilters = count($filters);
		for($i = 0; $i < $countFilters; $i++)
		{
			if($filters[$i][0])
			{
				if(self::FILTER_IN == $filters[$i][1])
				{
					if(is_array($filters[$i][2]))
					{
						$sqlFilterSegment[] = "{$filters[$i][0]} ".
							self::FILTER_IN . " ( '" . implode("','", $filters[$i][2]) . "' ) ";
					}

				}
				else if(self::FILTER_NOT_IN == $filters[$i][1])
				{
					if(is_array($filters[$i][2]))
					{
						$sqlFilterSegment[] = "{$filters[$i][0]} ".
							self::FILTER_NOT_IN . " ( '" . implode("','", $filters[$i][2]) . "' ) ";
					}
				}
				else if(self::FILTER_LIKE == $filters[$i][1])
				{
					if(is_array($filters[$i][2]))
					{
						$keyword = "";
						switch($filters[$i][2][1])
						{
							case self::WILDCARD_LEFT:
								$keyword = "'%{$filters[$i][2][0]}'";
								break;
							case self::WILDCARD_RIGHT:
								$keyword = "'{$filters[$i][2][0]}%'";
								break;
							case self::WILDCARD_BOTH:
								$keyword = "'%{$filters[$i][2][0]}%'";
								break;
						}
						if($keyword)
						{
							$sqlFilterSegment[] = "{$filters[$i][0]} ".
								self::FILTER_LIKE . " {$keyword}";
						}
					}
				}
				else
				{
					$sqlFilterSegment[] = "{$filters[$i][0]} {$filters[$i][1]} '{$filters[$i][2]}' ";
				}
			}
		}

		$sql = " where " . implode(" and ",$sqlFilterSegment);
		return $sql;
	}//}}}

	/** {{{ safeFilter
	 * 对前端输入的内容进行过滤
	 * @param $content 可以是一个array，也可以是一个scalar
	 * @return 返回过滤后的结果
	 */
	public static function safeFilter($content)
	{
		if(empty($content))
			return $content;
		if(is_array($content))
		{
			$temp = array();
			foreach($content as $key => $value)
			{
				$key   = self::safeFilter($key);
				$value = self::safeFilter($value);
				$temp[$key] = $value;
			}
			return $temp;
		}
		else if(is_scalar($content))
		{
			return self::mysql_escape_mimic($content);
		}
	}//}}}
	
	/**
	 * @breif 模拟 mysql escape
	 * @param mix $inp
	 */
	public static function mysql_escape_mimic($inp) {
        if(is_array($inp)) {
            return array_map(__METHOD__, $inp);
        }
        if(!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }
        return $inp;
    }

	/** {{{ hasSQLMethod
	 * 检测是否含有SQL函数
	 * 方便前端的输入，采用简陋的方法来检查
	 */
	public static function hasSQLMethod($value)
	{
		if(empty($value))
			return false;
		$sqlMethods = array("now(","md5(","unix_timestamp(","date(");
		foreach($sqlMethods as $sqlMethod)
		{
			if(false !== strpos($value, strtoupper($sqlMethod)))
				return true;
		}

		return false;
	}//}}}

	/** {{{ addSingleQuote
	 * 增加单引号
	 */
	private static function addSingleQuote(&$value)
	{
		if(!self::hasSQLMethod($value))
			$value = "'" . $value . "'";
	}//}}}
}

?>

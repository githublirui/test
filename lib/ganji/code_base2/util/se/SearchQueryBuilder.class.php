<?php
/**
 * Search Query Builder
 * @copyright (c) 2011 Ganji Inc.
 * @file      SearchQueryBuilder.class.php
 * @author    zhoufan <zhoufan@ganji.com>
 * @date      2011-5-13
 */

class SearchQueryBuilder {
    /**
     * 等于操作
     * @var int
     */
    const FILTER_TYPE_EQUAL = 1;

    /**
     * 范围查询
     * @var int
     */
    const FILTER_TYPE_BETWEEN   = 2;

    /**
     * 文本查询
     * @var int
     */
    const FILTER_TYPE_TEXT  = 3;

    /**
     * in查询
     * @var int
     */
    const FILTER_TYPE_IN    = 4;
    /**
     * not in 查询
     * @var int
     **/
    const FILTER_TYPE_NOT_IN    = 5;
    /**
     * and 查询
     * @var int
     **/
    const FILTER_TYPE_AND   = 6;
    /**
     * 升序
     * @var int
     */
    const ORDER_TYPE_ASC    = 1;

    /**
     * 降序
     * @var int
     */
    const ORDER_TYPE_DESC   = 2;

    // 需要查询的帖子字段
    protected $field_list     = '*';

    // 数据库查询参数
    protected $filter_list    = array();

    protected $order_by   = array();
    protected $groupBy   = null;

    protected $limit  = array();

    // 查询关键字
	public $strKeyword = "";
    protected $keywords;

    protected $specialWords = array("{","}","[","]","(",")","<",">","&","|",":");

    /**
     * 设置查询字段名
     * @param array $field_list
     */
    public function setFields($field_list) {
        $this->field_list   = $field_list;
    }

    /**
     * 设置过滤条件
     * @param string $key
     * @param mixed $value
     * @param int $type
     */
    private function setFilter($key, $value, $type) {
        if (is_null($value))    return false;
        $this->filter_list[$key] = array(
            'key'   => $key,
            'value' => $this->stripSpecialWords($value),
            'type'  => $type,
        );
    }

    /**
     * 等于查询
     * @param string $key
     * @param string|int $value
     */
    public function setEqualFilter($key, $value) {
        if (is_null($value))    return false;
        $this->setFilter($key, $value, self::FILTER_TYPE_EQUAL);
    }

    /**
     * 范围查询
     * @param string $key
     * @param array $value array(起始值,结束值)
     */
    public function setBetweenFilter($key, $value) {
        if (empty($value))    return false;
        $this->setFilter($key, $value, self::FILTER_TYPE_BETWEEN);
    }

    public function setCharFilter($key, $value ){
        $this->strKeyword = "[T:".$key.":".$value."]";

    }

    /**
     * 设置文本查询
     * @param $keyword
     * @param $filters array
     * @code
     *  $filters结构：
     *      array(1) {
     *          [0]=>
     *              array(3) {
     *                  ["pinyin_prefix"] => string(1) "b"
     *                  ["name"] => string(6) "柏林"
     *              }
     *          [1] => ...
     *      }
     *  0 => and 条件
     *  1 => or  或
     * @endcode
     */
    public function setTextFilter($keyword, $filters) {
        $keyword = $this->stripSpecialWords($keyword);
        //不要超过35个汉字
        $keyword = @mb_substr($keyword, 0, 35, 'utf-8');

        //列表页广告用
        $this->textFilterData = array($keyword, $filters);
        $this->strKeyword = '';
        if($keyword){
           $this->strKeyword = "[T:TT:".$keyword."]";
        }

        //拼接"与"过滤条件
        $strAndFilter = '';
        if(!empty($filters[0]) && is_array($filters[0])){
            foreach($filters[0] as $field => $value){
                $value  = $this->stripSpecialWords($value);
                $strAndFilter .= "[T:".$field.":".$value."]&";
            }
            $strAndFilter = rtrim($strAndFilter,'&');
        }

        // 拼接"或"过滤条件
        $strOrFilter = '';
        if(!empty($filters[1]) && is_array($filters[1])){
            foreach($filters[1] as $field => $value){
                $value  = $this->stripSpecialWords($value);
                $strOrFilter .= "[T:".$field.":".$value."]|";
            }
            $strOrFilter = rtrim($strOrFilter,'|');
        }

        //拼接经纬度特殊查询，N公里以外,此部分只支持经纬度，不支持其他
        $latlngFilter = '';
        if( !empty($filters[2]) &&  is_array($filters[2]) ) {
            foreach($filters[2] as $field => $value){
                if($field == "latlng") {
                    $value  = $this->stripSpecialWords($value);
                    $latlngFilter .= "[NT:".$field.":".$value."]&";
                }
            }
            $latlngFilter = rtrim($latlngFilter,'&');
        }

        if($strAndFilter){
            if($this->strKeyword){
                $this->strKeyword .= "&";
            }
            $this->strKeyword .= "(".$strAndFilter.")";
        }

        if($strOrFilter){
            if($this->strKeyword){
                $this->strKeyword .= "&";
            }
            $this->strKeyword .= "(".$strOrFilter.")";
        }

        if(isset($latlngFilter)&& $latlngFilter) {
            if($this->strKeyword){
                $this->strKeyword .= "&";
            }
            $this->strKeyword .= "(".$latlngFilter.")";
        }
    }

    /**
     * 设置in查询
     * @param string $key
     * @param array $value array(1,3,6)
     */
    public function setInFilter($key, $value) {
        if (empty($value))    return false;
        $this->setFilter($key, $value, self::FILTER_TYPE_IN);
    }

    /**
     * 设置and查询
     * @param string $key
     * @param array $value array(1,3,6)
     */
    public function setAndFilter($key, $value) {
        if (empty($value))    return false;
        $this->setFilter($key, $value, self::FILTER_TYPE_AND);
    }

   /**
     * 设置not in查询
     * @param string $key
     * @param array $value array(1,3,6)
     */
    public function setNotInFilter($key, $value) {
        if (empty($value))    return false;

        $this->setFilter($key, $value, self::FILTER_TYPE_NOT_IN);
    }

    /**
     * 删除某一个查询条件
     * @param stirng $key 查询条件
     */
    public function removeFilter($key) {
        unset($this->filter_list[$key]);
    }

    /**
     * 设置排序规则
     * @param string $key
     * @param int $type
     */
    private function setOrderBy($key, $type) {
        $this->order_by[$key]   = array(
            'key'   => $key,
            'type'  => $type,
        );
    }

    /**
     * 移除某个字段的排序
     *
     * @param string $key 字段
     */
    public function removeOrderBy($key) {
        if (isset($this->order_by[$key])) {
            unset($this->order_by[$key]);
        }
    }

    /**
     * 设置升序排序
     * @param string $key
     */
    public function setAscOrderBy($key) {
        $this->setOrderBy($key, self::ORDER_TYPE_ASC);
    }

    /**
     * 设置降序排序
     * @param string $key
     */
    public function setDescOrderBy($key) {
        $this->setOrderBy($key, self::ORDER_TYPE_DESC);
    }

    /**
     * 设置偏移量
     * @param int $offset
     * @param int $limit
     */
    public function setLimit($offset, $limit) {
        $this->limit    = array(
            'offset'    => $offset,
            'limit'     => $limit,
        );
    }
    /* {{{ setGroupBy */
    /**
     * @brief 设置分组字段
     *
     * @param $key
     *
     * @returns  boolean
     */
    public function setGroupBy($key){
        $this->groupBy = $key;
        return true;
    }//}}}

    /**
     * @brief 获取过滤条件
     * @param string $key
     * @return array
     *  - string key
     *  - mixed value
     *  - int type @see SearchQueryBuilder::FILTER_TYPE_*
     */
    public function getFilter($key) {
        return $this->filter_list[$key];
    }

    /**
     * @brief 获取所有过滤条件
     * @return array
     */
    public function getFilterList() {
        return $this->filter_list;
    }

    public function getFieldList() {
        return $this->field_list;
    }

    /**
     * 得到当前平台
     */
    public function getPlatform() {
        $platformField = $this->getFilter('platform');
        if ($platformField) {
            return $platformField['value'];
        }
        return SearchQueryPlatform::getCode();
    }

    public function getCharFilter() {
        return $this->strKeyword;
    }
    /**
     * 获得查询字符串
     * @return string
     */
    public function getQueryString() {
        //文本查询条件 为空时需要输出{}
        $query_string = "{".$this->strKeyword."}";
        $categoryFilter = $this->getFilter('category');
        if ($categoryFilter && SearchQueryPlatform::isSupportPlatform($categoryFilter['value'])) {
            $this->setEqualFilter('platform', $this->getPlatform());
        }
        // filter_list
        $tmp    = array();
        $prefixWhere = $middleWhere = $suffixWhere = $whereStr = $lastWhere = '';
        foreach ($this->filter_list as $filter) {
            switch ($filter['type']) {
                case self::FILTER_TYPE_EQUAL :
                    $prefixWhere  .= "&[F:{$filter['key']}:{$filter['value']}]";
                    break;
                case self::FILTER_TYPE_BETWEEN :
                    $suffixWhere  .= "&[N:{$filter['key']}:" . implode(':', $filter['value']) . ']';
                    break;
                case self::FILTER_TYPE_IN :
                    $prefixWhere  .= "&[F:{$filter['key']}:<" . implode(',', $filter['value']) . '>]';
                    break;
                case self::FILTER_TYPE_NOT_IN :
                    //一个值时不用< >符号
                    if (count($filter['value']) == 1) {
                        $lastWhere  .= "&[FN:{$filter['key']}:" . implode(',', $filter['value']) . ']';
                    } else {
                        $lastWhere  .= "&[FN:{$filter['key']}:<" . implode(',', $filter['value']) . '>]';
                    }
                    break;
                case self::FILTER_TYPE_AND :
                    $prefixWhere  .= "&[FA:{$filter['key']}:<" . implode(',', $filter['value']) . '>]';
                    break;
            }
        }
        $whereStr = ltrim($prefixWhere.$middleWhere.$suffixWhere. $lastWhere, '&');
        $query_string   .= '{' . $whereStr . '}';

        //查询字段
        $tmp    = array();
        if($this->field_list || $this->order_by || $this->limit){
            if ($this->field_list) {
                $tmp[]  = "[QF:<" . implode(',', $this->field_list) . ">]";
            }

            foreach ((array) $this->order_by as $order_by) {
                switch ($order_by['type']) {
                    case self::ORDER_TYPE_ASC:
                        $tmp[]  = "[S:{$order_by['key']}:ASC]";
                        break;
                    case self::ORDER_TYPE_DESC:
                        $tmp[]  = "[S:{$order_by['key']}:DESC]";
                        break;
                }
            }

            if ($this->groupBy !== null) {
                $tmp[] = "[GB:{$this->groupBy}]";
            }

            if ($this->limit) {
                $tmp[]  = '[L:' . implode(':', $this->limit) . ']';
            }
        }
        if (!empty($tmp)) {
            $query_string   .= '{' . implode('', $tmp) . '}';
        }
        //MSC-1457 增加client来源字符串
        if (defined('PLATFORM_CODE')) {
            $query_string   .= '[' . PLATFORM_CODE . ']';
        }
        return $query_string;
    }

    // stripSpecialWord
    public function stripSpecialWords($data){
       if(is_array($data)){
           foreach ($data as &$value){
               $value = $this->stripSpecialWords($value);
           }
       }else{
           $data = str_replace($this->specialWords,'',$data);
       }
       return $data;
    }

}
/**
 * xapian里category对应真实category的scriptIndex
 *  - findjob 求职简历:5
 *  - fulltimewanted 全职招聘:4
 *  - parttimewanted 兼职招聘:12
 *  - houserent fang1、fang3普通帖:201
 *  - housesell fang5:205
 *  - hprent fang1、fang3端口贴:202
 *  - hpmisc fang6、fang7、fang8、fang9 fang11端口贴混合:1002
 *  - hsmisc fang2、fang4、fang6、fang7、fang8、fang9、fang10、fang11普通帖混合:1008
 *  - hsnew fang12:212
 *  - jingjia 房1 3 5竞价:1001
 *  - xiaoqu （小区）:230
 *  - guazi 瓜子:107
 *  - jiaoyou 交友:103
 *  - lodgeunit 蚂蚁短租:104
 *  - huodong 活动:7
 *  - personals 老交友:110
 *  - pinche 拼车:109
 *  - reward 悬赏:108
 *  - ticketing 票务:14
 *  - training 教育培训:9
 *  - bservice 大服务:100
 *  - managerbservice 大服务后台管理:101
 *  - serviceproduct 服务商品:111
 *  - serviceshop 服务（银行等）:102
 *  - country_vehicle（二手车全国搜索）:1011
 *  - pet 宠物:15
 *  - secondmarket 二手物品:1
 *  - vehicle 车辆买卖:11
 *  - allpost 全站:1000
 *  - editall 编辑后台:116
 *  - Observer sender 192.168.117.19
 *  - editall_for_editdel 编辑后台修改删除:106
 */
class SearchQueryPlatform {
    const WEB       = 1;// 主站
    const WAP       = 2;// wap
    const APP       = 2;// app 和wap相同

    private static $codeList = array(
        'web' => self::WEB,
        'wap' => self::WAP,
        'mob' => self::APP,
    );

    public static function getCode() {
        if (defined('PLATFORM_CODE') && isset(self::$codeList[PLATFORM_CODE])) {
            return self::$codeList[PLATFORM_CODE];
        }
        return null;
    }

    public static function isSupportPlatform($category) {
        $category = (int) $category;
        return in_array($category, array(
                4,      // 全职招聘
                15,     // 宠物
                11,     // 车辆
                12,     // 兼职招聘
                100,    // 大服务
        ));
    }
}

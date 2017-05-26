<?php
/**
 * 分页类
 * @author zhanglei <zhanglei30@staff.weibo.com>
 * @date 2015-05-26 17:30
*/
 
class Pager
{

	private static $first_row;					//起始行数

	private static $list_rows;					//列表每页显示行数
	 
	private static $total_pages;				//总页数

	private static $total_rows;				//总行数
	 
	private static $now_page;					//当前页数
	 
	private static $method		= 'defalut';	//处理情况 Ajax分页 Html分页(静态化时) 普通get方式 
	 
	private static $parameter	= '';
	 
	private static $page_name;					//分页参数的名称
	 
	private static $ajax_func_name;
	 
	private static $plus		= 3;			//分页偏移量
	 
	private static $url;
     
     
    /**
     * 构造函数
     * @param unknown_type $data
     */
    public function __construct($data = array())
	{

        self::$total_rows = $data['total_rows'];
 
        self::$parameter		= !empty($data['parameter']) ? $data['parameter'] : '';
        self::$list_rows		= !empty($data['list_rows']) && $data['list_rows'] <= 100 ? $data['list_rows'] : 15;
        self::$total_pages		= ceil(self::$total_rows / self::$list_rows);
        self::$page_name		= !empty($data['page_name']) ? $data['page_name'] : 'p';
        self::$ajax_func_name	= !empty($data['ajax_func_name']) ? $data['ajax_func_name'] : '';
         
        self::$method			= !empty($data['method']) ? $data['method'] : '';
         
         
		/* 当前页面 */
		if(!empty($data['now_page']))
		{
			self::$now_page = intval($data['now_page']);
		}
		else
		{
			self::$now_page = !empty($_GET[self::$page_name]) ? intval($_GET[self::$page_name]) : 1;
		}
		self::$now_page = self::$now_page <= 0 ? 1 : self::$now_page;


		if(!empty(self::$total_pages) && self::$now_page > self::$total_pages)
		{
			self::$now_page = self::$total_pages;
		}
		self::$first_row = self::$list_rows * (self::$now_page - 1);
    }   
     
    /**
     * 得到当前连接
     * @param $page
     * @param $text
     * @return string
     */
	private static function _get_link($page, $text)
	{
		switch (self::$method) {
			case 'ajax':
				$parameter = '';
				if(self::$parameter)
				{
					$parameter = ',' . self::$parameter;
				}
					return '<a onclick="' . self::$ajax_func_name . '(\'' . $page . '\''.$parameter.')" href="javascript:void(0)">' . $text . '</a>' . "\n";
			break;

			case 'html':
				$url = str_replace('#', $page, self::$parameter);
				return '<a href="' .$url . '">' . $text . '</a>' . "\n";
			break;

			default:
				return '<a href="' . self::_get_url($page) . '">' . $text . '</a>' . "\n";
			break;
		}
	}
     
     
    /**
     * 设置当前页面链接
     */
    private static function _set_url()
    {
		$url	= $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '' : "?") . self::$parameter;
		$parse	= parse_url($url);
		if(isset($parse['query']))
		{
			parse_str($parse['query'], $params);
			unset($params[self::$page_name]);
			$url = $parse['path'] . '?' . http_build_query($params);
		}
		if(!empty($params))
		{
			$url .= '&';
		}
		self::$url = $url;
    }
     
    /**
     * 得到$page的url
     * @param $page 页面
     * @return string
     */
    private static function _get_url($page)
    {
        if(self::$url === null)
        {
            self::_set_url();   
        }
        return self::$url . self::$page_name . '=' . $page;
    }
     
     
    /**
     * 得到第一页
     * @return string
     */
    private static function first_page($name = '第一页')
    {
        if(self::$now_page > 5)
        {
            return self::_get_link('1', $name);
        }   
        return '';
    }
     
    /**
     * 最后一页
     * @param $name
     * @return string
     */
    private static function last_page($name = '最后一页')
    {
        if(self::$now_page < self::$total_pages - 5)
        {
            return self::_get_link(self::$total_pages, $name);
        }   
        return '';
    }  
     
    /**
     * 上一页
     * @return string
     */
    private static function up_page($name = '上一页')
    {
        if(self::$now_page != 1)
        {
            return self::_get_link(self::$now_page - 1, $name);
        }
        return '';
    }
     
    /**
     * 下一页
     * @return string
     */
    private static function down_page($name = '下一页')
    {
        if(self::$now_page < self::$total_pages)
        {
            return self::_get_link(self::$now_page + 1, $name);
        }
        return '';
    }

     
    public static function show()
    {
        $plus = self::$plus;
        if( $plus + self::$now_page > self::$total_pages)
        {
            $begin = self::$total_pages - $plus * 2;
        }else{
            $begin = self::$now_page - $plus;
        }
         
        $begin = ($begin >= 1) ? $begin : 1;
        $return = '';
        $return .= self::first_page();
        $return .= self::up_page();
        for ($i = $begin; $i <= $begin + $plus * 2; $i++)
        {
            if ($i > self::$total_pages)
            {
                break;
            }
            if($i == self::$now_page)
            {
                $return .= "<a class='now_page'>$i</a>\n";
            }
            else
            {
                $return .= self::_get_link($i, $i) . "\n";
            }
        }
        $return .= self::down_page();
        $return .= self::last_page();
        return $return;
    }

}
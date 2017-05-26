<?php
/**
 * @author zhanglei19881228@sina.com
 * @brief  内容分页类
 * @github github.com/coderookie
 * @time   2014-05-28 15:15:00
 */
class Content2Pager{

	private static $class = null;

	// 分页的href
	private $url;

	// 内容分割符号
	private $cut;

	// 内容分页总页数
	private $pageCount;

	// 需要显示分页的个数, 奇数最好
	private $pageShow = 3;

	// 页码样式
	private $pagerTpl = "<a href=\'%s\' %s>%s</a>";


	private function __construct($url, $cut){
		$this->url = $url;
		$this->cut = $cut;
	}

	/*
	 * @params $content 需要分页的内容
	 * @brief  处理内容, 将内容分好页返回
	 * @return array(
	 *				0 => array(
	 *					'content' => '内容1',
	 *					'pager'   => '第一页的分页html'
	 *				),
	 *				1 => array(
	 *					'content' => '内容2',
	 *					'pager'   => '第二页的分页html'
	 *				),
	 *			)
	 */
	public function ContentToPager($content){
		if(empty($content)){
			return false;
		}
		$content_array = explode($this->cut, $content);
		if(empty($content_array)){
			return false;
		}
		$this->pageCount = count($content_array);
		$returns = array();
		for($i = 0; $i < $this->pageCount; $i++){
			$pager = $this->getPager($i + 1);
			$returns[$i + 1] = array('content' => $content_array[$i], 'pager' => $pager);
		}
		return $returns;
	}
	
	/**
	 * @params $current_page 处理到当前页面的页码
	 * @brief  通过页码, 处理分页样式, 返回
	 * @return 返回不同页码时的分页html
	 */
	private function getPager($current_page){
		$pager_html = "";

		// 总共就一页, 则不显示分页
		if($this->pageCount == 1){
			return $pager_html;
		}
		
		// 总共的页数小于设定的显示页数($this->pageShow), 则全部显示
		if($this->pageCount <= $this->pageShow){
			for($i = 1; $i <= $this->pageCount; $i++){
				$per_page_html = $this->getPageHtml($i, $current_page);
				$pager_html .= $per_page_html;
			}
		}else{
			// 总共的页数大于设定的显示页面($this->pageShow), 则按照一定规则显示
			if($current_page < $this->pageShow){
				// 当前页是最前$this->pageShow页中的一页
				for($i = 1; $i <= $this->pageShow; $i++){
					$per_page_html = $this->getPageHtml($i, $current_page);
					$pager_html .= $per_page_html;
				}
				$pager_html = $this->decoratePager($pager_html, 1);
			}elseif($current_page > ($this->pageCount - $this->pageShow)){
				// 当前页是最后$this->pageShow页中的一页
				for($i = $this->pageCount - $this->pageShow + 1; $i <= $this->pageCount; $i++){
					$per_page_html = $this->getPageHtml($i, $current_page);
					$pager_html .= $per_page_html;
				}
				$pager_html = $this->decoratePager($pager_html, 2);
			}else{
				// 当前页第$this->pageShow页到后$this->pageShow页中间的一页
				$start_page = $current_page - floor($this->pageShow / 2);
				$end_page   = $current_page + floor($this->pageShow / 2);
				for($i = $start_page; $i <= $end_page; $i++){
					$per_page_html = $this->getPageHtml($i, $current_page);
					$pager_html .= $per_page_html;
				}
				$pager_html = $this->decoratePager($pager_html, 3);
			}
		}
		return $pager_html;
	}

	/**
	 * @params $page 页码, $current_page 当前页码
	 * @brief  将页码转化成html格式, 并将当前页码加上特殊标示
	 * @return 返回页码的html格式 return <a href='www.lning.com/news/id/1/page/1' />1</a>
	 */
	private function getPageHtml($page, $current_page){
		if($page == $current_page){
			// 当前页
			$pager = sprintf($this->pagerTpl, "%s", "class = \'current_page\'", "%u");
		}else{
			$pager = sprintf($this->pagerTpl, "%s", "", "%u");
		}
		return sprintf(isset($pager) ? $pager : $this->pagerTpl, $this->url . "&page=" . $page, $page);
	}

	/*
	 * @params $pager 页面html $type 类型
	 *     type = 1 最前$this->pageShow页中的一页
	 *     type = 2 最后$this->pageShow页中的一页
	 *     type = 3 第$this->pageShow页到倒数第$this->pageShow页中的一页
	 * @brief  点缀页面html样式
	 * @return
	 *     type = 1 return 1 2 3 4 5 ... 10
	 *     type = 2 return 1 ... 6 7 8 9 10
	 *     type = 3 return 1 ... 2 3 4 5 6 7 8 ... 10
	 */
	private function decoratePager($pager, $type = 1){

		$lastest_page  = "..." . sprintf($this->pagerTpl, $this->url . "&page=" . $this->pageCount, "", $this->pageCount);
		$firstest_page = sprintf($this->pagerTpl, $this->url . "&page=1", "", 1) . "...";
		
		switch($type){
			case 1:
				// 当前页如果是最前$this->pageShow页其中一页, 则显示 1 2 3 4 5 ... 最后一页
				$pager = $pager . $lastest_page;
				break;
			case 2: 
				// 当前页如果是最后$this->pageShow页其中一页, 则显示 1 ... 6 7 8 9 10
				$pager = $firstest_page . $pager;
				break;
			default:
				$pager = $firstest_page . $pager . $lastest_page;
				break;
		}
		return htmlspecialchars($pager);
	}

	public static function getInstance($url, $cut = '@@cut@@'){
		if(self::$class === null){
			self::$class = new Content2Pager($url, $cut);
		}
		return self::$class;
	}

}
?>
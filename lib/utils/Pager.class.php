<?php

/**
 * 分页类
 *
 * @author gengxl <gengxl@51talk.com>
 */
class Pager {

    protected $total = 0; #总记录数
    protected $pageSize = 20; #每页显示的记录数
    protected $page = 1; #当前页数
    protected $link = ''; #跳转页面的地址  http://www.51talk.com/page={page}  程序会自动寻找{page}并替换成 
    protected $totalPage = 0; #总共多少页
    protected $limit = 9; #最多显示多少分页项

    public function __construct($param = array()) {
        foreach ((array) $param as $key => $value) {
            isset($this->$key) && $this->$key = $value;
        }
        $this->page = max(intval($this->page), 1);
        $this->pageSize = max(intval($this->pageSize), 1);
        $this->totalPage = ceil($this->total / $this->pageSize);
    }

    /**
     * 获取分页html代码
     */
    public function html() {
    	
        if ($this->totalPage <= 1)
            return '';
        $pagelist = $this->pageList();
        $html = '';
        if ($this->page > 1) {
            $html = '<a href="' . $this->herf($this->page - 1) . '"><span class="pagel">< 上一页</span></a>';
        }
        foreach ($pagelist as $v) {
            if ('y' == $v['is_show']) {
                if ($this->page == $v['page_num']) {
                    $html.= '<em class="select">' . $v['page_num'] . '</em>';
                } else {
                    $html .= '<a href="' . $this->herf($v['page_num']) . '"><span class="pagel">'.$v['page_num'].'</span></a>';
                }
            }if ('...' == $v['is_show']) {
                $html .= '...';
            }
        }
        if ($this->page < $this->totalPage) {
            $html .= '<a href = "' . $this->herf($this->page + 1) . '"><span class = "pager">下一页 ></span></a>';
        }
        return $html;
    }
	
	/**
     * 获取分页html代码(new)
     */
    public function htmlNew() {
        if ($this->totalPage <= 1)
            return '';
        $pagelist = $this->pageList();
        $html = '';
        if ($this->page > 1) {
            $html = '<a href="' . $this->herf($this->page - 1) . '" class="p"> 上一页</a>';
        }
        foreach ($pagelist as $v) {
            if ('y' == $v['is_show']) {
                if ($this->page == $v['page_num']) {
                    $html.= '<a class="crt" href="javascript:;">' . $v['page_num'] . '</a>';
                } else {
                    $html .= '<a href="' . $this->herf($v['page_num']) . '">'.$v['page_num'].'</a>';
                }
            }if ('...' == $v['is_show']) {
                $html .= '<span>...</span>';
            }
        }
        if ($this->page < $this->totalPage) {
            $html .= '<a href = "' . $this->herf($this->page + 1) . '" class="n">下一页 </a>';
        }
        return $html;
    }
    /**
     * 获取分页列表
     */
    protected function pageList() {
        //生成分页数组
        for ($i = 1; $i <= $this->totalPage; $i++) {
            $pageList[$i]["page_num"] = $i;
        }

        //设置页面码是否显示
        if ($this->totalPage <= $this->limit) {
            for ($i = 1; $i <= $this->totalPage; $i++) {
                $pageList[$i]['is_show'] = 'y';
            }
        } elseif ($this->totalPage > $this->limit) {
            if ($this->page <= round($this->limit / 2)) {
                for ($i = 1; $i <= $this->totalPage; $i++) {
                    $pageList[$i]['is_show'] = $i <= $this->limit ? 'y' : 'n';
                }
                if ($this->limit + 1 <= $this->totalPage) {
                    $pageList[$this->limit + 1]['is_show'] = '...';
                }
                $pageList[$this->totalPage]['is_show'] = 'y';
            } elseif ($this->page > round($this->limit / 2)) {
                $pageshowmin = $this->page - floor($this->limit / 2);
                $pageshowmax = $this->limit % 2 == 0 ? $this->page + $this->limit / 2 - 1 : $this->page + floor($this->limit / 2);
                if ($pageshowmax > $this->totalPage) {
                    $pageshowmin -= $pageshowmax - $this->totalPage;
                }
                for ($i = 1; $i <= $this->totalPage; $i++) {
                    $pageList[$i]['is_show'] = $i >= $pageshowmin && $i <= $pageshowmax ? 'y' : 'n';
                }
                if ($pageshowmin - 1 > 1) {
                    $pageList[$pageshowmin - 1]['is_show'] = '...';
                }
                if ($pageshowmax + 1 <= $this->totalPage) {
                    $pageList[$pageshowmax + 1]['is_show'] = '...';
                }
                $pageList[1]['is_show'] = 'y';
                $pageList[$this->totalPage]['is_show'] = 'y';
            }
        }
        return $pageList;
    }

    /**
     * 获取url连接地址
     * @param int $page
     * @return string
     */
    public function herf($page = 1) {
        return str_replace('{page}', $page, urldecode($this->link));
    }
    
    /**
     * @author fangxz <fangxuezheng@51talk.com>
     * 获取分页html代码(bbs社区专用)
     */
    public function htmlBbs($limit) {
        if ($this->totalPage <= 1)
            return '';
        $pagelist = $this->pageListBbs($limit);
        $html     = '';
        if ($this->page > 1) {
            $html .= '<a class="a1" href="' . $this->herf(1) . '" > 首页</a>';
            $html .= '<a class="a1" href="' . $this->herf($this->page - 1) . '" > 上一页</a>';
        }
        foreach ($pagelist as $v) {
            if ('y' == $v['is_show']) {
                if ($this->page == $v['page_num']) {
                    $html .= '<a class="cur" href="javascript:;">' . $v['page_num'] . '</a>';
                } else {
                    $html .= '<a href="' . $this->herf($v['page_num']) . '">' . $v['page_num'] . '</a>';
                }
            }
            if ('...' == $v['is_show']) {
                $html .= '<span>...</span>';
            }
        }
        if ($this->page < $this->totalPage) {
            $html .= '<a href = "' . $this->herf($this->page + 1) . '" class="a2">下一页 </a>';
            $html .= '<a href = "' . $this->herf($this->totalPage) . '" class="a2">末页 </a>';
        }
        return $html;
    }
    
    /**
     * @author fangxz <fangxuezheng@51talk.com>
     * 获取分页列表
     */
    protected function pageListBbs($limit='') {
        //生成分页数组
        for ($i = 1; $i <= $this->totalPage; $i++) {
            $pageList[$i]["page_num"] = $i;
        }
       $this->limit = $limit ? $limit : $this->limit;
        //设置页面码是否显示
        if ($this->totalPage <= $this->limit) {
            for ($i = 1; $i <= $this->totalPage; $i++) {
                $pageList[$i]['is_show'] = 'y';
            }
        } elseif ($this->totalPage > $this->limit) {
            if ($this->page <= (round($this->limit / 2)-1)) {
                for ($i = 1; $i <= $this->totalPage; $i++) {
                    if ($i <= round($this->limit / 2)) {
                        $pageList[$i]['is_show'] = 'y';
                    }
                    if ($i > round($this->limit / 2)) {
                        $pageList[($this->limit / 2)+1]['is_show'] =  '...';
                    }
                    if($i == $this->limit){
                        $pageList[$i]['is_show'] = 'y';
                    }
                }
            } elseif ($this->page > (round($this->limit / 2)-1)) {
                $pageshowmin = $this->page - floor($this->limit / 2)+2;
                $pageshowmax = $this->limit % 2 == 0 ? $this->page + $this->limit / 2  : $this->page + floor($this->limit / 2)+1;
                $pageshowmax = $pageshowmax >= $this->totalPage ? $this->totalPage : $pageshowmax;
                if ($pageshowmax > $this->totalPage) {
                    $pageshowmin -= $pageshowmax - $this->totalPage;
                }
                for ($i = 1; $i <= $this->totalPage; $i++) {
                    if($i >= $pageshowmin && $i <= $this->page+1){
                        $pageList[$i]['is_show'] = 'y';
                    }
                    if ($i > $this->page+1 && $i< $pageshowmax) {
                        $pageList[$pageshowmax-1]['is_show'] =  '...';
                    }
                    if($i == $pageshowmax){
                        $pageList[$i]['is_show'] = 'y';
                    }
                }
            }
        }
        return $pageList;
    }
    

}

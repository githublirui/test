<?php
/**
 * @author longweiguo<lwg_8088@yahoo.com.cn>
 * @version 2010-02-21
 */
include_once dirname(__FILE__) . "/HtmlParser.class.php";

class RichText
{
	//允许的html标签
	public static $allowableTags = array(		
		"root", "text", "a", "b", "big", "blockquote", "br", "center", "dd", "del", "div", "dl", "dt", "em", "font",
		"pre", "hr", "i", "img", "label", "li", "ol", "p", "s", "small", "span", "strike", "strong", "sub",
		"sup", "table", "tbody", "td", "th", "tr", "tt", "u", "ul", "h1", "h2", "h3", "h4", "h5", "h6",
    );
	public static $allowableTagsFlip = null;

	public static $singleTags = array(
		"br", "hr", "input", "img", "base", "wbr", "rt", "param", "meta", "link", "col", "bgsound", "basefont", "doctype",
	);
	public static $singleTagsFlip = null;

	public static $singleAttrs = array("checked", "selected", "nowrap", "disabled", "noresize");
	public static $singleAttrsFlip = null;

	private $textLength    = 0;
	private $substrLength  = 0;
	private $charset       = 'utf-8';

	public function __construct()
	{
		if (!self::$allowableTagsFlip) {
			self::$allowableTagsFlip = array_flip(self::$allowableTags);
		}
		if (!self::$singleTagsFlip) {
			self::$singleTagsFlip    = array_flip(self::$singleTags);
		}
		if (!self::$singleAttrsFlip) {
			self::$singleAttrsFlip   = array_flip(self::$singleAttrs);
		}
	}

	protected function nodeFilter($node) 
	{
		//去掉多余的结尾标签
		if ($node->tag == 'text' && preg_match("/^<\/\w+>$/", $node->outertext)){
			$node->outertext = '';
			return;
		}

		//去掉不允许的标签
		if (!isset(self::$allowableTagsFlip[$node->tag])){
			$node->outertext = '';
			return;
		}

		//去掉没有内容的标签
		if (preg_match("/^(\s|<br\s*\/?>|　|&nbsp;|&#xa0;)*$/i", $node->innertext) && !in_array($node->tag, self::$singleTagsFlip)){
			if ($node->tag == 'p' || $node->tag == 'div'){
				$node->outertext = '<br />';
			}
			else {
				$node->outertext = '';
			}
			return;
		}

		if (count($node->attr)>0) {
			foreach($node->attr as $k=>$v){
				if ((is_bool($v) && !isset(self::$singleAttrsFlip[$k])) || false !== strpos($k, 'on')){
					$node->$k = null;
					unset($node->$k);
				}
			}
		}
		if (count($node->attr) == 0 && ($node->tag == 'font' || $node->tag == 'span')) {
			$node->outertext = $node->innertext;
		}

		if ($this->substrLength > 0){
			//如果到达指定长度，去掉标签
			if ($this->textLength >= $this->substrLength){
				$node->outertext = '';
				return ;
			}

			//内容截取
			if (!empty($node->_[HDOM_INFO_TEXT])){
				$str = preg_replace("/(\s|<br\s*\/?>|　|&nbsp;|&#xa0;)+/i", "", $node->_[HDOM_INFO_TEXT]);
				$thisLength = self::strlen($str, $this->charset);
				if ($this->textLength + $thisLength > $this->substrLength) {
                    $str = self::substr($str, $this->substrLength-$this->textLength, $this->charset);
					$this->textLength = $this->substrLength;
					$node->_[HDOM_INFO_TEXT] = $str;
					return;
                }
				else {
					$this->textLength += $thisLength;
				}                
			}
		}

		foreach($node->nodes as $c) {
			$this->nodeFilter($c);
		}
	}
	
	public function filter($str, $length=0, $charset='utf-8')
	{
		$this->textLength    = 0;
		$this->substrLength  = $length;
		$this->charset       = $charset;
        
        $str = self::stripRepeatStr($str);
		
		$html = str_get_html($str);

		$this->nodeFilter($html->root);

		$str = $html->save();

		$html->clear(); 
		unset($html);		
        
        //补全链接
        $str = preg_replace("/([^\/])www\./", "\\1http://www.", $str);

		return $str;
	}

	//取得纯文本
	public static function getText($str)
	{
		$str = strip_tags($str);
		$str = str_replace("&nbsp;", " ", $str);
		$str = str_replace("&#xa0; ", " ", $str);		
		$str = preg_replace("/\s+/", " ", $str);
		return trim($str);
	}
	
    /**
     * 去掉重复字串
     * 重复8次以上的字串将被删除
     *
     * @param unknown_type $str
     * @return unknown
     */
    private static function stripRepeatStr($str)
    {
        while (preg_match("/(.+)\\1{8,}/s", $str, $regs)){
            $str = preg_replace("/(".preg_quote($regs[1], "/").")+/", $regs[1], $str);
        }
        return $str;
    }

	public static function strlen($str, $charset='utf-8') 
	{ 
		$str = str_replace("\r\n"," ",$str);
		$str = stripslashes($str);
		
	    return mb_strlen($str, $charset);
	}
	
	public static function substr($str, $length, $charset='utf-8') 
	{
    	return mb_substr($str, 0, $length, $charset);
	}
}
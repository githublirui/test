<?php
namespace Crontab\Logic;
use Think\Model;

class NewsLogic extends Model{
    
	CONST ZHIBO_NAME = '直播8';
    CONST ZHIBO = 'http://news.zhibo8.cc/zuqiu/';
    
    // 将zhibo8中间需要采集的区域先采集出来
    private $regexp_block_content = '/<p\s+class="plist"(.*)>(.*)<\/p>/i';
    
	// 将区域中的a链接的url取出来
    private $regexp_block_urls = '/<a(.*)href="(.*)"(.*)>(.*)<\/a>/Ui';
    
	// zhibo8 标题
	private $regexp_zhibo_title = '/<div\s+class="title">\s+<h1>(.*)\s*<\/h1>\s*<span>(.*)<a(.*)>(.*)<\/a>\s*<\/span>/i';

	// zhibo8 内容
	private $regexp_zhibo_content = '/<div\s+id="signals"\s+class="content">\s*(.*)\s*<\/div>/i';

    /**
     * @description 得到zhibo8中间区域采集
     * @param type $content 中间部分源代码
     * @return type 返回a链接的数组
     */
    private function getZhiboBlockContent($content){
        $matches = array();
        preg_match_all($this->regexp_block_content, $content, $matches);
        return isset($matches[1]) ? $matches[1] : array();
    }
    
    /**
     * @param type $blocks 中间区域的a完整链接的数组
     * @return 通过$this->regexp_block_urls匹配出href的值, 返回
     */
    private function getZhiboBlockUrls($blocks){
        if(empty($blocks)){
            return false;
        }
        $urls = $matches = array();
        foreach($blocks as $block){
            preg_match_all($this->regexp_block_urls, $block, $matches);
            $urls = array_merge($urls, $matches[2]);
        }
		return !empty($urls) ? array_values($urls) : array();
    }
    
    /**
     * @brief 通过文章的内容, 匹配news表需要的数据, 并且insert
     * @param type $content 文章的内容源代码
     */
	private function handleNewsData($content){
		$matches = $content_matches = $data = $content_data = array();
		preg_match_all($this->regexp_zhibo_title, $content, $matches);
		$data['title'] = isset($matches[1][0]) ? $matches[1][0] : '';
		$data['published_time'] = isset($matches[2][0]) ? $matches[2][0] : '';
		$data['author'] = isset($matches[4][0]) ? $matches[4][0] : '';
		$data['resource'] = self::ZHIBO_NAME;
		$news_model = D('Home/News');
		$data_content['news_id'] = $news_model->insert($data);

		preg_match_all($this->regexp_zhibo_content, $content, $content_matches);
		$data_content['content'] = isset($content_matches[1][0]) ? $content_matches[1][0] : '';
		$news_content_model = D('Home/NewsContent');
		$news_content_model->insert($data_content);
	}
    
    /**
     * @brief 采集主函数
     */
    public function curlMultiZhibo(){
        import('Think.CurlMulti');
        $curl_multi = \CurlMulti::getInstance(array(get_class($this), 'callbackzhibo'));
        $content = $curl_multi->curl(self::ZHIBO);


        $blocks = $this->getZhiboBlockContent($content);
        $urls = $this->getZhiboBlockUrls($blocks);
		$counts = count($urls);
		$limit = 20;
		$current = 1;
        $start = 0;

		while($start < $counts){
			$start = ($current - 1) * $limit;
			$slice_urls = array_slice($urls, $start, $limit);
			$current++;
			if(!empty($slice_urls)){
				$curl_multi->curlmulti($slice_urls);
			}
		}
        return true;

    }
    
    /**
     * @brief 多线程采集回调函数 see Think\CurlMulti
     * @param type $content
     */
    public function callbackzhibo($content){
        $this->handleNewsData($content);
    }
    
}
<?php
/**
 * 字符串相关函数测试
 * @author zhengyifeng
 * @version 2011.5.12
 * 
 */
require_once(dirname(__FILE__) . '/../String.class.php');
class StringTest extends PHPUnit_Framework_TestCase
{
	private $string = '我就是用来测试对错的字符串的字符串';
	private $array  = array('我知道这是测试字符串的函数','我就是故意来捣乱的','我试试会发生什么');
	
	
	/**
	 * 测试utf-8下的字符串截取,多余的部分用符号代替
	 */
	public function testTrword()
	{
		$normal = String::substr_utf8($this->string,50,0);
		$this->assertEquals($this->string,$normal);//字数足够
		$special = String::substr_utf8($this->string,10,0) . '...';
		$this->assertEquals('我就是用来测试对错的...',$special);//字数不足
	}
	
	
	/**
	 * 测试截取utf-8编码的字符串
	 */
	public function testSubstr_utf8()
	{
		$normal = String::substr_utf8($this->string,6,2);
		$this->assertEquals('是用来测试对',$normal);//测试截取结果
		$special = String::strlen_utf8($normal);
		$this->assertEquals(6,$special);//测试长度
		/*$bug  = String::substr_utf8($array,8,0);
		$this->assertTure(TRUE,$bug);*/
	}
	
	
	/**
	 * 测试查找字符串位置
	 */
	public function testStrpos_utf8()
	{
		$normal = mb_strpos($this->string,'的',0,'utf-8');
		$this->assertEquals(9,$normal);//从开始查
		$normalone = mb_strpos($this->string,'字符',12,'utf-8');
		$this->assertEquals(14,$normalone);//从中间查
		$special = mb_strpos($this->string,'没有',0,'utf-8');
		$this->assertFalse($special);//根本没有,应该返回flase
	}
	
	
	/**
	 * 获得utf-8编码的字符串的长度
	 * @param $sourceString	utf-8编码的字符串
	 * @return int			长度
	 */
	public static function strlen_utf8($sourceString)
	{
		//环境检查不要在runtime中处理  2009-08-03 3:13 PM zjy
		/*
		if(!extension_loaded('mbstring') )
			die('php-mbstring should be install ');
		*/
		
		//换行符修改为一字节 和JS判断相一致 修改人：刘必坚 修改时间：2009.2.18
		$str = str_replace("\r\n"," ",$sourceString);
		$str = stripslashes($str);
		
	    return mb_strlen($str, 'utf-8');
	}
	
	/**
	 * 检查一个字符串是否以特定字符串开始
	 */
	public function testBeginWith() {
        $normal = String::beginWith($this->string, '我');
        $this->assertTrue($normal);
        $special = String::beginWith($this->string, '你');
        $this->assertFalse($special);
	}
	
	
	/**
	 * 检查一个字符串是否以特定字符串结尾
	 */
	public function testEndWith() {
        $normal = String::endWith($this->string, '串');
        $this->assertTrue($normal);
        $special = String::endWith($this->string, '你');
        $this->assertFalse($special);
	}
	
	/**
	 * 检查一段utf-8编码的字符串是否为中文
	 */
	public function testIsChinese_utf8() {
        $normal = String::isChinese_utf8($this->string);
        $this->assertEquals($normal, 1);
        $special = String::isChinese_utf8('abcdefg');
        $this->assertEquals($special, 0);
	}
	
	/**
	 * 将相差timestamp转为如“1分钟前”，“3天前”等形式
	 *
	 * @param timestamp $ts_diff 当前时间 - 要格式化的timestamp
	 */
	public static function formatTime($ts_diff)
	{
		if ($ts_diff <=0)
		{
			return date('Y-m-d');
		}
		else if ( $ts_diff <= 3600 )
		{
			return max(1, (int)($ts_diff/60)) . '分钟前';
		}
		else if ( $ts_diff <= 86400 )
		{
			return ((int)($ts_diff/3600)) . '小时前';
		}
		else
		{
			return ((int)($ts_diff/86400)) . '天前';
		}
	}
	
	//{{{格式化时间显示，用在二手物品等列表页 formatTime2()
	//$time 当前时间 timestamp
	public static function formatTime2($time, $listing_status=0, $pageType='detail')
	{
		if ( $pageType == 'detail' )
			return date("m-d H:i", $time);
		
		$ts_diff = time() - $time;

		if ($ts_diff <=0 || $listing_status==9 )
		{
			return date('m-d');
		}
		else if ( $ts_diff <= 3600 )
		{
			return max(1, (int)($ts_diff/60)) . '分钟前';
		}
		else if ( $ts_diff <= 86400 )
		{
			return ((int)($ts_diff/3600)) . '小时前';
		}
		else
		{
			return date("m-d", $time);
		}
	}
    /** 格式化时间显示，用在房产1/3/5前台列表 formatTimeHousing()
     * @param int $time 当前时间 timestamp
     */
	public static function formatTimeHousing($time, $listing_status=0, $pageType='detail', $post_type = null)
	{
		if ( $pageType == 'detail' )
			return date("m-d H:i", $time);
		
		$ts_diff = time() - $time;

        //{{{ add for 自主置顶
        //if( $ts_diff <= 0 ){
            if( $listing_status >= 9 ){
                return date('m-d');
            }
        //}
        return String::formatTime2($time,$listing_status,$pageType);
	}
	
	/**
	 * 获取unix时间戳秒数
	 *
	 * @return float
	 */
	public static function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}
	
	/**
     * 格式化为安全字符串(用于FCK编辑器提交后的内容清理中)
     *
     * @var string $str 字符串
     * @return string 返回完全的字符串
     */
	public function formatSafeStr($str)
    {
    	include_once dirname(__FILE__) . "/RichText.class.php";

		$richText = new RichText();
		return $richText->filter($str);
    }
    /** 将数字星期转换成字符串星期 weekNum2String($num)
     * @param int
     * @return string
     */
    public static function weekNum2String($num){
        switch($num){
            case 1:
                return '星期一';
            case 2:
                return '星期二';
            case 3:
                return '星期三';
            case 4:
                return '星期四';
            case 5:
                return '星期五';
            case 6:
                return '星期六';
            case 7:
                return '星期日';
            default:
                return '未知';
        }
    }
    /** 进行防注入检查 _gpc($g)
     * @param  string $g   要检查的变量
     * @return string
     */
    public static function gpc($g){
        $gpc = get_magic_quotes_gpc();
        if (!$gpc) {
            $g = addslashes($g);
        }
        return trim($g);
    }
    /** 生成数字,大小写字母组成的任意位数的字符串 random($min_len,$max_len,$t)
     * @param  $min_len  字符串最小长度
     * @param  $max_len    字符串最大长度
     * @param  $type       生成的字符串类别
     *                     0为全部小写和数字的组合,1为全部大写和数字的组合,3为全部数字的组合,4为大小写字母的组合
     * @return string
     */
    public static function random($min_len=9,$max_len=9,$type=3){
        $str_len = mt_rand($min_len,$max_len);
        $ps = "";
        while(strlen($ps) < $str_len){
            $r = array(
                    mt_rand(49,57),//1-9的ASCII码
                    mt_rand(65,90),//A-Z的ASCII码
                    mt_rand(97,122)//a-z的ASCII码
                    );
            if($type == 3) $tmp = chr($r[mt_rand(0,0)]);
            else $tmp = chr($r[mt_rand(0,2)]);
            if($type == 0)
                $tmp = strtolower($tmp);
            else
                $tmp = $type==1?strtoupper($tmp):$tmp;
            $ps .= $tmp;
        }
        return trim($ps);
    }

	/**
	 * 测试ASCII字符串截取
	 */
	public function testCutStr() {
		$normal = String::cutStr($this->string,50);
		$this->assertEquals($this->string,$normal);
		$special = String::cutStr($this->string,20);
		$this->assertEquals('我就是用来测试对错的...',$special);
	}
	
    
    public function testJsonEncode() {
        $array = array (
            0 => '/([0-9-]){7,20}/',
            1 => true,
            2 => '描述不能填写电话',
            3 => 24,
            4 => '33',
            5 => array(
                1, array(22, '你好啊')
            )
        );
        
        $ret = String::jsonEncode($array);
        $this->assertEquals($ret, '["/([0-9-]){7,20}/",true,"描述不能填写电话",24,"33",[1,[22,"你好啊"]]]');
    }
	
}

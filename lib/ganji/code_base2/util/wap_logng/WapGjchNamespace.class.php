<?php
if(!defined('CODE_BASE2'))
{
    require_once dirname(__FILE__).'/../../config/config.inc.php';
}
require_once (CODE_BASE2."/app/category/CategoryNamespace.class.php");
/**
 * 获取数据统计gjch信息
 *
 * @author    wangjian
 * @touch     2012-11-28
 * @category  wap
 * @package   WapGjchNamespace.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
class WapGjchNamespace
{
    public static  function judgePageRoleByUrl($url)
    {
        $cateArr = array('housing', 'wanted', 'parttime_wanted', 'secondmarket', 'vehicle', 'ticketing', 'pet',
                        'service_living', 'service_biz', 'training', 'personals', 'event', 'qiuzhi', 'findjob', 'parttime_findjob' );
        $otherArr = array("redirect", "recommend", "link", "jianyi", "fav", "zhaopin_zt", "nav", "additional", "down", "appdown");

        $urlArr = parse_url($url);
        //区别 http://wap.ganji.cn/bj/search/?  http://wap.ganji.cn//bj/search/?
        $path = str_replace("//", "/", $urlArr['path']);
        $pathArr= explode('/', $path);
        $city = $pathArr[1];
        if(count($pathArr)>2){
            $cate = $pathArr[2];
        }
        if(count($pathArr)>3){
            $detail = $pathArr[3];
        }

        if(empty($cate)){
            $pageType = 'index';
            $cateInfo = array('source_name' => "");
            $majorCateInfo = array('url' => "");
        }else if(in_array($cate, $otherArr)){
            $pageType = 'other';
            $cateInfo = array('source_name' => $cate);
            $majorCateInfo = array('url' => "");
        }else if($cate == 'search') {
            $pageType = 'search';
            $cateInfo = array('source_name' => 'site'); //这里修改成大师要求的数据
            $majorCateInfo = array('url' => "");
        }else{
            //判断是否是大类
            if(in_array($cate, $cateArr)){
                $cateInfo = CategoryNamespace::getByUrl($cate);
                $majorCateInfo = array('url' => "");
                $pageType = "channel";
            }else{
                //判断所属的二级/三级类
                $parentCates = CategoryNamespace::getParentByUrl($cate);
                $unknownCateInfo = CategoryNamespace::getByUrl($cate);
                if(2 == $unknownCateInfo['type']){
                    $cateInfo = $parentCates[0];
                    $majorCateInfo = $unknownCateInfo;
                }else if(3 == $unknownCateInfo['type']){
                    $cateInfo = $parentCates[0];
                    $majorCateInfo = $parentCates[1];
                }
                //暂时不考虑三级以上以及Tag
                $pageType = empty($detail) ? "list" : "detail";
            }
        }

        $gjchurl = '/' . $cateInfo['source_name'] . '/' . $majorCateInfo['url'] . '/' . $pageType;

        return $gjchurl;
    }


    public static   function getGjch($url, $gjch)
    {
        $gjchHeader =self:: judgePageRoleByUrl($url);
        $propStr = "";
        if(!empty($gjch)) {
            foreach ($gjch as $Value) {
                $propStr .= '@' . $Value['key'] . '=' . $Value['value'];
            }
        }
        return urlencode($gjchHeader . $propStr);
    }


    //统计关键字
    //see setGjch(WapPostDetailPage) in WapPage's subclasses
    public static    function InvokeWapLogV2Gjch($url, $gjch, $arr=array())
    {
    	//build 
    	$utmGifLocation = "/ga_v2.php";
    	$utmUrlArr = array_merge(preTrackPageViewV2(), $arr);
    	$utmUrlArr['utmgjch'] =self:: getGjch($url, $gjch);
		if(urldecode($utmUrlArr['utmgjch'])=='/redirect//other') 
			$utmUrlArr['utmgjgc'] = urlencode('/other/redirect/-/-/detail');
    	//build end
    	//write to scibe
    	require_once dirname(__FILE__).'/WapLogngNamespace.class.php';
    	require_once dirname(__FILE__).'/LogWriterNG.php';
    	$retUtmUrlArr=array();
    	WapLogngNamespace::GetAnalyticsImageUrl($utmUrlArr,$retUtmUrlArr);
    	$logstr=WapLogngNamespace::GetAnalyticsImageUrlRequestAnaTeam($retUtmUrlArr,true);
		//write to scribe    	
    	LogWriterNG::WriteTextToScribe($logstr);
    	
        $utmUrl = $utmGifLocation . "?" . http_build_query($utmUrlArr);
        writeToLocalV2($utmUrl);
    }
}
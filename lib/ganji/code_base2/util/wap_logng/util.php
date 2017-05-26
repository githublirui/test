<?php
/**
 * 整体迁移WapLogngNamespace
 * 使用类封装
 *
 * @author    wangjian
 * @touch     2012-11-28
 * @category  wap
 * @package   util.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
require_once dirname(__FILE__).'/WapLogngNamespace.class.php';

//use HTTP_HOST instead of SERVER_NAME
function curPageURL()
{
    return  WapLogngNamespace::curPageURL();
}


// Writes the bytes of a 1x1 transparent gif into the response.
function writeGifData()
{
    return  WapLogngNamespace::writeGifData();
}

//去掉IP组后一个分组，以保护用户隐私。
function maskIP($ip)
{
    return  WapLogngNamespace::maskIP($ip);
}


// The last octect of the IP address is removed to anonymize the user.
function getIP($remoteAddress)
{
    return  WapLogngNamespace::getIP($remoteAddress);
}

/**
 * 加密后google id
 * @param unknown_type $userAgent
 * @param unknown_type $cookie
 * @param unknown_type $account
 * @param unknown_type $guid
 */
function getVisitorIdEncrpt($userAgent, $cookie, $account="", $guid="")
{

    return  WapLogngNamespace::getVisitorIdEncrpt($userAgent, $cookie, $account, $guid);
}
// Generate a visitor id for this hit.
// If there is a visitor id in the cookie, use that, otherwise
// use the guid if we have one, otherwise use a random number.
function getVisitorId($userAgent, $cookie, $account="", $guid="")
{

    return  WapLogngNamespace::getVisitorId($userAgent, $cookie, $account , $guid );
}


//预获取相关统计参数，可以使用array_merge等覆盖
function preTrackPageViewV1()
{
    return  WapLogngNamespace:: preTrackPageViewV1();
}


//记录日志，写入本地文件
function writeToLocalV1($utmUrl)
{
    return WapLogngNamespace:: writeToLocalV1($utmUrl);
}


function InvokeWapLogV1($arr)
{
    return WapLogngNamespace:: InvokeWapLogV1($arr);
}


/**
 * 为了AB测试， 增加GJ.LogTracker.gjchver变量。该值从‘A-Z’
 *果页面中没有定义该变量，或者该变量不合法，则缺省为’A’
 **/
function getGjeval($value='')
{
    return WapLogngNamespace::getGjeval($value);
}


/**
 * 直接获取枚举cookies值，若为空值有可能是第一次访问或者是不支持
 * */
function isSupportCookie()
{
    return WapLogngNamespace::isSupportCookie();
}


//预获取相关统计参数，可以使用array_merge等覆盖
function preTrackPageViewV2()
{
    return WapLogngNamespace::preTrackPageViewV2();
}


function getOrganicInfo($ca_name='', $ca_source='', $ca_kw='', $ca_id='')
{
    return WapLogngNamespace:: getOrganicInfo($ca_name, $ca_source, $ca_kw, $ca_id);
}


function writeToLocalV2($utmUrl)
{
    return WapLogngNamespace::writeToLocalV2($utmUrl);
}


function preTrackPageViewDz()
{
    return WapLogngNamespace::preTrackPageViewDz();
}


//gjch is in setGoogleAnalytics_Gjch(WapPostDetailPage) or more
function InvokeWapLogV2($arr=array())
{
    return WapLogngNamespace::InvokeWapLogV2($arr);
}

function InvokeWapLogVar($version, $cookie_name)
{
    return WapLogngNamespace::InvokeWapLogVar($version, $cookie_name);
}

function InvokeWapLogTouch()
{
    return WapLogngNamespace::InvokeWapLogTouch();
}

function InvokeWapLogHtml5()
{
    return WapLogngNamespace::InvokeWapLogHtml5();
}

function TempWapLog()
{
    return WapLogngNamespace::TempWapLog();
}

function InvokeWapLogBoth($path, $refer)
{
    return WapLogngNamespace::InvokeWapLogBoth($path, $refer);
}


function writeToLocalDz($page_type, $channel_name='-', $city_name='-', $parameters = array())
{
    return WapLogngNamespace:: writeToLocalDz($page_type, $channel_name, $city_name, $parameters);
}


function writeToLocalAds($utmUrl)
{
    return WapLogngNamespace:: writeToLocalAds($utmUrl);
}


function writeToLocalAclk($utmUrl)
{
    return WapLogngNamespace:: writeToLocalAclk($utmUrl);
}


function writeToLocalJiuyou($utmUrl)
{
    return WapLogngNamespace:: writeToLocalJiuyou($utmUrl);
}


function writeToLocalWURFL($utmUrl)
{
    return WapLogngNamespace:: writeToLocalWURFL($utmUrl);
}
function writeToLocalDNS($utmUrl)
{
    return WapLogngNamespace:: writeToLocalDNS($utmUrl);
}

<?php
/**
* 整体迁移WapGjchNamespace
* 使用类封装
*
* @author    wangjian
* @touch     2012-11-28
* @category  wap
* @package   gjch.php
* @version   0.1.0
* @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
*/
require_once dirname(__FILE__).'/WapGjchNamespace.class.php' ;
function judgePageRoleByUrl($url)
{
    return WapGjchNamespace::judgePageRoleByUrl($url);
}
function  getGjch($url, $gjch)
{
    return WapGjchNamespace:: getGjch($url, $gjch);
}
function   InvokeWapLogV2Gjch($url, $gjch, $arr=array())
{
    return WapGjchNamespace:: InvokeWapLogV2Gjch($url, $gjch, $arr);
}


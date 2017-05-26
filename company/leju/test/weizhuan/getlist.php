<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($userdata);
$page=trim($_GET['page']);
$typeid=trim($_GET['typeid']);
$uid=trim($_GET['uid']);
if(is_numeric($uid)==false){
	$uid='';
}
$min=$page*10;
$max=$min+10;
if(is_numeric($page)==false){
	$page=1;
}

if($typeid==0){
	$rows=$mysql->query("select * from `article` order by  -`top`,`id` desc limit {$min},{$max}");
}else{
	$rows=$mysql->query("select * from `article` where `type`='{$typeid}' order by  -`top`,`id` desc limit {$min},{$max}");	
}

//转发多域名支持
$arr_sharesite=explode('#',$config['sharesite']);
$rand=array_rand($arr_sharesite);
$sharesite=$arr_sharesite[$rand];

foreach($rows as $row){
$row_pic=str_replace('[weixin]','http://img01.store.sogou.com/net/a/04/link?appid=100520031&w=135&h=90&url=',$row['pic']);
if($row['pv_max']=='-1'){
	$pv_max='无限投放';
}else{
	$pv_max=$row['pv_max'];
}
if($row['top']==1){
print <<<div
            <section class="middle_mode has_action">
                <a href="http://{$sharesite}/detail.php?aid={$row['id']}&uid={$uid}" class="article_link clearfix">
                    <h3><font color=red><b>[置顶]</b></font>{$row['title']}</h3>
                    <div class="list_img_holder">
                        <img src="{$row_pic}" style="opacity: 1;">
                    </div>
                    <div class="item_info">
                        <span class="cmt">阅读：{$row['pv']}</span>
                        <span class="agree" style="display: none"><i class="fa fa-thumbs-up"></i> 925</span>
                        <span title="2015-06-30" class="time" style="display: none">06-30 20:10</span>
                    </div>
                </a>
            </section>
div;
}else{
print <<<div
            <section class="middle_mode has_action">
                <a href="http://{$sharesite}/detail.php?aid={$row['id']}&uid={$uid}" class="article_link clearfix">
                    <h3>{$row['title']}</h3>
                    <div class="list_img_holder">
                        <img src="{$row_pic}" style="opacity: 1;">
                    </div>
                    <div class="item_info">
                        <span class="cmt">阅读：{$row['pv']}</span>
                        <span class="agree" style="display: none"><i class="fa fa-thumbs-up"></i> 925</span>
                        <span title="2015-06-30" class="time" style="display: none">06-30 20:10</span>
                    </div>
                </a>
            </section>
div;
}
}
$mysql->__destruct();
$mysql->close();
<?php
require('conn.php');
require('session.php');
require('functions.php');
//print_r($userdata);
$page=trim($_GET['page']);
$typeid=trim($_GET['type']);
$min=$page*10;
$max=$min+5;
if(is_numeric($page)==false){
	$page=1;
}

if(is_numeric($typeid)){
	$rows=$mysql->query("select * from `article` where `type`='{$typeid}' order by  -`top`,`id` desc limit {$min},{$max}");	
}else{
	$rows=$mysql->query("select * from `article` order by  -`top`,`id` desc limit {$min},{$max}");
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
		<div class="list_d">
    	<a class="list_dd bor_b" href="http://{$sharesite}/detail.php?aid={$row['id']}&uid={$userdata['id']}" target="_blank">
		<div class="list_l"><img src="{$row_pic}" width="135" height="90"/></div>
		<div class="list_lt">
			<dl class="list_ld">
				<dt><font color=red><b>[置顶]</b></font>{$row['title']}</dt>
				<!--<dd>{$row['day']} 每次点击{$v_type['type_pp']}</dd>-->
				<!--<dd>剩余数量：{$pv_max}</dd>-->
			</dl>
		</div>
		</a>
		</div>
		
div;
}else{
print <<<div
		<div class="list_d">
    	<a class="list_dd bor_b" href="http://{$sharesite}/detail.php?aid={$row['id']}&uid={$userdata['id']}" target="_blank">
		<div class="list_l"><img src="{$row_pic}" width="135" height="90"/></div>
		<div class="list_lt">
			<dl class="list_ld">
				<dt>{$row['title']}</dt>
				<!--<dd>{$row['day']} 每次点击{$v_type['type_pp']}</dd>-->
				<!--<dd>剩余数量：{$pv_max}</dd>-->
			</dl>
		</div>
		</a>
		</div>
div;
}
}
$mysql->__destruct();
$mysql->close();
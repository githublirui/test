<?php
require('conn.php');
//require('session.php');
require('functions.php');
//print_r($userdata);
$uid=guolv($_GET['uid']);
$typeid=trim($_GET['type']);
if(is_numeric($typeid)==false){
	$typeid=0;
	$rows=$mysql->query("select * from `article` order by  -`top`,`id` desc limit 0,10");
}else{
	$rows=$mysql->query("select * from `article` where `type`='{$typeid}' order by  -`top`,`id` desc limit 0,10");	
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="Cache-Control" content="max-age=0">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title>转发就赚钱 - <?php echo $config['sitename']?></title>
		<meta name="description" content="<?php echo $config['sitename']?>">
		<meta name="keywords" content="<?php echo $config['sitename']?>" />
        <link rel="stylesheet" href="static/css/font-awesome.min.css" />
        <style>
            html, body{
                height: 100%;
                width:100%;
            }
            body{margin:0;font-family:"微软雅黑","Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif;font-size:12px;overflow-x: hidden;color: #3a4049;height: 100%;background: #fff}
            body.withHeader {
                padding-top: 80px;
            }
            body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,blockquote,p {
                padding: 0;
                margin: 0;
            }*,*:before,*:after {
                 -webkit-box-sizing:border-box;
                 -moz-box-sizing:border-box;
                 box-sizing:border-box
             }
            .clear:before,.clear:after{
                content: " ";
                display: table;
            }
            input,select,textarea {
                outline: 0;
                background-color: #fff;
                border: 1px solid #D9D9D9;
                display: inline-block;
                padding: 5px 6px 3px;
                font-size: 12px;
                line-height: 20px;
                color: #555555;
                vertical-align: middle;
                resize: none;
            }
            em {
                font-style:normal;
                font-weight:normal;
            }
            img{max-width:100%;width:auto\9;height:auto;vertical-align:middle;border:0;-ms-interpolation-mode:bicubic}
            ol,ul,li {
                list-style-type:none;
                padding:0;
                margin:0;
            }
            ol,ul,li {
                list-style-type:none;
            }
            a {
                text-decoration:none;
                outline:none;
                color: #333;
                text-shadow:none;
                resize: none;
            }
            a:hover {
                outline:none;
                text-shadow:none;
            }
            div, input, select, textarea, span, img, table, td, th, p, a, button, ul, li,i {
                border-radius: 0 ;/*!important*/
                -moz-border-radius: 0;
                -webkit-border-radius: 0 ;
                outline:0 none;
            }
			/* 底部浮层 */
			.positionFooter {
				background: #f3f3f3;
				bottom: 0;
				left: 0;
				position: fixed;
				width: 100%;
				z-index: 200;
				border-top: 1px solid #ddd
			}
			.positionFooter li {
				float: left;
				position: relative;
				text-align: center;
				width: 33.3%;
				color: #3096c6;
				padding: 5px 0
			}
			.positionFooter li a {
				color: #8e8e8e;
				padding-top: 30px;
				font-size: 14px;
				display: block
			}
			.positionFooter li a.a1 {
				background: url(static/index/b1.png) no-repeat top center;
				background-size: 30px 27px
			}
			.positionFooter li a.a2 {
				background: url(static/index/b2.png) no-repeat top center;
				background-size: 30px 27px
			}
			.positionFooter li a.a3 {
				background: url(static/index/b3.png) no-repeat top center;
				background-size: 30px 27px
			}
            header{
                display: block;
                z-index: 999;
                overflow: hidden;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            }
            .top_bar{
                height: 44px;
                position: relative;
                background: rgba(255,50,50,.95);
            }
            .top_bar .abs_m {
                width: 100%;
                text-align: center;
                font-size: 20px;
                color: #fff;
                font-weight: bold;
                line-height: 44px;
            }
            .top_menu_bar{background:rgba(255,255,255,0.95); border-bottom: 1px solid #f0f0f0; height: 37px; }
            .top_menu{overflow:hidden; overflow-x:scroll; -webkit-overflow-scrolling:touch; }
            .top_menu::-webkit-scrollbar { display: none; }
            .top_menu_list {
                white-space: nowrap;
            }
            .top_menu_list .btn.cur {
                color: #d00;
                font-size: 15px;
                background: #ddd;
                border-radius: 20px;
            }
            .top_menu_list .btn {
                white-space: nowrap;
                display: inline-block;
                padding: 0 10px;
                color: #333;
                text-decoration: none;
                font-size: 14px;
                line-height: 26px;
                height: 26px;
                margin: 5px 0px 5px 5px;
                border-radius: 20px;
            }
            .list_content{-webkit-user-select:none;position: relative;}
            section{ border-top:1px solid #f0f0f0; position: relative; background-color: #fff}
            .list_content section:last-child{ border-bottom: 1px solid #f0f0f0; }
            section .article_link{ display:block; padding:10px 10px 10px 14px; text-decoration:none; -webkit-tap-highlight-color:rgba(0,0,0,.1); -webkit-touch-callout:none;}

            section h3{ color:#464646; font-size:17px; font-weight:normal; margin: 0; padding:0; line-height: 1.2em; text-align: justify;}
            .iphone section{ padding: 2px }
            .iphone section .article_link,
            .iphone section h3{ font-weight: bold;}
            .item_info{ font-size: 11px; line-height: 11px; margin-top: 10px; color: #999; position: relative; }
            section.middle_mode .article_link{ padding-right: 110px;}
            section.middle_mode h3{ min-height: 2.4em;}
            section.middle_mode .list_img_holder{ overflow: hidden; position: absolute; right: 10px; top:50%; margin-top: -31px;border-radius: 5px;}


            /* 信息流大图,中图,小图 */
            .list_img_holder{ background:url(data:image/gif;base64,R0lGODlh7AA5AIAAAOHh4f///yH5BAEAAAEALAAAAADsADkAAAL/jI+py+0Po5y02ouz3hv4D4YhR2YiWAbnmJrry3bwTNdeRFNwWbeYPeMAh0SAJHd8kYpMZbIZ+0GnUUcP59RQt8/tzeWdTq6PnTYMxaK/l3V6HISYpe6iHKjCt+t2XdyaZcHHVKZnYOg3iNi1Uti4pzgESIYgmXjS8genqTBXQQmJtLBYSTqJmcLJ+DgauOkZKlpq4wh6KpIJ+8ra6aqme2mbR3tHXIzKA7yK28rrE6t6aMpgWcuc7BuMPLstaHwryx1+PN7rLJP9LF7l/Z1A1FCtrXxAD82uvo4iRCpvLjyMzbtp0s6dSZcv4DV+xOCBS2fQn74PuRBO3LeMYio+/wkLdsP28aDBfyOpWaTjpaPHkAxZonQ50F5MmCLfdARYEx+YkiRp1juJro9KhTxf6jR6tBlQohjz2bxZLujClj4vNjVZNCchqNGkJm2XdeZJmUjFOI2qVSPIqmJpkv006OxbuJFwPkw695eiZ1291g2rtGTeu3Ga+Fh67+/UeToHB3bHFHLOhIrRkouI+LHdp0OXVHbcdnFkgbskY3XYmernr3wzewacmutq0bJpy2UbbzZQ0IRJD+W903ZG3UassWastnNfdcB/Ehe+8njpo885Vb9O0Dluvbaxf/QOfrfry9TDJ49uHjz5836vWiWuGXtsu+jZp7Vfv3r86/Mt5+wvvlZ35p2mnnKmWVURbIll9x98vx2I4Ebj0SUUZRP251+EAUq3IH29hZZcc2VtJ2JPHFJoGFjdeLLcYRDuB+BrCk63FXIx/gcifi5mCGOC2w03Ao/vmcheiev9qB10KCq5JCtC4kikby3exySBJx7pG5VNeQhllDc2yNyLxunmoy9CKoPmhb1dCaNiY3LR5pdeWtmYkUnayd+bZsUpp4ajCahmjlViGQmQNdKJlyselshgk3/RmKKeOsLC45S5cWljXJBWuGaWugxmqZdsEuqGipxJ6qmZM/5paqCdohFbrMAgGWuttt6Ka2wFAAA7) #efefef no-repeat center center; background-size:54px; width:90px; height:63px;}
            .list_img_holder img{ border:none; display:block; width:100%; min-height:100%; -webkit-transition:opacity 300ms ease; -moz-transition:opacity ease .3s;  pointer-events: none}
            .list_img_holder_large img{border:none; display:block; width:100%; pointer-events: none}
            .list_img_holder_large_fix img{ position: absolute; }
            .list_image ul{ display:block; margin: 0; padding:0; list-style-type: none; }
            .list_image ul li{ display:inline-block; float:left; overflow:hidden; width:33.3%}
            .list_image ul li .list_img_holder{ margin:0 auto}
            .list_image ul li:first-child .list_img_holder{ float:left}
            .list_image ul li:last-child .list_img_holder{ float:right}

            @media screen and (min-width: 360px) {
              section h3{ font-size:19px;}
              section.middle_mode h3 {min-height: 54px;}
              .list_img_holder{width:106px; height:73px;}
              section.middle_mode .list_img_holder{ margin-top: -36px}
              section.middle_mode .article_link{ padding-right: 130px;}
            }

            @media screen and (min-width: 400px) {
              section h3{ font-size:21px;}
              section.middle_mode h3 {min-height: 58px;}
              .list_img_holder{width:114px; height:80px;}
              section.middle_mode .list_img_holder{ margin-top: -40px }
              section.middle_mode .article_link{ padding-right: 135px }
            }

            @media screen and (min-width: 700px) {
              section h3{ font-size:24px}
              .list_img_holder{width:203px; height:140px;}
              section.middle_mode .list_img_holder{ margin-top: -70px }
              section.middle_mode .article_link{ padding-right: 230px }
              section.middle_mode h3{ min-height: 120px}

              section .media_avatar img{ width: 96px; height: 96px;}
              section .media_info h3{ font-size: 22px;  margin-bottom: 10px;}
            }
            .item_info span{margin-right: 5px;opacity: .75}
            #more-loading{ border-top:1px solid #f0f0f0;background-color: #fff}
            #more-loading a{text-align:center;font-size: 16px;;display:block; padding:10px 10px 10px 14px; text-decoration:none; -webkit-tap-highlight-color:rgba(0,0,0,.1); -webkit-touch-callout:none;}
            .nothing{text-align: center;padding:20px 10px;font-size: 20px;color:#999}
            .nothing i{color:#bbb;opacity: .5}
        </style>
        
		<script src="static/jquery.js"></script>
    </head>
    <body class="withHeader">
        <header>
            <div class="top_bar">
                <div class="abs_m" onclick="location='ucenter.php'"><?php echo $config['sitename']?></div>
            </div>
            <div class="top_menu_bar">
                <div class="top_menu">
                    <div class="top_menu_list">
					<a href="list.php?uid=<?php echo $uid?>" class="btn">所有</a>
			<?php
			$rows_type=$mysql->query("select * from `typedata` order by `id` desc");
			//print_r($rows_type);
			//$count_type=count($rows_type);
			foreach($rows_type as $v_type){
				echo "<a href=\"?type={$v_type['id']}&uid={$uid}\" class=\"btn\">{$v_type['name']}</a>";
			}
			?>
                    </div>
                </div>
            </div>
        </header>
        <div class="list_content">
<?php
//转发多域名支持
$rand_sitehead=_rand(6);
$arr_sharesite=explode('#',$config['sharesite']);
$rand=array_rand($arr_sharesite);
$sharesite=$arr_sharesite[$rand];
$sharesite=str_replace('*',$rand_sitehead,$sharesite);//fan

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
?>		
            <div id="more-list"></div>
            <div id="more-loading"><a href=""></a></div>
        </div>
    </body>
</html>
<script>
$(function () {
			var page_num = 1;
			var ident = true;
			var height_rate = 1.8;
			$(window).scroll(function(){
				//if($(document).height() - $(window).scrollTop() < $(window).height()*height_rate){
				if($(document).scrollTop()>=$(document).height()-$(window).height() - 2500 && ident){
                     page_num = page_num + 1;
					 ident = false;
                     $.get("/getlist.php", { page: page_num ,typeid:<?php echo $typeid?>,uid:<?php echo $uid?>},function(response){
						ident = true;
						$("#more-loading").last().after(response);
						// if(response == ''){
							// ident = false;
							// $("#box-more").html('完');
						// }
                     },'html' );
				}
             });
});
</script>
<?php 
include('footer.php');
$mysql->__destruct();
$mysql->close();
?>
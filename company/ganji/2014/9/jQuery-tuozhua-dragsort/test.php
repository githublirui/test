<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; chaRset=utf-8" />
        <title>1</title>
        <meta name="keywords" content="基于jQuery的拖动排序插件" />
        <meta name="description" content="jquery特效,js特效,flash特效,div+css教程,html5教程" />
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery.1.4.2-min.js"></script>
    </head>
    <body>
        <script type="text/javascript">
            //屏蔽右键菜单
            document.oncontextmenu = function(event) {
                if (window.event) {
                    event = window.event;
                }
                try {
                    var the = event.srcElement;
                    if (!((the.tagName == "INPUT" && the.type.toLowerCase() == "text") || the.tagName == "TEXTAREA")) {
                        return false;
                    }
                    return true;
                } catch (e) {
                    return false;
                }
            }
        </script>

        <!--演示内容开始-->
        <script type="text/javascript" src="js/jquery.dragsort-0.4.min.js"></script>
        <style type="text/css">
            *{margin:0;padding:0;list-style-type:none;}
            body{font-family:Arial;font-size:12pt;color:#333;}
            h1{font-size:16pt;}
            h2{font-size:13pt;}
            /* demo */
            .demo{padding:20px;width:800px;margin:20px auto;border:solid 1px black;}
            .demo h2{margin:30px 0 20px 0;color:#3366cc;}
            /* dragfunction */
            .dragfunction{margin:40px 0 0 0;}
            .dragfunction dt{height:30px;font-weight:800;}
            .dragfunction dd{line-height:22px;padding:0 0 20px 0;color:#5e5e5e;}
            /* dragsort */
            .dragsort-ver li{height:30px;line-height:30px;}
            .dragsort{width:350px;list-style-type:none;margin:0px;}
            .dragsort li{float:left;padding:5px;width:100px;height:100px;}
            .dragsort div{width:90px;height:50px;border:solid 1px black;background-color:#E0E0E0;text-align:center;padding-top:40px;}
            .placeHolder div{background-color:white!important;border:dashed 1px gray!important;}
        </style>

        <div class="demo">
            <h1>jQuery列表拖动排列演示</h1>
            <h2>两组列表拖放:</h2>
            <select name="item_list" class="item_list">
                <option value="1.0.9">教育培训</option>
                <option value="1.0.10">婚恋交友</option>
                <option value="1.0.11">百宝箱</option>
                <option value="1.0.12">搬家</option>
            </select>
            <ul class="dragsort" id="list1">
                <li><div item_id="1.0.1">全职工作<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.2">兼职工作<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.3">生活家政<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.4">房产<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.5">二手车<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.6">二手物品<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.7">本地服务<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.8">生活电话簿<a href="javascript:void(0)" class="delModule">删除</a></div></li>
                <li><div item_id="1.0.8">宠物<a href="javascript:void(0)" class="delModule">删除</a></div></li>
            </ul>
            <div><input type="button" value="添加模块" class="addModule"/></a></div>
            <!-- 排序保存在这里可以检索服务器上的回传 -->
            <input name="list1SortOrder" type="hidden" />
            <div style="clear:both;"></div>
        </div>
        <!--演示内容结束-->
        <script type="text/javascript">
            $("#list1").dragsort({
                dragSelector: "div",
                dragBetween: true,
                dragEnd: saveOrder,
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>",
                scrollSpeed: 5
            });             //排序赋值
            function saveOrder() {
                var data = $("#list1 li").map(function() {
                    return $(this).children().attr('item_id');
                }).get();
                $("input[name=list1SortOrder]").val(data.join(","));
            }
            //添加模块
            $('.addModule').live('click', function() {
                var item_id = $('.item_list').val();
                var name = $(".item_list").find("option:selected").text();
                var list = '<li><div item_id="' + item_id + '">' + name + '<a href="javascript:void(0)" class="delModule">删除</a></div></li>';
                $("#list1").append(list);
            });
            //删除模块
            $('.delModule').live('click', function() {
                $(this).parent().parent().remove();
            });
        </script>
    </body>
</html>

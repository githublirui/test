<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>slideBox轮播插件</title>
        <link href="css/jquery.slideBox.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <center>
        <h5>一、左右轮播，滚动持续0.6秒，滚动延迟3秒，滚动效果swing，初始焦点第1张，点选按键自动隐藏，按键边框半径（IE8-只方不圆）5px（以上各项为默认设置值）</h3>
            <div id="demo1" class="slideBox">
                <ul class="items">
                    <li><a href="http://www.ido321.com" title="标题一"><img src="./img/1.jpg"></a></li>
                    <li><a href="http://www.ido321.com" title="标题二"><img src="./img/0.jpeg"></a></li>
                    <li><a href="http://www.ido321.com" title="标题三"><img src="./img/2.png"></a></li>
                </ul>
            </div>
            <h5>二、上下轮播，滚动持续0.3秒，滚动延迟5秒，滚动效果linear，初始焦点第2张，点选按键自动隐藏</h3>
                <div id="demo2" class="slideBox">
                    <ul class="items">
                        <li><a href="http://www.ido321.com" title="标题一"><img src="./img/1.jpg"></a></li>
                        <li><a href="http://www.ido321.com" title="标题二"><img src="./img/0.jpeg"></a></li>
                        <li><a href="http://www.ido321.com" title="标题三"><img src="./img/2.png"></a></li>
                    </ul>
                </div></center>
                <script src="js/jquery.min.js" type="text/javascript"></script>
                <script src="js/jquery.slideBox.min.js" type="text/javascript"></script>
                <script>
                    jQuery(function ($) {
                        $('#demo1').slideBox();
                        $('#demo2').slideBox({
                            direction: 'top', //left,top#方向
                            duration: 0.3, //滚动持续时间，单位：秒
                            easing: 'linear', //swing,linear//滚动特效
                            delay: 5, //滚动延迟时间，单位：秒
                            startIndex: 1//初始焦点顺序
                        });
                    });
                </script>
                <div style="text-align:center;clear:both">
                    <p>适用浏览器：IE8、360、FireFox、Chrome等浏览器</p>
                    <p>来源：<a href="http://www.ido321.com/" target="_blank">淡忘~浅思</a></p>
                </div>
                </body>
                </html>
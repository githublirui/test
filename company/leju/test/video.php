<!doctype  html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>全屏问题</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-equiv="imagetoolbar" content="no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <script type="text/javascript" src="/js/lib/jquery-1.11.1.min.js"></script>
        <style type="text/css">
            *{
                padding: 0px;
                margin: 0px;
            }

            body div.videobox{
                width: 400px;
                height: 320px;
                margin: 100px auto;
                background-color:#000;
            }

            body div.videobox video.video
            {
                width: 100%;
                height: 100%;
            }

            :-webkit-full-screen {

            }

            :-moz-full-screen {

            }

            :-ms-fullscreen {

            }

            :-o-fullscreen {

            }

            :full-screen { 

            }

            :fullscreen {

            }

            :-webkit-full-screen video {
                width: 100%;
                height: 100%;
            }
            :-moz-full-screen video{
                width: 100%;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <div id="videobox">
            <video controls="controls" autoplay="" preload="preload" id="video">
                <source src="http://www.qnggg.com/weixin/addons/x3box/uploads/49117066655e04f667e7cc6283_merge.mp4" type='video/mp4' />
            </video>
            <button id="fullScreenBtn">全屏</button>
        </div>
        <script type="text/javascript">// <![CDATA[ 
            VideoJS.player.newBehavior("video", function (element) {
                _V_.addListener(element, "click", this.onPlayButtonClick.context(this));
            }, {
                onPlayButtonClick: function (event) {
                    this.enterFullScreen();
                    this.play();
                }
            }
            );
            // ]]></script>
    </body>

</html>
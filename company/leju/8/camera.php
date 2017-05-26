<!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
		<title>Smart Home - Camera</title>
        <link href="css/main.css" rel="stylesheet" type="text/css">
        <script src="js/jq.js"></script>
        <script type="text/javascript">
		/*
		*/
		function init(t){
			accessLocalWebCam("camera_box");
		}
		
		// Normalizes window.URL
		window.URL = window.URL || window.webkitURL || window.msURL || window.oURL;
		// Normalizes navigator.getUserMedia
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia|| navigator.mozGetUserMedia || navigator.msGetUserMedia;

		function isChromiumVersionLower() {
		  var ua = navigator.userAgent;
		  var testChromium = ua.match(/AppleWebKit\/.* Chrome\/([\d.]+).* Safari\//);
		  return (testChromium && (parseInt(testChromium[1].split('.')[0]) < 19));
		}

		
		function successsCallback(stream) {
			document.getElementById('camera_errbox').style.display = 'none';
      		//document.getElementById('video_box').style.display = 'block';
      		document.getElementById('camera_box').src = (window.URL && window.URL.createObjectURL) ? window.URL.createObjectURL(stream) : stream;
		
		}
		
		function errorCallback(err) {
	
		}
		
		function accessLocalWebCam(id) {
		  try {
			// Tries it with spec syntax
			navigator.getUserMedia({ video: true }, successsCallback, errorCallback);
		  } catch (err) {
			// Tries it with old spec of string syntax
			navigator.getUserMedia('video', successsCallback, errorCallback);
		  }
		}
		
        
		</script>
        <style type="text/css">
		#camera_errbox{
			width:320px; height:auto; border:1px solid #333333; padding:10px;
			color:#fff; text-align:left;margin:20px auto;
			font-size:14px;
		}
		#camera_errbox b{
			padding-bottom:15px;
		}
		
		</style>
	</head>
	<body onLoad="init(this)" oncontextmenu="return false" onselectstart="return false">
    	<div class="Screen_outer">
        	<div id="mainbox" class="Screen_inner">
            	<div id="bt_goback"></div>
            	<div class="logobox"></div><div id="t_iconbox" class="icon_12"></div><div id="t_text">
                	<div id="el_title" class="font_h2">Camera</div>
                    <div id="el_descr" class="font_2"></div>
                </div> 
                 
                <div class="t_descri_bt"></div>
                <div class="sp_title"><span class="sp_title_text">Camera</span><div class="sp_oc sp_oc_1"></div></div>
                <dl id="el_actionbox" class="menu_btbox" style="text-align:center;">
                    <video id="camera_box" autoplay="" src=""></video>
                    <div id="camera_errbox">
                    	<b>请点击“允许”按钮，授权网页访问您的摄像头！</b>
						<div>若您并未看到任何授权提示，则表示您的浏览器不支持Media Capture或您的机器没有连接摄像头设备。</div>
                    </div>
                </dl>
                <!--<div class="sp_title"><span class="sp_title_text">Menu</span><div class="sp_oc sp_oc_1"></div></div>
                <dl id="el_menubox" class="menu_btbox2">
                	<dd><div class="btbox3 bt4">添 加</div></dd>
                    <dd><div class="btbox3 bt4">除 湿</div></dd>
                    <dd class="fright"><div class="btbox3 bt3">确 定</div></dd>
                </dl>-->
        	</div><!-- end Screen_inner -->
        </div><!-- end Screen_outer -->
      
	</body>
</html>

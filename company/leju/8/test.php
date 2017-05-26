<meta charset="UTF-8" />
<script language="javascript">
//禁止按键F5
    document.onkeydown = function (e) {
        e = window.event || e;
        var keycode = e.keyCode || e.which;
        if (keycode == 116) {
            if (window.event) {// ie
                try {
                    e.keyCode = 0;
                } catch (e) {

                }
                alert('视频上传中，请等待');
                e.returnValue = false;
            } else {// ff
                alert('视频上传中，请等待');
                e.preventDefault();
            }
        }
    }

//
//取消禁止F5
    document.onkeydown = function (e) {
        return true;
    }
    
//禁止鼠标右键菜单
    document.oncontextmenu = function (e) {
        return false;
    }
</script>
<img src="http://www.qnggg.com/weixin/app/index.php?i=6&c=entry&do=video_qrcode&m=x3box&vid=4" />
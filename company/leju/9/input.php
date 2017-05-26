<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>万仔历险记</title>
        <meta name="viewport" id="viewport" content="width=device-width,user-scalable=no">
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
    </head>

    <body id="body">
        <div class="web_login" style="background:url(./company/leju/9/info_bg.png)"> 
            <div class="item "><span>姓&nbsp;&nbsp;&nbsp;&nbsp;名</span><input  type="text" name="username" id="name" placeholder="输入姓名"></div>
            <div class="item"><span>手机号</span><input  type="text" name="phone" maxlength=11 placeholder="请输入手机号码" id="phone"></div>
            <div class="item btn">
                <input type="submit" value="提交" onclick="doProfile();"/>
            </div>
        </div>
    </body>

    <script>
        document.getElementById("body").style.height = document.documentElement.clientHeight + "px"
    </script>
    <script>
        function doProfile() {
            var pars = {};
            pars.name = $.trim($('#name').val());
            pars.phone = $.trim($('#phone').val());
            var msg = '';
            if (pars.phone == '' || !(/^1\d{10}$/).test(pars.phone)) {
                msg = '请输入有效的手机号码';
                alert(msg);
                return;
            }
            $.post('{php echo $this->createMobileUrl("ajax",array("sdo"=>"regmsg"))}', pars).success(function (dat) {
                if (dat == 'success') {
                    location.href = location.href;
                } else {
                    alert(dat);
                }
            });
        }
        //禁止鼠标右键菜单
        document.oncontextmenu = function (e) {
            return false;
        }
    </script>
</html>

手机验证码计时器
<script src="/js/jquery-1.11.1.min.js"></script>
<input type="button" value="获取验证码" id="timer" style="height: 30px;"/>
<script>
    var time = function (obj) {
        if (wait == 0) {
            obj.removeAttr("disabled");
            obj.val("获取验证码");
            wait = 60;
        } else {
            obj.attr("disabled", true);
            obj.val("重新发送(" + wait + ")");
            wait--;
            setTimeout(function () {
                time(obj);
            }, 1000);
        }
    }

    $("#timer").click(function () {
        wait = 60;
        time($("#timer"));
    })
</script>
<script language="javascript" src="/js/jquery-1.11.1.min.js"></script>
<script language="javascript">
    var timer = setInterval(function () {
        $.ajax({
            url: "http://test.local/company/leju/8/upload.php",
            type: "get",
            dataType: "html",
            data: {},
            success: function (res) {
                if (res == 1) {
                    clearInterval(timer);
                }
            },
            error: function (res) {
            }
        });
    }, 3000); //5 seconds
</script>
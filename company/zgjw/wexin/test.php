<script>
    $(".login-btn").click(function() {
        var password = $("#password").val();
        document.cookie = "zgjw_password=" + password;
        alert(document.cookie.split(";"));
    })
</script>
<html>  
    <body>  
        <div>oninput测试</div>  
        <div id="testdiv"><input id='tx1' name="tx1" value="" /></div>  
    </body>  
</html>  
<script language="JavaScript">
    <!--  
function getOs() {//判断浏览器类型  
        var OsObject = "";
        if (navigator.userAgent.indexOf("MSIE") > 0) {
            return "MSIE";
        }
        if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
            return "Firefox";
        }
        if (isSafari = navigator.userAgent.indexOf("Safari") > 0) {
            return "Safari";
        }
        if (isCamino = navigator.userAgent.indexOf("Camino") > 0) {
            return "Camino";
        }
        if (isMozilla = navigator.userAgent.indexOf("Gecko/") > 0) {
            return "Gecko";
        }

    }

    if (navigator.userAgent.indexOf("MSIE") > 0) {
        document.getElementById('tx1').attachEvent("onpropertychange", txChange);
    } else if (navigator.userAgent.indexOf("Firefox") > 0) {
        document.getElementById('tx1').addEventListener("input", txChange2, false);
    }
    function txChange() {
        alert("testie");
    }
    function txChange2() {
        alert("testfirefox");
    }
</script> 
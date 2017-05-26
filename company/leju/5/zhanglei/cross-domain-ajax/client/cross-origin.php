<html>
<head>
<script>
var server_url = "http://www.cross-domain-server.com.cn/cross-origin.php";
var xhr;

function loadScript(url, callback, charset){
	charset = charset || 'utf-8';
	callback = callback ||
	function(){
	};
	var t = document.createElement("script");
	t.type = "text/javascript";
	t.charset = charset;
	t.src = url;
	document.getElementsByTagName("head")[0].insertBefore(t,document.getElementsByTagName("head")[0].firstChild);
    return t;
}

function createCORSRequest(method, url){
    xhr = null;
    if(window.XMLHttpRequest && !document.all){
        //Firefox, Opera 8.0+, Safari
        xhr = new XMLHttpRequest();
    }else if(window.XMLHttpRequest && document.all){
        xhr = new XDomainRequest();
    }else if(window.ActiveXObject){
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        return false;
    }
    xhr.open(method, url);
    /*
	if("withCredentials" in xhr){
		xhr.open(method, url, true);
	}else if(typeof XDomainRequest != "undefined"){
		xhr = new XDomainRequest();
		xhr.open(method, url);
	}else{
		xhr = null;
	}
    */
    xhr.onreadystatechange = handler;
    xhr.send();
}

function myfunc(obj){
    var url = server_url + "?params=" + obj.id;
    createCORSRequest("GET", url);
}

function handler(){
    if(xhr.readyState == 4){
        if(xhr.status == 200){
            var response = xhr.responseText;
            // 后台返回json数据, js做eval处理
            var results = eval("(" + response + ")");
            var replacestring = document.getElementById('div');
            string = "params is " + results.params + ", description is " + results.description;
            replacestring.innerHTML = string;
        }
    }
}

function _myfunc(obj){
    var url = server_url + "?params=" + obj.id;
    var element = loadScript(url);
    var string = null;
    if(document.all){
        element.onreadystatechange = function(){//IE用
            var state = element.readyState;
            if (state == "loaded" || state == "interactive" || state == "complete") {
                string = "params is " + result.params + ", description is " + result.description;
                document.getElementById('div').innerHTML = string;
            }
        };
    }else{
        element.onload = function() {//FF用
            string = "params is " + result.params + ", description is " + result.description;
            document.getElementById('div').innerHTML = string;
        };
    }
}

</script>
</head>
<body>
<div id='div' style='margin-bottom: 20px; color: red; font-weight: bolder'></div>
<a href='javascript:void(0)' onclick='myfunc(this)' id='xmlhttprequest' style='text-decoration:none'>cross domain ajax</a>
<br /><br />
<a href="javascript:void(0)" onclick='_myfunc(this)' id='scriptsrc' style='text-decoration:none'>script src</a>
</body>
</html>
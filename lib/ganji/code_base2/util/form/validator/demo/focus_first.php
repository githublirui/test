<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>表单验证</title>
<link  href="/css/validator.css" rel="stylesheet" type="text/css" />
<script src="../../../../apps/media/js/jquery.min.js" type="text/javascript"></script>
<script src="../../../../apps/media/js/validator/validator.js" type="text/javascript"></script>
</head>
<body>
<form id="form1" name="form1" method="post" action="" onsubmit="alert('验证通过');">
  <h3>自动聚焦出错的表单控件的示例</h3>
  <div style="color:#f00"></div>
  <p>
    <input type="text" name="username" />
  </p>
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
  <p>
    <input type="text" name="password" />
  </p>
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
  <p>
    <input type="text" name="password2" />
  </p>
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
  <p>
    <input type="text" name="email" />
  </p>
  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
  <p>
    <input type="submit" name="Submit" value="提交" />
  </p>
</form>

<script type='text/javascript'>
$.validator("username")
.setDefaultMsg("请填写用户名")
.setFocusMsg("请填写用户名,3-20个字符")
.setEmptyValue("请输入用户名")
.setRequired("用户名不能为空");

$.validator("username")
.setLength(3, 20, "字符长度要在3-20之间");

$.validator("password")
.setDefaultMsg("请填写密码")
.setFocusMsg("请填写密码，只能是数字")
.setRequired("密码不能为空");

$.validator("password2")
.setDefaultMsg("请再次填写密码")
.setFocusMsg("请再次填写密码，确保两次填写的一致")
.setRequired("必须再填写一次密码");

$.validator("password2")
.setCompareField("==", "password", "再次填定的密码不正确");

$.validator("email")
.setRegexp(/^\w+((-|.)\w+)*@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/, "Email不正确");
</script>
</body>
</html>
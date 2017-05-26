<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>源码</title>
</head>

<body>
<?
highlight_string(file_get_contents(dirname(__FILE__) . "/../../../../" . $_GET['file']));
?>
</body>
</html>
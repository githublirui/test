<?php
$now_path = dirname(__FILE__);

$xml = URL_PATH . 'krpano.php';
$content = include ($now_path . "/krpano.php");
var_dump($s);
die;
var_dump($content);
die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <title>一楼厨房</title>
        <style type="text/css">
            html {height: 100%;overflow: hidden;}
            #vrpano {height: 100%;}
            body {height: 100%;margin: 0;padding: 0;background-color: #fff;}
        </style>
    </head>
    <body>
        <div id="vrpano"></div>
        <script type="text/javascript" src="<?php echo URL_PATH ?>swfkrpano.js"></script>
        <script type="text/javascript">
            embedpano({swf: "<?php echo URL_PATH ?>krpano.swf", xml: "<?php echo $xml ?>", target: "vrpano", html5: "auto", passQueryParameters: true});
        </script>
    </body>
</html>
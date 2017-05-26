<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>管理系统</title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap-responsive.css">
    <link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">

    <script src="lib/jquery-1.8.1.min.js" type="text/javascript"></script>
    <!-- Demo page code -->
<script language="JavaScript" type="text/javascript">
// if ((navigator.userAgent.indexOf('MSIE') >= 0) 
    // && (navigator.userAgent.indexOf('Opera') < 0)){
    // alert('您使用的是IE浏览器，请用chrome内核浏览器，推荐UC浏览器，360极速，猎豹等');
	// location.href='admin.php?do=exit';
// }
</script>
    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .brand { font-family: georgia, serif; }
        .brand .first {
            color: #ccc;
            font-style: italic;
        }
        .brand .second {
            color: #fff;
            font-weight: bold;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="javascripts/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox() 
    })
</script>

  <body> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container-fluid">
                <ul class="nav pull-right">
                    <li><a href="<?php echo $site?>" target="_blank">访问网站</a></li>
                    <li id="fat-menu" class="dropdown">				
                        <a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
							
						   <i class="icon-user"></i><?php echo $_SESSION['admindata']['username']?>
                            <i class="icon-caret-down"></i>
                        </a>
						
						

                        <ul class="dropdown-menu">
                            <!--<li><a tabindex="-1" href="set.php">设置</a></li>-->
                            <li class="divider"></li>
							<li><a href="set_admin.php">管理员设置</a></li>
                            <li><a href="admin.php?do=exit">退出</a></li>
                        </ul>
						
                    </li>
                </ul>
                <a class="brand" href="ucenter.php"><span class="second"><?php echo $config['sitename']?>管理后台</span></a>
				
            </div>
        </div>
    </div>
<!doctype html>
<head>
    <title>ce'shi</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/animate.css"/>
    <link rel="stylesheet" href="css/base.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimumscale=1.0, maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="max-age=0" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />

    <style>
        body, canvas, div {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            -khtml-user-select: none;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        .rank_list {
            color: #F4F4F4;
        }
        .rank_list li {
            margin-left: 30px;
        }
    </style>

</head>
<body>
    <div class="ruler" style="display:block;">
        <ul class="rank_list">
            <?php for ($i = 1; $i <= 100; $i++) { ?>
                <li>乐居移动 <?php echo $i ?>  <?php echo rand(1, 50000); ?></li>
            <?php } ?>
        </ul>
</body>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery-scrollable.js"></script>
<script type="text/javascript">
    var duration = $(".rank_list li").length;
    duration = duration * 1000;
    $(".rank_list").scrollable({
        width: 250,
        height: 350,
        direction: "top",
        duration: duration,
        scrollCount: 0
    });
</script>

</html>

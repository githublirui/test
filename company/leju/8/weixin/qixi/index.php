<!DOCTYPE html>
<html class="">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>qi </title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no">
        <link rel="stylesheet" type="text/css" href="img/style.css">
        <SCRIPT type=text/javascript src="img/jquery-1.7.2.min.js"></SCRIPT>
        <script src="img/swipe.js"></script> 
        <SCRIPT>
            $(window).load(
                    function () {
                        window.mySwipe = new Swipe(document
                                .getElementById('slider'), {
                            startSlide: 0,
                            speed: 400,
                            auto: 6000,
                            continuous: true,
                            disableScroll: false,
                            stopPropagation: false,
                            callback: function (pos) {
                                var i = bullets.length;

                                while (i--) {
                                    bullets[i].className = ' ';
                                }
                                bullets[pos].className = 'on';
                            },
                            transitionEnd: function (index, elem) {
                            }
                        });
                        if (document.getElementById('ppoool')) {
                            var bullets = document.getElementById('ppoool')
                                    .getElementsByTagName('li');
                        }
                    });
        </SCRIPT>
    </head>
    <body >
        <section class="warp">

            <header>

                <div style="visibility: visible;" class="picshowtop" id="slider">
                    <div class="swipe-wrap">
                        <div data-index="1"> <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"><img src="img/jdt1.jpg"> </a> </div>
                        <div data-index="2" > <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"> <img src="img/jdt2.jpg"> </a> </div>
                        <div data-index="3" > <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"> <img src="img/jdt3.jpg"> </a> </div>
                        <div data-index="2" > <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"> <img src="img/jdt4.jpg"> </a> </div>
                        <div data-index="2" > <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"> <img src="img/jdt5.jpg"> </a> </div>
                        <div data-index="2" > <a href="http://eqxiu.com/s/Db4o80KH?from=singlemessage&isappinstalled=0"> <img src="img/jdt6.jpg"> </a> </div>
                    </div>
                    <div style="position: absolute; width: 100%; height: 25px; line-height: 25px; bottom: 0px; background:rgba(0,0,0,.5)"id="ppooind">
                        <ol id="ppoool">
                            <li class="on"></li>
                            <li></li>
                        </ol>
                    </div>
                </div>
            </header>
            <article>
                <div class="cont">
                    <h2>测试额度</h2>
                    <form>
                        <input type="text" name="name" placeholder="姓名" id='name'>
                        <select name='gender' id='gender'>
                            <option value="">性别</option>
                            <option value="1">女</option>
                            <option value="2">男</option>
                        </select>
                        <input type="text" name="tel" placeholder="手机号码" id='tel'>
                        <div class="box" >
                            <select style="width:33%" name='birthyear' id='birthyear'>
                                <option value="">出生年份</option>
                                <?php for ($i = 1940; $i <= 2015; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                            <select style="width:33%" name='birthmon' id='birthmon'>
                                <option value="">出生月份</option>
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?>月</option>
                                <?php } ?>
                            </select>
                            <select style="width:27%"  name='birthday' id='birthday'>
                                <option value="">出生日</option>
                                <?php for ($i = 1; $i <= 31; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <input type="text" name="biaobao" placeholder="阳光标保" id='biaobao'style="width:90%"><b style="line-height:33px; padding-left:5px">元</b>
                        <button type="button" class="btn" id='btn'>开始计算</button>
                    </form>
                </div>
            </article>
            <footer  ><p style="width:92%; margin:0 4%; background:#b32423; height:46px;line-height:46px; font-size:25px; font-weight:bold "id='res'>可购创富一号限额</p>
                <p style="color:#f00;line-height:54px">备注：1手等于年存2万</p>
            </footer>
        </section>
        <script>
            $("#btn").click(function () {
                var name = $("#name").val();
                var gender = $("#gender").val();
                var birthyear = $("#birthyear").val();
                var birthmon = $("#birthmon").val();
                var birthday = $("#birthday").val();
                var tel = $("#tel").val();
                var biaobao = $("#biaobao").val();
                if (!/^((\d{3}-\d{8}|\d{4}-\d{7,8})|(1[3|5|7|8][0-9]{9}))$/.test(tel)) {
                    alert('手机号码不正确');
                    return false;
                }
                if (!/^([1-9][\d]{0,9}|0)(\.[\d]{1,2})?$/.test(biaobao)) {
                    alert('金额填写不正确');
                    return false;
                }
                if (name == '') {
                    alert('请填写姓名');
                    return false;
                }
                if (gender == '') {
                    alert('请填写性别');
                    return false;
                }
                if (birthyear == '') {
                    alert('请填写完整的年龄');
                    return false;
                }
                if (birthmon == '') {
                    alert('请填写完整的年龄');
                    return false;
                }
                if (birthday == '') {
                    alert('请填写完整的年龄');
                    return false;
                }
                $.ajax({
                    url: 'index2.php',
                    type: 'post',
                    data: 'name=' + name + '&gender=' + gender + '&birthyear=' + birthyear + '&birthmon=' + birthmon + '&birthmon=' + birthmon + '&tel=' + tel + '&biaobao=' + biaobao,
                    async: false, //默认为true 异步     
                    dataType: 'json',
                    error: function () {
                        alert('error');
                    },
                    success: function (data) {
                        if (data.res == 'success') {
                            $("#res").html(data.biaobao);
                        } else if (data.res == 'system_error') {
                            alert('系统错误');
                        } else if (data.res == 'exist') {
                            alert('已经测试过');
                            $("#res").html(data.biaobao);
                        } else {
                            alert('系统错误');
                        }
                    }
                });
            })
        </script>
    </body>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>客户端接口测试工具</title>
        <link rel="stylesheet" type="text/css" href="./css/index.css" />
        <script type="text/javascript" src="./scripts/jquery-1.8.3.min.js"></script>
    </head>

    <?php
    $interface = '';
    if (isset($_GET['interface'])) {
        $interface = $_GET['interface'];
    }
    $customerId = '705';
    if (isset($_GET['customerId'])) {
        $customerId = $_GET['customerId'];
    }
    $versionId = '4.6.0';
    if (isset($_GET['versionId'])) {
        $versionId = $_GET['versionId'];
    }
    $token = '5949686d65514c5a776f49516b4135505949797456504336';
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }
    ?>
    <body>
        <input type="hidden" id="urlInterface" value="<?php echo $interface; ?>" />
        <div class="header">
            <div class="logo">
                <a><img src="./logo.png" alt="赶集网 啥都有"/></a>
            </div>
            <div class="rightHeader">
                <h3>Client Interface Test Page.</h3>
                <div class="hostPanel">
                    <ul id="hostList" class="radioList">
                        <?php foreach (EnvDomain::$ENVIRONMENT as $item) { ?>
                            <li>
                                <input type="radio" role="hostRadio" value="<?php echo $item['domain']; ?>"  />
                                <a><?php echo $item['title']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="leftWrapper">
                <div class="interfaceList">
                    <ul id="interfaceList" class="radioList">
                        <?php
                        foreach (InterfacePara::$TEST_DATAS as $key => $value) {
                            if ($key == $interface) {
                                echo '<li><input type="radio" name="interfaceBtn" value="' . $key . '" checked="true" />' . $key . '</li>';
                            } else {
                                echo '<li><input type="radio" name="interfaceBtn" value="' . $key . '" />' . $key . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="paramPanel">
                    <input type="button" class="button" id="submit" value="Submit" />
                    <a id="reset" />Reset</a>
                    <ul id="paraExp">
                        <li>
                            <label class="key">method</label>
                            <input type="text" value="GetCode" class="value textBox" />
                            <p></p>
                        </li>
                    </ul>
                    <ul id="paraList">
                    </ul>
                </div>
            </div>
            <div class="rightWrapper">
                <div class="topDefault">
                    <ul>
                        <li><label>customerId</label><input type="text" class="textBox" value="<?php echo $customerId; ?>" name="customerId" /></li>
                        <li><label>versionId</label><input type="text" class="textBox" value="<?php echo $versionId; ?>" name="versionId" /></li>
                        <li><label>显示数据类型</label><input style="margin-top:8px" type="checkbox" name="showTypeBox" /></li> 
                        <li><label>显示Header 数据</label><input style="margin-top:8px" type="checkbox" name="showHeaderBox" /></li>
                        <li><label>token</label><input style="width:400px" type="text" class="textBox" value="<?php echo $token; ?>" name="token" /></li>
                    </ul>
                </div>
                <div id="resultPanel">
                    Result will show here.
                </div>
            </div>
        </div>
        <div class="footer">
            <a> ©ganji.com</a>
        </div>
        <div id="paraConfig" style="display:none;"><?php echo InterfacePara::getParaConfig(); ?></div>
    </body>

    <script type="text/javascript" src="./scripts/interfaceConfig.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('input#submit').click(function() {
                var domain = $('ul#hostList input[checked="checked"]').val();
                var url = 'http://' + domain;
                var interfaceName = $('ul#interfaceList input[checked="checked"]').val();

                var customerId = $('input[name=customerId]').val();
                if (customerId == '') {
                    alert('请填写customerId.');
                    return;
                }
                var versionId = $('input[name=versionId]').val();
                if (versionId == '') {
                    alert('请填写versionId');
                    return;
                }

                var token = $('input[name=token]').val();

                var temp = [];
                //获取post 主题参数
                $('#paraList li').each(function() {
                    var key = $(this).find('label').html();
                    var value = $(this).find('input').val();
                    temp.push({k: key, v: value});
                });
                //合并所有参数
                var paramJson = {
                    URL: url,
                    interface: interfaceName,
                    customerId: customerId,
                    versionId: versionId,
                    token: token,
                    showType: $('input[name="showTypeBox"]').attr('checked'),
                    showHeader: $('input[name="showHeaderBox"]').attr('checked'),
                    postParam: temp
                }

                $('#resultPanel').html('努力请求中…');
                $.ajax({
                    type: 'GET',
                    data: paramJson,
                    url: 'request.php',
                    success: function(data) {
                        $('div#resultPanel').html('<pre>' + data + '</pre>');
                    }
                });
            });

            var interfaceName = $('input#urlInterface').val();
            var paraConfig = $.parseJSON($('div#paraConfig').html());

            $('ul.radioList').each(function() {
                //默认选中第一个
                $(this).find('li input').eq(0).attr('checked', true);
                $(this).find('li').click(function() {
                    //清除兄弟单选项状态
                    $(this).parent().find('li input').removeAttr('checked');
                    //选中自己
                    $(this).children(0).attr('checked', true);
                });
            });
            //接口项点击
            $('ul#interfaceList li').click(function() {
                var input = $(this).find('[name="interfaceBtn"]');
                input.attr('checked', true);
                var paraJson = paraConfig[input.val()];

                var html = $('#paraExp li').html();
                $('#paraList').html('');
                for (var key in paraJson) {
                    var item = $('<li>' + html + '</li>');
                    item.find('.key').html(key);
                    //接口说明字段特殊处理
                    if (key == 'desc') {
                        item.find('p').html(paraJson[key]);
                        item.find('.value').css("display", "none");
                    } else {
                        item.find('.value').attr('value', paraJson[key]);
                    }
                    item.appendTo($('#paraList'));
                }
            });
            $('ul#interfaceList li').eq(0).trigger('click');

            $('a#reset').click(function() {
                $('#interfaceList input[checked="checked"]').click();
            });
        });
    </script>

</html>

<?php
$base_url = '/anjuke'; //定义url访问地址
require 'function.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>安居客小区采集系统</title>
        <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    </head>
    <div style="margin-top:50px;margin-left:300px;">
        <h2>添加区县</h2>
        <span>
            <select class="province address" name="province">
                <option value="0">请选择</option>
                <?php foreach (getAllProvinces() as $pro_l) { ?>
                    <option value="<?php echo $pro_l['id'] ?>"><?php echo $pro_l['province'] ?></option>
                <?php } ?>
            </select>
            省
            <select class="city address" name="province">
                <option value="0">请选择</option>
            </select>
            市
        </span>
        <br/>
        <div style="margin-top: 10px;margin-bottom: 10px;">
            区县名称:
            <br/>
            <input type="text" class="area_name" />
        </div>
        <div style="margin-top: 10px;margin-bottom: 10px;">
            区县邮编:
            <br/>
            <input type="text" class="area_code"/>
        </div>
        <input type="button" class="submit" value="添加" />
        <img src="1.gif" style="display: none;" class="loader"/>
    </div>
</html>
<script>
    $(document).ready(function() {
        //三级联动
        $(".address").live("change",function() {
            var province = $(".province").val();
            var city = $(".city").val();
            var obj = $(this);
            $.ajax({
                type:'post',
                url:'<?php echo $base_url ?>/ajax.php?ac=a2',
                dataType:'html',
                data: ({province:province,city:city}),
                success: function(res) {
                    obj.parent().html(res);
                }
            })
        })
        
        $(".submit").click( function() {
            var obj = $(this);
            var province = $(".province").val();
            var city = $(".city").val();
            var area_name = $(".area_name").val();
            var area_code = $(".area_code").val();
            var error = false;
            if(province ==0) {
                alert("请输入省份");
                error = true;
            } else if(city ==0) {
                alert("请输入城市");
                error = true;
            } else if(!area_name) {
                alert("请输入区县名称");
                error = true;
            }else if(!area_code && area_code !=0) {
                alert("请输入区县邮编");
                error = true;
            }
            if(!error) {
                obj.hide();
                $(".loader").show();
                $.ajax({
                    type:'post',
                    url:'<?php echo $base_url ?>/ajax.php?ac=addarea',
                    dataType:'html',
                    data: ({province:province,city:city,area_name:area_name,area_code:area_code}),
                    success: function(res) {
                        obj.show();
                        $(".loader").hide();
                        $(".area_name").val('');
                        $(".area_code").val('');
                        if(res=='exist') {
                            alert('编码已经存在');
                        } else {
                            alert("添加成功");
                        }
                    }
                })
            } 
        })
        
        $(".clear_log").click( function() {
            $(".add_area_list").html('');
        })
    })
    
    /**
     *  自动刷新添加记录 
     */
    //    var ajax_record =setInterval("area_add_record()",2000);
    var area_add_record = function() {
        $.ajax({
            type:'post',
            url:'<?php echo $base_url ?>/ajax.php?ac=al',
            dataType:'html',
            success: function(res) {
                $(".add_area_list").html(res);
            }
        })
    }
</script>
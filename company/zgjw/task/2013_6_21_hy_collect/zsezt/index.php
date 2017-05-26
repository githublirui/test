<?php
require 'function.php';
unset($_SESSION['add_area']);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>装修E站通会员采集系统</title>
        <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    </head>
    <div style="margin-top:50px;margin-left:300px;">
        <h2>装修E站通会员采集系统</h2>
        <span>
            <select class="province address" name="province">
                <option value="0">请选择</option>
                <?php foreach (getAllProvinces() as $pro_l) { ?>
                    <option value="<?php echo $pro_l['id'] ?>"><?php echo $pro_l['name'] ?></option>
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
            <span style="color:red;">输入选择的地址</span>
            <br/>
            <input type="text" class="zsezt_province" style="width: 100px;" value=""/>省
            <input type="text" class="zsezt_city" style="width: 100px;" value=""/>市
            <br/>
        </div>
        <div style="color:red;margin-top: 10px;margin-bottom: 10px;">
            采集人员
            <br/>
            <input type="text" class="collecter" style="width: 100px;" value="<?php echo $_SESSION['authId'] ?>"/>
        </div>
        <input type="button" class="submit" value="确定采集" />
        <input type="button" class="loader" style="display: none;" value="正在采集....." />
        <img src="1.gif" style="display: none;" class="loader"/>
        <div style="margin-top: 10px;display:none" class="collect_num">
            共采集: <span class="num"></span>个小区
        </div>
        <div style="margin-top:20px;border:1px solid #ddd; width:700px;height:200px;overflow-y: auto">
            <ul style="list-style: none;font-size: 12px;color:green" class="add_area_list">
            </ul>
        </div>
    </div>
</html>
<script>
    $(document).ready(function() {
        //三级联动
        $(".address").live("change", function() {
            var province = $(".province").val();
            var city = $(".city").val();
            var obj = $(this);
            $.ajax({
                type: 'post',
                url: '<?php echo $base_url ?>/ajax.php?ac=a3',
                dataType: 'html',
                data: ({province: province, city: city}),
                success: function(res) {
                    obj.parent().html(res);
                }
            })
        })

        $(".submit").click(function() {
            var obj = $(this);
            var province = $(".province").val();
            var city = $(".city").val();
            var area = $(".area").val();
            var url = $(".url").val();
//            var pager = $(".pager").val();
            var district = $(".district").val();
            var collecter = $(".collecter").val();
            var zsezt_province = $(".zsezt_province").val();
            var zsezt_city = $(".zsezt_city").val();
//            var regular_url = $(".regular_url").val();
            var error = false;
            if (province == 0) {
                alert("请输入省份");
                error = true;
            } else if (city == 0) {
                alert("请输入城市");
                error = true;
            } else if (!zsezt_province) {
                alert("请输入选择的省份名称");
                error = true;
            } else if (!zsezt_city) {
                alert("请输入选择的城市名称");
                error = true;
            }
            if (!error) {
                obj.hide();
                $(".loader").show();
                //            var ajax_record =setInterval("area_add_record()",2000);
                $.ajax({
                    type: 'post',
                    url: '<?php echo $base_url ?>/ajax.php?ac=collect',
                    dataType: 'html',
                    data: ({province: province, city: city, area: area, zsezt_city: zsezt_city, zsezt_province: zsezt_province, collecter: collecter}),
                    success: function(res) {
                        obj.show();
                        $(".loader").hide();
                        area_add_record();
                        $(".zsezt_city").val('');
                        //clearInterval(ajax_record);
                    }
                })
            }
        })

        $(".clear_log").click(function() {
            $(".add_area_list").html('');
        })
    })

    /**
     *  自动刷新添加记录 
     */
    //    var ajax_record =setInterval("area_add_record()",2000);
    var area_add_record = function() {
        $.ajax({
            type: 'post',
            url: '<?php echo $base_url ?>/ajax.php?ac=al',
            dataType: 'html',
            success: function(res) {
                $(".add_area_list").html(res);
            }
        })
    }
</script>
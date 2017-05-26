<style type="text/css">
    label input[type="radio"] {
        margin-top: -2px;
    }
    span.item {
        float: left;
        margin: 4px 0px 0px 10px;
        cursor: pointer;
    }
    span.item i {
        margin: 4px 0px 0px 3px;
    }
    .select-small {
        width: 100px;
    }
    .tips {
        font-size: 80%;
        color: #A1A2A3;
    }
    .fl { float: left; }
    .input-label {
        margin: 6px 5px 0px 0px;
    }

    *{margin:0;padding:0;list-style-type:none;}
    h1{font-size:16pt;}
    h2{font-size:13pt;}
    /* demo */
    .demo{padding:20px;width:800px;margin:20px auto;border:solid 1px black;}
    .demo h2{margin:30px 0 20px 0;color:#3366cc;}
    /* dragfunction */
    .dragfunction{margin:40px 0 0 0;}
    .dragfunction dt{height:30px;font-weight:800;}
    .dragfunction dd{line-height:22px;padding:0 0 20px 0;color:#5e5e5e;}
    /* dragsort */
    .dragsort-ver li{height:30px;line-height:30px;}
    .dragsort{width:550px;list-style-type:none;margin:0px;}
    .dragsort li{float:left;padding:5px;width:100px;height:100px;}
    .dragsort div{cursor: pointer;width:90px;height:50px;border:solid 1px black;background-color:#E0E0E0;text-align:center;padding-top:40px;}
    .placeHolder div{background-color:white!important;border:dashed 1px gray!important;}
</style>
<form action="/appConfManage/category/categoryMoudle/save" class="form-horizontal" id="operate_form" method="POST">
    <div class="alert alert-info">
        <? echo $this->warnStr; ?>
    </div>
    <div class="form-inline well">
        <div class="control-group">
            <h4>添加模块</h4>
        </div>
        <input type="hidden" name="notice_id" value="<?php echo $this->notice['id']; ?>" />
        <div class="control-group">
            <label class="control-label">客户端</label>
            <div class="controls">
                <select name="customer" class="customer">
                    <option value="">--请选择--</option>
                    <?php foreach ($this->customers as $cas => $item) { ?>
                        <option value="<?php echo $cas ?>"><?php echo $item['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">大类</label>
            <div class="controls">
                <select name="categoryId" class="categoryId">
                    <option value="">--请选择--</option>
                </select>
            </div>
        </div>
        <div class="control-group virtualIdGroup" style="display:none;">
            <label class="control-label">虚拟大类</label>
            <div class="controls">
                <select name="virtualId" class="virtualId">
                    <option value="0">虚拟大类</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">客户端版本</label>
            <div class="controls">
                <select name="versionId" class="versionId">
                    <option value="0">不限版本</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">标题</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'title')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">icon</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'icon')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">显示模式</label>
            <div class="controls">
                <select name="mode">
                    <?php foreach ($this->showModes as $showId => $showText) { ?>
                        <option value="<?php echo $showId ?>"><?php echo $showText ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">扩展字段</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'ext', 'type' => 'textarea', 'style' => 'width:300px;height:110px;')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">模块子元素</label>
            <div class="controls" id="datagrid_item_list">
                <ul class="dragsort module_item_list" id="list1">
                </ul>
                <select name="item_list" class="addModule_item_list">
                </select>
                <input type="button" value="添加模块" class="addModule btn"/>
                <!-- 排序保存在这里可以检索服务器上的回传 -->
                <input name="new_item_list" type="hidden" />
            </div>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" id="submit_btn" class="btn btn-primary" value="保存" />
    </div>
</form>

<script type="text/javascript" charset="utf-8">
    GJ.use('app/backend/js/backend.js', function() {
<?php include dirname(__FILE__) . '/../static/js/jquery.dragsort-0.4.min.js' ?>
        $("#list1").dragsort({
            dragSelector: "div",
            dragBetween: true,
            dragEnd: saveOrder,
            placeHolderTemplate: "<li class='placeHolder'><div></div></li>",
            scrollSpeed: 5
        });
        //排序赋值
        function saveOrder() {
            var data = $("#list1 li").map(function() {
                return $(this).children().attr('item_id');
            }).get();
            $("input[name=new_item_list]").val(data.join(","));
        }
        //添加模块
        $('.addModule').live('click', function() {
            var item_id = $('.addModule_item_list').val();
            var itemList = $("#list1 li").map(function() {
                return $(this).children().attr('item_id');
            }).get();
            if (!item_id) {
                alert("模块不能为空");
            } else {
                if ($.inArray(item_id, itemList) == -1) {
                    var name = $(".addModule_item_list").find("option:selected").text();
                    $(".addModule_item_list").find("option:selected").remove();
                    var list = '<li><div item_id="' + item_id + '"><span>' + name + '</span><a href="javascript:void(0)" class="delModule">&nbsp;删除</a></div></li>';
                    $("#list1").append(list);
                    saveOrder();
                } else {
                    alert('列表中已经存在，请重新选择');
                }
            }
        });
        //删除模块
        $('.delModule').live('click', function() {
            $(this).parent().parent().remove();
            var item_id = $(this).parent().attr('item_id');
            var title = $(this).prev().html();
            $(".addModule_item_list").append('<option value="' + item_id + '" >' + title + '</option>');
            saveOrder();
        });

        $('.customer').live('change', function() {
            var customerId = $(this).val();
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetCategoryIds',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId},
                success: function(data) {
                    $(".categoryId").html('<option value="">--请选择--</option>');
                    for (var i in data) {
                        $(".categoryId").append('<option value="' + i + '" >' + data[i].name + '</option>');
                    }
                },
                error: function() {
                    GJ.app.backend.trigger('alert-error', '获取大类id失败！');
                }
            });
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetAllVersion',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId},
                success: function(data) {
                    $(".versionId").html('<option value="0">--不限版本--</option>');
                    for (var i in data) {
                        $(".versionId").append('<option value="' + data[i].softwareversion + '" >' + data[i].softwareversion + '</option>');
                    }
                },
                error: function() {
                    GJ.app.backend.trigger('alert-error', '获取版本失败！');
                }
            });
        })

        $('.categoryId').live('change', function() {
            var customerId = $('.customer').val();
            var categoryId = $(this).val();
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetItemList',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId, "categoryId": categoryId},
                success: function(data) {
                    $(".addModule_item_list").html('');
                    for (var i in data) {
                        $(".addModule_item_list").append('<option value="' + data[i].item_id + '" >' + data[i].item_id + data[i].title + '</option>');
                    }
                },
                error: function() {
                    GJ.app.backend.trigger('alert-error', '获取模块失败！');
                }
            });

            //判断虚拟id
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetVirtualIds',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId, "categoryId": categoryId},
                success: function(data) {
                    if (data != '') {
                        $('.virtualIdGroup').show();
                        for (var i in data) {
                            $(".virtualId").append('<option value="' + i + '" >' + data[i] + '</option>');
                        }
                    } else {
                        $('.virtualIdGroup').hide();
                        $(".virtualId").html('<option value="0">虚拟大类</option>');
                    }
                },
                error: function() {
//                    GJ.app.backend.trigger('alert-error', '获取虚拟大类id失败！');
                }
            });
        })
    });
</script>
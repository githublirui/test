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
</style>
<form action="/appConfManage/category/categoryItem/save" class="form-horizontal" id="operate_form" method="POST">
    <div class="alert alert-info">
        <?php echo $this->warnStr; ?>
    </div>
    <div class="form-inline well">
        <div class="control-group">
            <h4>添加元素</h4>
        </div>
        <input type="hidden" name="notice_id" value="<? echo $this->notice['id']; ?>" />
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
            <label class="control-label">所属模块</label>
            <div class="controls">
                <select name="moduleId" class="moduleId">
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Item Id</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'item_id')); ?>
                <span style="margin-left:10px;font-size:12px;">留空为自动计算</span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">标题</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'title')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">短标题</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'short_title')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">图片</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'img_url')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">显示*对应版本</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'is_new')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">数据来源</label>
            <div class="controls">
                <select name="data_source">
                    <option value="">请选择</option>
                    <?php foreach ($this->dataSources as $v => $name) { ?>
                        <option value="<?php echo $v ?>"><?php echo $name ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">跳转类型</label>
            <div class="controls">
                <select name="jump_type">
                    <option value="">请选择</option>
                    <?php foreach ($this->jumpTypes as $v => $name) { ?>
                        <option value="<?php echo $v ?>"><?php echo $name ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Data Params</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'data_params', 'type' => 'textarea', 'style' => 'width:300px;height:110px;')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">扩展字段</label>
            <div class="controls">
                <?php echo $this->helper('field', array('name' => 'ext', 'type' => 'textarea', 'style' => 'width:300px;height:110px;')); ?>
            </div>
        </div>
    </div>
    <div class="form-actions">
        <input type="submit" id="submit_btn" class="btn btn-primary" value="保存" />
    </div>
</form>

<script type="text/javascript" charset="utf-8">
    GJ.use('app/backend/js/backend.js', function() {
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
                    GJ.app.backend.trigger('alert-error', '获取大类失败！');
                }
            });
        })

        $('.categoryId').live('change', function() {
            var customerId = $(".customer").val();
            var virtualId = $(".virtualId").val();
            var categoryId = $(this).val();
            //更新选择item列表
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetModules',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId, "categoryId": categoryId, "virtualId": virtualId},
                success: function(data) {
                    $(".moduleId").html('');
                    for (var i in data) {
                        $(".moduleId").append('<option value="' + data[i].module_id + '" >' + data[i].module_title + '</option>');
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
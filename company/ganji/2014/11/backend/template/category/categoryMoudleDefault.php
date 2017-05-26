<style type="text/css">
    .grey {
        color: #DDDDDD;
    }
    .green {
        color: green;
    }
    .link {
        cursor: pointer;
    }
</style>
<form method="GET">
    <div class="alert alert-info">
        <?php echo $this->warnStr; ?>
    </div>
    <div class="form-horizontal">
        <div class="well">
            <div class="control-group">
                <div class="filter form-inline">
                    <select name="query_customerId" class="query_customerId">
                        <?php foreach ($this->customers as $cas => $item) { ?>
                            <option value="<?php echo $cas ?>" <? if($this->selectCustomer == $cas) echo 'selected="selected"'; ?>><?php echo $item['name'] ?></option>
                        <?php } ?>
                    </select>
                    <select name="query_categoryId" class="query_categoryId">
                        <?php
                        if ($this->selectCustomer) {
                            $categoryIds = $this->customers[$this->selectCustomer]['categoryIds'];
                            foreach ($categoryIds as $catId => $catArr) {
                                ?>
                                <option value="<?php echo $catId ?>" <? if($this->selectCategoryId == $catId) echo 'selected="selected"'; ?>><?php echo $catArr['name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php
                    $virtualIds = isset($this->customers[$this->selectCustomer]['categoryIds'][$this->selectCategoryId]['virtualIds']) ? //
                            $this->customers[$this->selectCustomer]['categoryIds'][$this->selectCategoryId]['virtualIds'] : '';
                    if (empty($virtualIds)) {
                        ?>
                        <select name="query_virtualId" class="query_virtualId" style="display:none">
                            <option value="0">虚拟大类</option>
                        </select>
                    <?php } else { ?>
                        <select name="query_virtualId" class="query_virtualId">
                            <option value="0">虚拟大类</option>
                            <?php foreach ($virtualIds as $vid => $virtualName) { ?>
                                <option <?php if ($this->virtualId == $vid) echo 'selected="selected"'; ?> value="<?php echo $vid ?>"><?php echo $virtualName ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                    <select name="query_version" class="query_version">
                        <option value="">选择客户端版本</option>
                        <?php
                        if ($this->selectCustomer) {
                            foreach ($this->allVersions as $allVersion) {
                                ?>
                                <option value="<?php echo $allVersion['softwareversion'] ?>" 
                                        <?php if ($this->softwareversion == $allVersion['softwareversion']) echo 'selected="selected"'; ?>>
                                            <?php echo $allVersion['softwareversion'] ?>
                                </option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <button class="btn btn-success" id="search-btn">查询</button>
                    <a class="btn btn-success" href="javascript:void(0)" id="generate-btn">生成配置</a>
                    <a id="add-item-btn" class="btn btn-success pull-right" href="/appConfManage/category/categoryMoudle/create"><i class="icon-plus icon-white"></i>增加模块</a>
                </div>
                <?php echo $this->datagrid->toHTML('datagrid-1'); ?>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    GJ.use('app/backend/js/backend.js', function() {
        GJ.app.backend.widget('#datagrid-1').ready(function() {
            var datagrid = this;
            datagrid.loadData();
        });

        $(".query_customerId").live('change', function() {
            var customerId = $(this).val();
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetCategoryIds',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId},
                success: function(data) {
                    $(".query_categoryId").html('');
                    for (var i in data) {
                        $(".query_categoryId").append('<option value="' + i + '" >' + data[i].name + '</option>');
                    }
                },
                error: function() {
                    GJ.app.backend.trigger('alert-error', '获取大类失败！');
                }
            });
        });

        $(".deleteModule").live('click', function() {
            var href = $(this).attr('link');
            GJ.use("js/util/panel/panel.js", function() {
                GJ.confirm({content: "确定删除该纪录？", style: "text", onSubmit: function() {
                        $.ajax({
                            url: href,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if (data.data) {
                                    GJ.app.backend.trigger('alert-success', '操作成功。');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    GJ.app.backend.trigger('alert-error', '操作失败1！' + data.data);
                                }
                            },
                            error: function(data) {
                                GJ.app.backend.trigger('alert-error', '操作失败2！' + data.data);
                            }
                        });
                    }})
            })

        });

        $("#generate-btn").live('click', function() {
            var customerId = $('.query_customerId').val();
            var categoryId = $('.query_categoryId').val();
            var virtualId = $('.query_virtualId').val();
            window.open('/appConfManage/category/categoryMoudle/generateConfig?customerId=' + customerId + '&categoryId=' + categoryId + '&virtualId=' + virtualId);
        });

        //大类change
        $(".query_categoryId").live('change', function() {
            var customerId = $('.query_customerId').val();
            var categoryId = $(this).val();
            $.ajax({
                url: '/appConfManage/category/categoryMoudle/ajaxGetVirtualIds',
                type: 'GET',
                dataType: 'json',
                data: {"customerId": customerId, "categoryId": categoryId},
                success: function(data) {
                    if (data != '') {
                        $('.query_virtualId').show();
                        for (var i in data) {
                            $(".query_virtualId").append('<option value="' + i + '" >' + data[i] + '</option>');
                        }
                    } else {
                        $('.query_virtualId').hide();
                        $(".query_virtualId").html('<option value="0">虚拟大类</option>');
                    }
                },
                error: function() {
//                    GJ.app.backend.trigger('alert-error', '获取虚拟大类id失败！');
                }
            });
        });
    });
</script>
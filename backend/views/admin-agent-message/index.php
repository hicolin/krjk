<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminAgentMessage;

$modelLabel = new \backend\models\AdminAgentMessage()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="col-sm-12">
                    <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                    <div class="form-group" style="margin: 5px;">
                        <label>代理人:</label>
                        <input type="text" class="form-control" id="query[user_id]" name="query[user_id]"
                               value="<?= isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                        <label>产品名称:</label>
                        <input type="text" class="form-control" id="query[p_id]" name="query[p_id]"
                               value="<?= isset($query["p_id"]) ? $query["p_id"] : "" ?>">
                        <label>使用推广码:</label>
                        <select name="query[type]" class="form-control" id="query[p_id]">
                            <option value="3">全部</option>
                            <option value="2" <?=$query["type"]==2?'selected':''?>>是</option>
                            <option value="1" <?=$query["type"]==1?'selected':''?>>否</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin: 5px;">
                        <label>时间：</label>
                        <input type="text" class="form-control ECalendar" id="b_time" name="query[b_time]"
                               value="<?= $query["b_time"] ? date('Y-m-d H:i', $query["b_time"]) : "" ?>"> -
                        <input type="text" class="form-control ECalendar" id="e_time" name="query[e_time]"
                               value="<?= $query["e_time"] ? date('Y-m-d H:i', $query["e_time"]) : "" ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm"><i
                                    class="glyphicon glyphicon-zoom-in icon-white"></i>搜索
                        </button>

                        <a class="btn btn-primary btn-sm"
                           href="<?= Url::toRoute([$this->context->id . '/' . Yii::$app->controller->action->id]) ?>">
                            <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <hr/>
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>&nbsp;&nbsp;
                                <a href="<?= Url::toRoute([$this->context->id . '/export','query'=>$query]) ?>">
                                    <button id="delete_btn" type="button" class="btn btn-xs btn-info">导出数据</button>
                                </a>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>

                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("user_id") ?></th>
                                         <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">代理人手机号</th>                                           
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("p_id") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("promo_code") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("promo_url") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending">使用推广码</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending">状态</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("create_time") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($model as $list) {
                                        ?>
                                        <tr id="rowid_$list->id">
                                            <td><label><input type="checkbox" value="<?= $list->id ?>"></label></td>
                                            <td><?= $list->member['nickname'] ?></td>
                                            <td><?= $list->member['tel'] ?></td>
                                            <td><?= $list->product['title'] ?></td>
                                            <td><?= $list->promo_code?:'无' ?></td>
                                            <td><?= $list->promo_url?:'无' ?></td>
                                            <td><?= $list->type==2?'是':'否' ?></td>
                                            <td><?= $list->type==1?'----':($list->status==1?
                                                    '<button onclick="update_allot('.$list->id.')" title="点击修改推广码" agent_id="'.$list->id.'" class="btn btn-info btn-xs show_allot" >修改</button>':
                                                    '<button title="点击分配推广码" agent_id="'.$list->id.'" class="btn btn-info btn-xs show_allot" data-toggle="modal"  data-target="#addUserModal">分配</button>') ?>
                                            </td>
                                            <td><?= date('Y-m-d', $list->create_time) ?></td>
                                            <td class="center">
                                                <a id="delete_btn" onclick="deleteAction('<?= $list->id ?>')"
                                                   class="btn btn-danger btn-sm" href="javascript:;"> <i
                                                            class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- row end -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data_table_info" role="status" aria-live="polite">
                                    <div class="infos">
                                        从<?= $pages->getPage() * $pages->getPageSize() + 1 ?>
                                        到 <?= ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ? $pageCount : $pages->totalCount ?>
                                        共 <?= $pages->totalCount ?> 条记录
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate"
                                     style="text-align: right;padding-right: 50px;">
                                    <?= LinkPager::widget([
                                        'pagination' => $pages,
                                        'nextPageLabel' => '下一页',
                                        'prevPageLabel' => '上一页',
                                        'firstPageLabel' => '首页',
                                        'lastPageLabel' => '尾页',
                                    ]); ?>

                                </div>
                            </div>
                        </div>
                        <!-- row end -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    分配推广码
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="user_id" class="col-sm-3 control-label">推广码</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="promo_code" name="promo_code" value=""
                               placeholder="请输入推广码">
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-3 control-label">推广链接</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="promo_url" value="" id="promo_url"
                               placeholder="请输入推广链接">
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" onclick="allot()" class="btn btn-primary">提交</button><span id="tip"> </span>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>
<?php $this->beginBlock('footer'); ?>
<script>
    var id = '';
    $('.show_allot').on('click', function () {
        id = $(this).attr('agent_id');
    })
    $('#addUserModal').on('hidden.bs.modal', function () {
        layer.closeAll();
    })
    function update_allot(id) {
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute($this->context->id . '/get-data')?>",
            data: {"id": id},
            cache: false,
            dataType: "json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                layer.msg('出错了'+errorThrown,{icon:2,time:2000})
            },
            success: function (data) {
                if(data.state==100) {
                    $('#promo_code').val(data.code);
                    $('#promo_url').val(data.url);
                    $('#addUserModal').modal('show');
                }else{
                    layer.msg('出错了'+errorThrown,{icon:2,time:2000})
                }
            }
        });
    }

    function allot(){
        var code = $('#promo_code').val();
        var url = $('#promo_url').val();
        if(!code) {
            layer.tips('请输入推广码', '#promo_code', {tips: 3});
            return false;
        }else if(!url) {
            layer.tips('请输入推广链接', '#promo_url', {tips: 3});
            return false;
        }else{
            $('#addUserModal').modal('hide');
            layer.closeAll();
            var csrf = "<?= Yii::$app->request->csrfToken ?>";
            $.ajax({
                type: "POST",
                url: "<?= Url::toRoute($this->context->id . '/allot-promo')?>",
                data: {"id": id, 'code':code, 'url':url, '_csrf':csrf},
                cache: false,
                dataType: "json",
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    layer.msg('出错了'+errorThrown,{icon:2,time:2000})
                },
                success: function (data) {
                    if(data==100) {
                        window.location.reload();
                    }else{
                        layer.msg('分配失败',{icon:2,time:2000})
                    }
                }
            });
        }
    }
    function initModel(id, type, fun) {
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute($this->context->id . '/view')?>",
            data: {"id": id},
            cache: false,
            dataType: "json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function (data) {
                initEditSystemModule(data, type);
            }
        });
    }
    function editAction(id) {
        initModel(id, 'edit');
    }

    function deleteAction(id) {
        var ids = [];
        if (!!id == true) {
            ids[0] = id;
        }
        else {
            var checkboxs = $('#data_table tbody :checked');
            if (checkboxs.size() > 0) {
                var c = 0;
                for (i = 0; i < checkboxs.size(); i++) {
                    var id = checkboxs.eq(i).val();
                    if (id != "") {
                        ids[c++] = id;
                    }
                }
            }
        }
        if (ids.length > 0) {
            admin_tool.confirm('请确认是否删除', function () {
                $.ajax({
                    type: "GET",
                    url: "<?=Url::toRoute($this->context->id . '/delrecord')?>",
                    data: {"ids": ids},
                    cache: false,
                    dataType: "json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function (data) {
                        for (i = 0; i < ids.length; i++) {
                            $('#rowid_' + ids[i]).remove();
                        }
                        admin_tool.alert('msg_info', '删除成功', 'success');
                        window.location.reload();
                    }
                });
            });
        }
        else {
            admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
        }

    }

    function getSelectedIdValues(formId) {
        var value = "";
        $(formId + " :checked").each(function (i) {
            if (!this.checked) {
                return true;
            }
            value += this.value;
            if (i != $("input[name='id']").size() - 1) {
                value += ",";
            }
        });
        return value;
    }

    $('#edit_dialog_ok').click(function (e) {
        e.preventDefault();
        $('#admin-module-form').submit();
    });

    /* $('#create_btn').click(function (e) {
     e.preventDefault();
     initEditSystemModule({}, 'create');
     });*/

    $('#delete_btn').click(function (e) {
        e.preventDefault();
        deleteAction('');
    });

</script>
<script>
    $("#b_time").ECalendar({
        type: "time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp: true,   //是否转成时间戳，默认true;
        offset: [0, 2],   //弹框手动偏移量;
        format: "yyyy-mm-dd hh:ii",   //格式 默认 yyyy-mm-dd hh:ii;
        skin: 2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step: 10,   //选择时间分钟的精确度;
        callback: function (v, e) {

        } //回调函数
    });

    $("#e_time").ECalendar({
        type: "time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp: true,   //是否转成时间戳，默认true;
        offset: [0, 2],   //弹框手动偏移量;
        format: "yyyy-mm-dd hh:ii",   //格式 默认 yyyy-mm-dd hh:ii;
        skin: 2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step: 10,   //选择时间分钟的精确度;
        callback: function (v, e) {

        } //回调函数
    });
</script>
<?php $this->endBlock(); ?>

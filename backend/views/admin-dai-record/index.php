<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use backend\models\AdminDaiRecord;

$modelLabel = new AdminDaiRecord();
$matchNumArr = AdminDaiRecord::$matchNumArr;
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                                <div class="form-group" style="margin: 5px;">
                                    <label>推荐人手机号:</label>
                                    <input type="text" class="form-control" id="query[phone]" name="query[phone]" value="<?= isset($query["phone"]) ? $query["phone"] : "" ?>">
                                    <label>申请人姓名:</label>
                                    <input type="text" class="form-control" id="query[name]" name="query[name]" value="<?= isset($query["name"]) ? $query["name"] : "" ?>">
                                    <label>产品名称:</label>
                                    <input type="text" class="form-control" id="query[product]" name="query[product]" value="<?= isset($query["product"]) ? $query["product"] : "" ?>">
                                    <label>手机号:</label>
                                    <input type="text" class="form-control" id="query[tel]" name="query[tel]" value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin-left: 5px">
                                    <div class="input-group">
                                        <div class="input-group-addon">状态</div class="input-group-addon">
                                        <select name="query[match_num]" id="query[match_num]" class="form-control">
                                            <option value="">全部</option>
                                            <option value="3" <?= $query['match_num'] == 3 ? 'selected' : '' ?>>待匹配</option>
                                            <option value="1" <?= $query['match_num'] == 1 ? 'selected' : '' ?> >匹配成功</option>
                                            <option value="2" <?= $query['match_num'] == 2 ? 'selected' : '' ?> >已失效</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label>申请时间：</label>
                                    <input type="text" class="form-control ECalendar" id="b_time" name="query[b_time]" value="<?= $query["b_time"] ? date('Y-m-d H:i',$query["b_time"]) : "" ?>"> -
                                    <input type="text" class="form-control ECalendar" id="e_time" name="query[e_time]" value="<?= $query["e_time"] ? date('Y-m-d H:i',$query["e_time"]) : "" ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-zoom-in icon-white"></i>搜索</button>

                                    <a class="btn btn-primary btn-sm" href="<?= Url::toRoute([$this->context->id . '/'.Yii::$app->controller->action->id]) ?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div style="margin-top: 10px;">
                            <a href="<?php echo Url::toRoute([$this->context->id . '/'.Yii::$app->controller->action->id])?>"><button class="btn btn-primary">代理申请</button></a>&nbsp;
                            <a href="<?php echo Url::toRoute([$this->context->id . '/'.Yii::$app->controller->action->id,'sign'=>101])?>"><button class="btn btn-success">融资客申请</button></a>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>&nbsp;&nbsp;
                                <a href="<?= Url::toRoute([$this->context->id . '/export','query'=>$query]) ?>">
                                    <button id="delete_btn" type="button" class="btn btn-xs btn-info">导出数据</button>
                                </a>&nbsp;&nbsp;
                                <button id="audit_btn" type="button" class="btn btn-xs btn-primary">批量审核失效</button>&nbsp;&nbsp;
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">ID</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">申请人</th>
                                         <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">手机号码</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">身份证号码</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("tid") ?></th>
                                         <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">推荐人手机号</th>                                           
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("pid") ?></th>                           
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("ip") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("phone_system") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("match_num") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("created_time") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">匹配时间</th>

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
                                            <td><?= $list->id ?></td>
                                            <td><?= $list->name ?></td>
                                            <td><?= $list->tel ?></td>
                                            <td><?= $list->id_card ?></td>
                                            <td><?= $list->members['nickname'] ?></td>
                                            <td><?= $list->members['tel'] ?></td>
                                            <td><?= $list->product['title'] ?></td>
                                            <td><?= $list->ip ?></td>
                                            <td><?= $phone_system[$list->phone_system] ?></td>
                                            <td><?= $matchNumArr[$list->match_num] ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list->created_time) ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list->match_time) ?></td>
                                            <td class="center">
                                                <a id="view_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/view', 'id' => $list->id]) ?>">
                                                    <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看</a>
                                                <a id="view_btn" class="btn btn-primary btn-sm" href="javascript:;"
                                                   onclick="audit('<?= $list->id ?>')">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i> 审核失效</a>
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

<?php $this->beginBlock('footer'); ?>
<script>

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
        type:"time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp : true,   //是否转成时间戳，默认true;
        offset:[0,2],   //弹框手动偏移量;
        format:"yyyy-mm-dd hh:ii",   //格式 默认 yyyy-mm-dd hh:ii;
        skin:2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step:10,   //选择时间分钟的精确度;
        callback:function(v,e){

        } //回调函数
    });

    $("#e_time").ECalendar({
        type:"time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp : true,   //是否转成时间戳，默认true;
        offset:[0,2],   //弹框手动偏移量;
        format:"yyyy-mm-dd hh:ii",   //格式 默认 yyyy-mm-dd hh:ii;
        skin:2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step:10,   //选择时间分钟的精确度;
        callback:function(v,e){

        } //回调函数
    });

    function audit(id) {
        layer.confirm('确定要审核失效吗？', {
            btn: ['确定', '取消']
        }, function () {
            layer.load(2);
            $.get('<?= Url::to(['admin-dai-record/audit']) ?>', {id: id}, function (res) {
                layer.closeAll();
                if (res.status === 200) {
                    layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                        location.reload();
                    })
                } else {
                    layer.msg(res.msg, {icon: 2, time: 1500})
                }
            }, 'json')
        })
    }

    $('#audit_btn').click(function () {
        var ids = [];
        var checkboxs = $('#data_table tbody :checked');
        if (checkboxs.size() > 0) {
            for (i = 0; i < checkboxs.size(); i++) {
                ids[i] = checkboxs.eq(i).val();
            }
        }
        if(!ids.length){
            layer.msg('请选择要审核的记录',{icon:7});
            return false;
        }
        layer.confirm('确定要批量审核失效吗？', {
            btn: ['确定', '取消']
        }, function () {
            layer.load(2);
            $.get('<?= Url::to(['admin-dai-record/batch-audit']) ?>', {ids: ids}, function (res) {
                layer.closeAll();
                if (res.status === 200) {
                    layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                        location.reload();
                    })
                } else {
                    layer.msg(res.msg, {icon: 2, time: 1500})
                }
            }, 'json')
        })
    })


</script>
<?php $this->endBlock(); ?>

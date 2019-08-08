<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminWithdraw;

$modelLabel = new \backend\models\AdminWithdraw()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<style type="text/css">
    .red{color:#dd4b39;}
</style>
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
                                    <label>微信昵称:</label>
                                    <input type="text" class="form-control" id="query[nickname]" name="query[nickname]" value="<?= isset($query["nickname"]) ? $query["nickname"] : "" ?>">
                                </div>
                                
                                <div class="form-group" style="margin: 5px;">
                                    <label> 手机号码:</label>
                                    <input type="text" class="form-control" id="query[tel]" name="query[tel]" value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                                </div>   
                                <div class="form-group" style="margin: 5px;">
                                    <label> 申请状态:</label>
                                    <select name="query[status]" id="query[status]"
                                            style="height:30px; width: 125px;border: 1px solid #ccc;">
                                        <option value="0" <?php if ($query['status'] == '0') {
                                            echo "selected";
                                        } ?>>全部
                                        </option>    
                                        <option value="1" <?php if ($query['status'] == '1') {
                                            echo "selected";
                                        } ?>>申请中
                                        </option>
                                        <option value="2" <?php if ($query['status'] == '2') {
                                            echo "selected";
                                        } ?>>成功
                                        </option>
                                        <option value="3" <?php if ($query['status'] == '3') {
                                            echo "selected";
                                        } ?>>失败
                                        </option>
                                    </select>
                                    
                                </div>                                                               
                                <div class="form-group" style="margin: 5px;">
                                    <label>申请时间：</label>
                                    <input type="text" class="form-control ECalendar" id="b_time" name="query[b_time]" value="<?= $query["b_time"] ? date('Y-m-d H:i',$query["b_time"]) : "" ?>"> -
                                    <input type="text" class="form-control ECalendar" id="e_time" name="query[e_time]" value="<?= $query["e_time"] ? date('Y-m-d H:i',$query["e_time"]) : "" ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-zoom-in icon-white"></i>搜索</button>
                                    <a class="btn btn-primary btn-sm" href="<?= Url::toRoute([$this->context->id . '/index']) ?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>&nbsp;&nbsp;
                                <a href="<?= Url::toRoute([$this->context->id . '/export','query'=>$query]) ?>">
                                    <button type="button" class="btn btn-xs btn-info">导出数据</button>
                                </a>&nbsp;
                                <button id="audit_btn" type="button" class="btn btn-xs btn-primary">批量审核</button>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">微信昵称</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending" class='red'>收款账户</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">收款码</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">手机号码</th>
                                       
                                       <!--  <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">账户姓名</th> -->
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("money") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("created_time") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("status") ?></th>

                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">财务操作状态</th>     

                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">审核操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($model as $list) {
                                        ?>
                                        <tr id="rowid_<?= $list->id ?>">
                                            <td><label><input type="checkbox" value="<?= $list->id ?>"></label></td>
                                            <td><?= $list->member['nickname'] ?></td> 
                                            <td class='red'>
                                                <p><?= $list->member['account_name']?></p>
                                                <p><?= $list->member['account_number'] ?></p> 
                                            </td>
                                            <td>
                                                <?php if($list->member['pay_code']):?>
                                                    <a href="javascript:;" onclick="viewImg('<?= $list->member['pay_code']?>')">查看</a>
                                                <?php else:?>
                                                    <span>未上传</span>
                                                <?php endif;?>
                                            </td>
                                            <td><?= $list->member['tel'] ?></td> 
                                           <!--  <td><?= $list->member['account_name']?></td> -->
                                            <td><?= $list->money ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list->created_time) ?></td>
                                            <td class="audit_status"><?= $status[$list->status] ?></td>
                                            <td class="audit_make">
                                            <?php
                                            if($uid==165){
                                                if($list->status==2){
                                                    if ($list->make == 1) {
                                                        echo "<a class='btn btn-primary btn-sm' href=" . Url::toRoute([$this->context->id . '/make', 'id' => $list->id]) . ">未打款</a>";
                                                    } elseif ($list->make == 2) {
                                                        echo "<a class='btn btn-danger btn-sm' href=" . Url::toRoute([$this->context->id . '/make', 'id' => $list->id]) . ">已打款</a>";
                                                    }
                                                }else{
                                                        echo '未通过审核';
                                                }
                                            }else{
                                                if($list->status==2){
                                                    if ($list->make == 1) {
                                                        echo "未打款";
                                                    } elseif ($list->make == 2) {
                                                        echo "已打款";
                                                    }
                                                }else{
                                                        echo '未通过审核';
                                                }
                                            }
                                            ?>                                                                                       
                                            </td>

                                            <td class="center">
                                                <?php
                                                if($list->status==1) { ?>
                                                    <a onclick="change(2,<?=$list->id?>)" id="yes_edit_btn" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit icon-white"></i>通过</a>
                                                    <a onclick="change(3,<?=$list->id?>)" id="no_edit_btn" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit icon-white"></i>不通过</a>
                                                <?php }
                                                ?>
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

<!--批量审核-->
<div id="audit-el" style="padding: 20px;display: none">
    <div class="input-group">
        <div class="input-group-addon">审核</div>
        <select name="audit" id="audit-select" class="form-control">
            <option value="2" >通过</option>
            <option value="3" >不通过</option>
        </select>
    </div>
</div>

<?php $this->beginBlock('footer'); ?>
<script>
    function change(status,id){
        admin_tool.confirm('请确认此操作', function () {
            var csrfToken = "<?=Yii::$app->request->csrfToken?>";
            $.ajax({
                type: "POST",
                url:  "<?=Url::toRoute($this->context->id . '/change')?>",
                data: {'status': status, 'id': id, '_csrf': csrfToken},
                dataType: "text",
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    alert("出错了，" + textStatus);
                },
                success: function (data) {
                    if(data==100){
                        layer.msg('操作成功', {icon:1, time:1500}, function () {
                            location.reload();
                            // $row = $('#rowid_' + id);
                            // if (status === 2) {
                            //     $row.find('.audit_status').html('成功');
                            //     $row.find('.audit_make').html('未打款');
                            // } else {
                            //     $row.find('.audit_status').html('失败');
                            //     $row.find('.audit_make').html('未通过审核');
                            // }
                            // $row.find('#yes_edit_btn, #no_edit_btn').hide();
                        });
                    }else{
                        layer.alert('操作失败',{icon:2});
                    }
                }
            })
        })
    }

    // 批量审核
    $('#audit_btn').on('click',function () {
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
        layer.open({
            type:1,
            title:'批量审核',
            btn:['确定','取消'],
            content:$('#audit-el'),
            yes:function () {
                var status = $('select[name="audit"]').val();
                layer.load(2);
                batchAudit(status,ids)
            }
        })
    });
    function batchAudit(status,ids) {
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        $.post('<?= Url::to(['admin-withdraw/batch-audit'])?>',{_csrf: _csrf,status:status,ids:ids},function (res) {
            layer.closeAll();
            if(res.status === 200){
                layer.msg(res.msg,{icon:1,time:1500},function () {
                    location.reload();
                });
            }else{
                layer.msg(res.msg,{icon:2,time:1500});
            }
        },'json')
    }

    // 查看收款码
    function viewImg(src) {
        var json = {
            "data": [
                {
                    "src": src,
                }
            ]
        };
        layer.photos({
            photos: json,
            shade:0.3
        });
    }
</script>
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
</script>
<?php $this->endBlock(); ?>

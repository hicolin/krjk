<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\controllers\PublicController;

$modelLabel = new \backend\models\AdminCount()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<script src="<?=Url::base()?>/mobile/web/js/ajaxfileupload.js"></script>
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
                                    <label>姓名:</label>
                                    <input type="text" class="form-control" id="query[name]" name="query[name]" value="<?= isset($query["name"]) ? $query["name"] : "" ?>">
                                    <label>手机号:</label>
                                    <input type="text" class="form-control" id="query[tel]" name="query[tel]" value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                                    <label>产品名称:</label>
                                    <input type="text" class="form-control" id="query[p_name]" name="query[p_name]" value="<?= isset($query["p_name"]) ? $query["p_name"] : "" ?>">
                                    <label>匹配成功:</label>
                                    <select name="query[is_match]" class="form-control" id="query[is_match]">
                                        <option value="3">全部</option>
                                        <option value="1" <?=$query["is_match"]==1?'selected':''?>>是</option>
                                        <option value="0" <?=(isset($query["is_match"]) && ($query["is_match"]<1))?'selected':''?>>否</option>
                                        <option value="3" <?=$query["is_match"]==3?'selected':''?>>失败</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label>导入时间：</label>
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
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>&nbsp;&nbsp;&nbsp;
                                <a id="excel_btn" href="javascript:;" class="btn btn-xs btn-primary">导入数据</a>&nbsp;&nbsp;&nbsp;
                                <a href="<?=Url::toRoute(['public/download-file','file'=>'/backend/web/excel/count.csv'])?>"><button type="button" class="btn btn-xs btn-primary">模板下载</button></a>&nbsp;&nbsp;&nbsp;
                                <a href="<?=Url::toRoute([$this->context->id.'/export','query'=>$query])?>"><button type="button" class="btn btn-xs btn-primary">导出数据</button></a>&nbsp;&nbsp;&nbsp;
                                <a href="<?=Url::toRoute([$this->context->id.'/index','is_match'=>2])?>"><button type="button" class="btn btn-xs btn-warning">重复数据</button></a>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <!--<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">头像</th>-->
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("tel") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("name") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("p_name") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("money") ?>/元</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">返佣类型</th>                                            
                                        <!--<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?/*= $modelLabel->getAttributeLabel("commission_money") */?>/元</th>-->
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("apply_rate") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("is_match") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("created_time") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">分佣比例/%</th>
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
                                            <td><?= $list->tel ?></td>
                                            <td><?= $list->name ?></td>
                                            <td><?= $list->p_name ?></td>
                                            <td><?= $list->money ?></td>
                                            <td>
                                              <?php  if($list->fy_type==1){ ?>
                                                    cpa固定返佣（元)
                                               <?php }else if($list->fy_type==2){ ?>
                                                    cps %百分比反佣(点)

                                               <?php }else{ ?>
                                               <?php }?>

                                            </td>
                                            <td><?= $rate[$list->apply_rate] ?></td>
                                            <td>
                                                <span class="glyphicon <?php $style=[0=>'glyphicon-remove',1=>'glyphicon-ok',2=>'',3=> ''];echo $style[$list->is_match];?> btn btn-xs btn-primary"><?php $isMatch=[0=>'否',1=>'是',2=>'重复', 3=>'失败'];echo $isMatch[$list->is_match]?></span>
                                            </td>
                                            <td><?= date('Y-m-d H:i:s',$list->created_time) ?></td>
                                            <td><?= $list->commission_rate ?></td>
                                            <td class="center">
                                                <a id="edit_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/view', 'id' => $list->id]) ?>">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i>查看</a>
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
<input type="file" onChange="ajaxFileUpload()" id="hiddenFile" name="file" style="display: none;">
<?php $this->beginBlock('footer'); ?>
<script>
    function change(id) {
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute($this->context->id . '/change')?>",
            data: {"id": id},
            cache: false,
            dataType: "text",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function (data) {
                if(data==100) {
                    window.location.reload();
                }else {
                    layer.alert('修改失败',{icon:2})
                }
            }
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
    $(function(){
        $('#excel_btn').bind('click',function(){
            $('#hiddenFile').click()
        })
    })
</script>
<script>
    function ajaxFileUpload() {
        layer.load(2);
        $.ajaxFileUpload
        (
            {
                url: "<?=Url::toRoute(['public/excel'])?>", //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'hiddenFile', //文件上传域的ID
                dataType: 'JSON', //返回值类型 一般设置为json
                success: function (data)  //服务器成功响应处理函数
                {
                    if(!data) {
                        layer.alert('导入失败',{icon:2});
                        return;
                    }else {
                        layer.load(2);
                        $.ajax({
                            type: "GET",
                            url: "/phpexcel/index.php",
                            data: {"file": data},
                            cache: false,
                            dataType: "json",
                            error: function (xmlHttpRequest, textStatus, errorThrown) {
                                layer.closeAll('loading');
                                layer.alert('系统出错',{icon:2});
                                return false;
                                window.location.reload();
                            },
                            success: function (data) {
                                var csrfToken = "<?= Yii::$app->request->csrfToken ?>";
                                $.ajax({
                                    type: "POST",
                                    url: "<?= Url::toRoute($this->context->id . '/commission')?>",
                                    data: {"data": data, '_csrf': csrfToken},
                                    cache: false,
                                    dataType: "text",
                                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                                        layer.closeAll('loading');
                                        alert("出错了，" + textStatus);
                                    },
                                    success: function (data) {
                                        // alert(data)
                                        layer.closeAll('loading');
                                        if(data==100) {
                                            window.location.reload();
                                        } else {
                                            layer.msg('出错了');
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            }
        );
        return false;
    }
</script>
<script>
    $("#b_time").ECalendar({
        type:"time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp : true,   //是否转成时间戳，默认true;
        offset:[0,2],   //弹框手动偏移量;
        format:"yyyy-mm-dd",   //格式 默认 yyyy-mm-dd hh:ii;
        skin:2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step:10,   //选择时间分钟的精确度;
        callback:function(v,e){

        } //回调函数
    });

    $("#e_time").ECalendar({
        type:"time",   //模式，time: 带时间选择; date: 不带时间选择;
        stamp : true,   //是否转成时间戳，默认true;
        offset:[0,2],   //弹框手动偏移量;
        format:"yyyy-mm-dd",   //格式 默认 yyyy-mm-dd hh:ii;
        skin:2,   //皮肤颜色，默认随机，可选值：0-8,或者直接标注颜色值;
        step:10,   //选择时间分钟的精确度;
        callback:function(v,e){

        } //回调函数
    });
</script>
<?php $this->endBlock(); ?>

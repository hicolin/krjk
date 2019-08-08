<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminBuyAgent;

$modelLabel = new \backend\models\AdminBuyAgent()
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
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
                                    <label>订单号:</label>
                                    <input type="text" class="form-control" id="query[order_sn]" name="query[order_sn]" value="<?= isset($query["order_sn"]) ? $query["order_sn"] : "" ?>">
                              </div>

                              <div class="form-group" style="margin: 5px;">
                                <label> 支付方式:</label>
                                  <select name="query[pay_id]" id="query['pay_id']">
                                    <option value="-1" style="width: 150px;">请选择</option>
                                    <<?php foreach ($payname as $key => $value): ?>
                                            <option value="<?=$value->id?>" <?= isset($query["pay_id"])&&$query["pay_id"]==$value->id ? 'selected' : "" ?> ><?=$value->pay_name?></option>
                                    <?php endforeach ?>
                                  </select>
                                    
                              </div>

                            <div class="form-group" style="margin: 5px;">
                                <label> 购买会员类别:</label>
                                  <select name="query[grade_id]" id="query['grade_id']">
                                    <option value="-1" style="width: 150px;">请选择</option>
                                    <<?php foreach ($grade_name as $key => $value): ?>
                                            <option value="<?=$value->id?>" <?= isset($query["grade_id"])&&$query["grade_id"]==$value->id ? 'selected' : "" ?> ><?=$value->grade_name?></option>
                                    <?php endforeach ?>
                                  </select>
                                    
                              </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label>购买时间：</label>
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
                                <a href="<?= Url::toRoute([$this->context->id . '/export']) ?>">
                                    <button id="delete_btn" type="button" class="btn btn-xs btn-info">导出数据</button>
                                </a>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">微信昵称</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("money") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">手机号码</th> 
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">支付方式</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">会员类别</th>                                             
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">订单</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">凭证</th>      
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">客户备注</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">拒绝理由</th>                                                                  
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("created_time") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">状态</th>                                            
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
                                            <td><label><input type="checkbox" value="<?= $list->id ?>" ></label></td>
                                            <td><?= $list->member['nickname'] ?></td>
                                            <td><?= $list->money ?></td>
                                            <td><?= $list->member['tel'] ?></td>
                                            <td><?= $list->paytype['pay_name']?></td>
                                            <td><?= $list->grade['grade_name']?></td>
                                            <td><?= $list->order_sn ?></td>
                                            <td><a onclick="big('<?= $list->img?>')">点击查看</a></td>
                                            <td><?= $list->content ?></td>
                                            <td><?= $list->ju_content ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list->created_time) ?></td>
                                            <td class="center">
                                                  <?php if($list->status==1){ ?>
                                                    审核通过
                                                  <?php }else{ ?>
                                                    <a class="status btn btn-primary btn-sm" >
                                                    <i class="glyphicon glyphicon-edit icon-white"></i><?=$list->status==-2?'待审核':($list->status ==-1?'审核拒绝':'')?></a>
                                                  <?php }?>
                                                  <input type="hidden" name="id"  value="<?=$list->id?>">
                                                  <input type="hidden" name="status" value="<?=$list->status?>">
                                                  <input type="hidden" name="grade_id" value="<?=$list->grade_id?>">
                                            </td> 
                                            <td class="center">
                                                <a id="delete_btn" onclick="deleteAction('<?= $list->id ?>')"
                                                   class="btn btn-danger btn-sm" href="javascript:;"> <i
                                                            class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <style type="text/css">
                                        #layui-layer1{
                                            
                                        }
                                    </style>
                                    <script type="text/javascript">                          
                                             function big(path){
                                                layer.open({
                                                    type: 1,
                                                    title: false,
                                                    closeBtn: 0,
                                                    // area: '500px',
                                                    area: ['800px','500px'], 
                                                    skin: 'layui-layer-nobg', //没有背景色
                                                    shadeClose: true,
                                                    content:"<img style='width:500px;' src='"+path+"' >"
                                                });
                                            }
                                    </script>
                                    <input type="hidden" id="status_id" name=""> 
                                    <input type="hidden" id="grade_id" name="">
                                    <!-- huoqu -->
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
<div style="display: none;width: 440px;height: 200px;padding: 18px;font-size: 15px" id="status_box">
    <label style="margin-right: 5px"><input type="radio" value="-2" name="status" id="wxd">待审核</label>
    <label style="margin-right: 5px"><input type="radio" value="1" name="status">审核通过</label>
    <label style="margin-right: 5px"><input type="radio" value="-1" name="status">审核拒绝</label>
    <br>
    <div style="display:none;" id="je_box">
        核实后购买金额：
        <input type="" name="je" id="je">
    </div>
    <br/>
    <div style="display:none;" id="cont">
        备注：
       <textarea class="comment"></textarea>
    </div>
    <br><br>
    <button id="sub_status" type="button" style="width:50px;height: 30px;font-size: 15px;" class="btn btn-xs btn-primary">确定</button>
    <input type="hidden" value="" id="status_id">
</div>

<?php $this->beginBlock('footer'); ?>
<script>
    $(".status").on("click",function(){
        var id = $(this).next().val();
        var grade_id = $(this).next().next().next().val();
        $('#status_id').val(id);
        $('#grade_id').val(grade_id);
        var status =$(this).next().next().val();
        $("input[name='status']").each(function () {
            if ($(this).val() == status ) {
                $(this).attr("checked", "checked");
            }
        });
        if (status == 1) { //拒绝
                $("#je_box").css('display','block');
                $("#cont").css('display','none');
        }else if (status == -1) {
                $("#cont").css('display','block');
                $("#je_box").css('display','none');
        }else{
                $("#cont").css('display','none');
                $("#je_box").css('display','none');
            }
        var wxd = document.getElementById('wxd');
        wxd.checked = true;
        $("#cont").css('display','none');
        $("#je_box").css('display','none');
        var html = $('#status_box');
        layer.open({
            content: html
            ,type:1
            ,title: ['修改状态',
                ' width:100%; height:50px; line-height:50px; font-size:14px; color:#999; background:none;']
            ,area: '450px'
        });

    });
$(document).ready(function() {
    $('input[type=radio][name=status]').change(function() {
        if (this.value == 1) {
            $("#je_box").css('display','block');
            $("#cont").css('display','none');
        }
        else if (this.value == -1) {
            $("#cont").css('display','block');
            $("#je_box").css('display','none');
        }else{
            $("#cont").css('display','none');
            $("#je_box").css('display','none');
        }
    });
});
$('#sub_status').click(function(){
        var id = $('#status_id').val();
        var status = $("input[name='status']:checked").val();
        var grade_id =$("#grade_id").val();
        var je = $('#je').val();
    if(status == 1)
    {
        if(je==""||je=="0"||je=="0.00")
        {
            layer.tips('请输入价格',"#je",{tips:1,time:2000});
            return false;
        }
    }
        var cont = $(".comment").val();
        var getCsrfToken = "<?=Yii::$app->getRequest()->getCsrfToken()?>";
        $.ajax({
            url:"<?=Url::toRoute($this->context->id.'/status')?>",
            type:"POST",
            data:{'id':id,'status':status,'je':je,'cont':cont,'grade_id':grade_id,'_csrf':getCsrfToken},
            success:function(data){
                // alert(data);return;
                if(data == 1){
                    layer.msg( '修改成功',{icon:1,time:1000},function(){
                        window.location.reload();
                    });
                } else{
                    layer.msg('修改失败',{icon:2});
                }
            }
        })
    })
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
</script>
<?php $this->endBlock(); ?>

<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .create-content{padding: 15px 15px 0}
    .award{padding: 5px 20px 0;width: 350px}
    .award .item{display: flex;justify-content: space-between;line-height: 40px;border-bottom: 1px solid #eee;padding: 0 5px}
</style>
<?php $this->endBlock(); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="javascript:;" onclick="createAction()"
                               class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="col-sm-12">
                    <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">用户手机号</div>
                            <input type="text" class="form-control" id="query[tel]" name="query[tel]"
                                   value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <button type="submit" class="btn btn-primary btn-sm"><i
                                    class="glyphicon glyphicon-zoom-in icon-white"></i>搜索
                        </button>
                        <a class="btn btn-primary btn-sm"
                           href="<?= Url::toRoute([$this->context->id . '/' . Yii::$app->controller->action->id]) ?>">
                            <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                    </div>
                    <?php ActiveForm::end();?>
                </div>

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <hr/>
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger" onclick="delAction()">批量删除</button>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">ID</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">用户</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">金额/元</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">备注</span></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">时间</span></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $list):?>
                                        <tr>
                                            <td><label><input type="checkbox" value="<?= $list['id'] ?>"></label></td>
                                            <td><?= $list['id'] ?></td>
                                            <td><?= $list['member']['tel'] ?></td>
                                            <td><?= $list['money'] ?></td>
                                            <td><?= $list['remark'] ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list['create_time'])?></td>
                                            <td class="center">
                                                <a id="delete_btn" onclick="delAction('<?= $list['id'] ?>')"
                                                   class="btn btn-danger btn-sm" href="javascript:;"> <i
                                                            class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
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

<!--添加-->
<div class="create-content" style="display: none">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">用户手机号</div>
            <input type="text" class="form-control"  name="tel" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;金&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <input type="number" class="form-control" name="money" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <input type="text" class="form-control"  name="remark" value="">
        </div>
    </div>
</div>

<!--奖励-->
<div class="award" style="display: none">
    <div class="item">
        <span>用户：</span>
        <span id="tel"></span>
    </div>
    <div class="item">
        <span>昵称：</span>
        <span id="nickname"></span>
    </div>
    <div class="item">
        <span>账户余额：</span>
        <span id="available_money"></span>
    </div>
    <div class="item">
        <span>奖励金额：</span>
        <span id="money" style="color:red"></span>
    </div>
    <div class="item">
        <span>结算后账户余额：</span>
        <span id="balance"></span>
    </div>
    <div class="item">
        <span>备注：</span>
        <span id="remark"></span>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    function delAction(id) {
        var ids = [];
        if (id) {
            ids[0] = id;
        } else {
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
                    success: function (res) {
                        if(res.status === 200){
                            window.location.reload();
                        }
                    }
                });
            });
        } else {
            layer.msg('请选择你要删除的记录');
        }
    }

    // 添加
    function createAction(){
       window.topLayer = layer.open({
            type:1,
            title:'发放奖励',
            content:$('.create-content'),
            btn:['确定','取消'],
            yes:function () {
                getUserInfo();
            }
        })
    }

    // 获取用户信息
    function getUserInfo() {
        var tel = $('input[name="tel"]').val();
        var money = parseFloat($('input[name="money"]').val());
        var remark = $('input[name="remark"]').val();
        if(!tel){
            layer.msg('手机号不能为空',{time:1500});
            return false;
        }
        if(!/^1\d{10}$/.test(tel)){
            layer.msg('手机号格式不正确',{time:1500});
            return false;
        }
        if(!money){
            layer.msg('奖励金额不能为空',{time:1500});
            return false;
        }
        if(isNaN(money) || money <= 0){
            layer.msg('奖励金额不正确',{time:1500});
            return false;
        }
        if(!remark){
            layer.msg('备注不能为空',{time:1500});
            return false;
        }
        var index = layer.load(3);
        $.get('<?= Url::to(['admin-award/get-user-info'])?>',{tel:tel},function (res) {
            layer.close(index);
            if(res.status === 200){
                layer.close(topLayer);
                award(money,remark,res.data);
            }else{
                layer.msg(res.msg,{icon:2,time:1500});
            }
        },'json')
    }

    // 发放奖励
    function award(money,remark,data) {
        var total = parseFloat(data.available_money) + parseFloat(money);
        $('#tel').html(data.tel);
        $('#nickname').html(data.nickname);
        $('#available_money').html(data.available_money);
        $('#money').html(money);
        $('#remark').html(remark);
        $('#balance').html(total);
        layer.open({
            type:1,
            title:'信息核对',
            content:$('.award'),
            btn:['确定','取消'],
            yes:function () {
                layer.load(3);
                $.get('<?= Url::to(['admin-award/award'])?>',{userId:data.id,money:money,remark:remark},function (res) {
                    layer.closeAll();
                    if(res.status === 200){
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.reload();
                        })
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500})
                    }
                },'json')
            }
        })
    }
</script>
<?php $this->endBlock();?>

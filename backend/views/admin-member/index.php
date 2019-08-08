<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminMember;

$modelLabel = new \backend\models\AdminMember()
?>
<?php $this->beginBlock('header'); ?>
<script src="<?= Url::base()?>/backend/web/plugins/laydate/laydate.js"></script>
<style>
    .statistic-content{background-color: #fff;margin-top: 0.1rem;padding: 1rem;width: 30rem}
    .report-group{text-align: center}
    .report-title{background-color: #eee;width: 80%;border-radius: 0.1rem;text-align: center;font-size: 0.8rem;height: 2.5rem;line-height:2.5rem;margin: auto}
    .report-list{margin: 1rem}
    .report-list .report-item{display: flex;justify-content: space-between;line-height:2.5rem;border-bottom: 1px solid #eee }
    .report-item span:nth-child(2){color:#aaa}
</style>
<?php $this->endBlock(); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/create']) ?>" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>&nbsp;&nbsp;
                            <?php
                            if($is_agent) { ?>
                                <a id="back_btn" href="<?= $pre_url ?>" class="btn btn-xs btn-info">上一级</a>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 微信昵称:</label>
                                    <input type="text" class="form-control" id="query[nickname]" name="query[nickname]" value="<?= isset($query["nickname"]) ? $query["nickname"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 真实姓名:</label>
                                    <input type="text" class="form-control" id="query[realname]" name="query[realname]" value="<?= isset($query["realname"]) ? $query["realname"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 手机号:</label>
                                    <input type="text" class="form-control" id="query[tel]" name="query[tel]" value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 会员等级:</label>
                                    <select name="query[grade]" class="form-control">
                                        <option value="0"> 全部 </option>
                                        <option value="4" <?= isset($query['grade']) && $query['grade'] == 4 ? 'selected' : '' ?>>普通会员</option>
                                        <?php foreach ($grade as $key => $value): ?>
                                            <option value="<?=$value->id?>" <?= isset($query["grade"])&&($query["grade"]==$value->id)? 'selected' : "" ?>><?=$value->grade_name?> </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 合伙人:</label>
                                    <select name="query[is_partner]" class="form-control">
                                        <option value=""> 全部 </option>
                                        <option value="1" <?= $query['is_partner'] == 1 ? 'selected': ''?>> 否 </option>
                                        <option value="2" <?= $query['is_partner'] == 2 ? 'selected': ''?>> 是 </option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 状态:</label>
                                    <select name="query[is_block]" class="form-control">
                                        <option value=""> 全部 </option>
                                        <option value="1" <?= $query['is_block'] == 1 ? 'selected': ''?>> 正常 </option>
                                        <option value="2" <?= $query['is_block'] == 2 ? 'selected': ''?>> 锁定 </option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label> 注册时间：</label>
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

                        <div class="row" style="margin-top: 15px">
                            <div class="col-sm-12">
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>&nbsp;&nbsp;
                                <a href="<?=Url::toRoute(['admin-member/export'])?>">
                                    <button id="delete_btn" type="button" class="btn btn-xs btn-info">导出数据</button>
                                </a>
                                <span class="text-primary" style="display: inline-block;margin-left: 20px">可提现金额总和：<i style="color: red;font-weight: bolder"><?= $availableMoneySum?></i> 元</span>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("nickname") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("real_name") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">推荐人</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("grade") ?></th>                                       
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("tel") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("promotion_commission") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("dai_commission") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("available_money") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">合伙人</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("is_block") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("created_time") ?></th>
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
                                            <td><label><input type="checkbox" value="<?= $list['id'] ?>"></label></td>
                                            <td><?= $list['nickname']?></td>
                                            <td><?= $list['real_name'] ?></td>
                                            <td><?= $list['pre_tel'] ? $list['pre_tel'].' | '.$list['pre_name']: '' ?></td>
                                            <td><?= isset($list['grade'])&&$list['grade']==0?'普通会员': $list['grades']['grade_name']?></td>
                                            <td><?= $list['tel'] ?></td>
                                            <td><?= $list['promotion_commission'] ?></td>
                                            <td><?= $list['dai_commission'] ?></td>
                                            <td><?= $list['available_money'] ?></td>
                                            <td>
                                                <?php if($list['is_partner'] == 2):?>
                                                <span>是</span><a href="javascript:;" id="statistic" onclick="viewStatistic('<?= $list['id']?>','<?= $list['nickname']?>')">(查看统计)</a>
                                                <?php else:?>
                                                <span>否</span>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <span class="label <?php $color=[1=>'bg-green',2=>'bg-red'];echo $color[$list['is_block']];?>" style="cursor: pointer" onclick="changeBlock(<?= $list['is_block']?>,<?= $list['id']?>)">
                                                    <i class="glyphicon glyphicon-pencil icon-white"></i> <?php $status=[1=>'正常',2=>'锁定'];echo $status[$list['is_block']] ?>
                                                </span>
                                            </td>
                                            <td><?= date('Y-m-d H:i:s',$list['created_time']) ?></td>
                                            <td class="center">
                                                <a id="view_btn" class="btn btn-primary btn-sm"
                                                   href="<?=Url::toRoute([$this->context->id . '/agent', 'id' => $list['id']])?>">
                                                    <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看下级</a>
                                                <a id="commission_btn" class="btn btn-primary btn-sm" href="<?= Url::toRoute([$this->context->id . '/commission', 'id' => $list['id']]) ?>">
                                                    <i class="glyphicon glyphicon-zoom-in icon-white"></i>返佣明细</a>
                                                <a id="edit_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/update', 'id' => $list['id']]) ?>">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
                                                <a id="delete_btn" onclick="deleteAction('<?= $list['id'] ?>')"
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

<!--查看统计-->
<div class="statistic-content" style="display: none">
    <div class="report-group">
        <div class="report-title">全部</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><i class="a-m"></i> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><i class="a-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><i class="a-cpa-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><i class="a-cps-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><i class="a-c"></i> 元</span>
            </div>
        </div>
    </div>
    <div class="report-group">
        <div class="report-title">本月</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><i class="m-m"></i> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><i class="m-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><i class="m-cpa-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><i class="m-cps-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><i class="m-c"></i> 元</span>
            </div>
        </div>
    </div>
    <div class="report-group">
        <div class="report-title">本周</div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><i class="w-m"></i> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><i class="w-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><i class="w-cpa-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><i class="w-cps-o"></i> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><i class="w-c"></i> 元</span>
            </div>
        </div>
    </div>

    <div class="report-group">
        <div class="report-title">
            按月查询： <input type="text" value="" id="pick_date" size="12" style="border: 1px solid #ccc;text-align: center;height: 2rem">
        </div>
        <div class="report-list">
            <div class="report-item">
                <span>团队成员</span>
                <span><span class="pick_date_member">--</span> 人</span>
            </div>
            <div class="report-item">
                <span>订单数量</span>
                <span><span class="pick_date_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>CPA订单</span>
                <span><span class="pick_date_cpa_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>CPS订单</span>
                <span><span class="pick_date_cps_order">--</span> 单</span>
            </div>
            <div class="report-item">
                <span>收入金额</span>
                <span><span class="pick_date_award">--</span> 元</span>
            </div>
        </div>
    </div>
</div>

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


    $('#delete_btn').click(function (e) {
        e.preventDefault();
        deleteAction('');
    });

    // 更改状态
    function changeBlock(is_block,id) {
        $.get('<?= Url::toRoute(['admin-member/change-block'])?>',{is_block:is_block,id:id},function (res) {
            if(res.status === 200){
                layer.msg(res.msg,{icon:1,time:1500},function () {
                    location.reload();
                });
            }else{
                layer.msg(res.msg,{icon:2,time:1500},function () {
                    location.reload();
                });
            }
        },'json')
    }

    // 查看统计
    var user_id = ''; // 按时间查询时使用
    function viewStatistic(userId,nickname){
        user_id = userId;
        layer.load(3);
        $.get('<?= Url::to(['admin-member/statistic'])?>',{userId:userId},function (res) {
            layer.closeAll();
            if(res.status === 200){
                $('.a-m').html(res.data.allMembers);
                $('.a-o').html(res.data.allOrders);
                $('.a-cpa-o').html(res.data.allCpaOrders);
                $('.a-cps-o').html(res.data.allCpsOrders);
                $('.a-c').html(res.data.allAward);
                $('.m-m').html(res.data.monthMembers);
                $('.m-o').html(res.data.monthOrders);
                $('.m-cpa-o').html(res.data.monthCpaOrders);
                $('.m-cps-o').html(res.data.monthCpsOrders);
                $('.m-c').html(res.data.monthAward);
                $('.w-m').html(res.data.weekMembers);
                $('.w-o').html(res.data.weekOrders);
                $('.w-cpa-o').html(res.data.weekCpaOrders);
                $('.w-cps-o').html(res.data.weekCpsOrders);
                $('.w-c').html(res.data.weekAward);
                layer.open({
                    type:1,
                    title:[nickname,'font-weight:bold'],
                    content:$('.statistic-content'),
                })
            }else{
                layer.msg(res.msg,{icon:2,time:1500})
            }
        },'json');
    }

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

    laydate.render({
        elem: '#pick_date'
        ,type: 'month'
        ,done: function(value, date){
            var _csrf = '<?= Yii::$app->request->csrfToken ?>';
            var index = layer.load(3);
            $.post('<?= Url::to(['admin-member/statistic'])?>', {pickDate: value, userId: user_id, _csrf: _csrf}, function (res) {
                layer.close(index);
                if (res.status == 200) {
                    $('.pick_date_member').html(res.url.members);
                    $('.pick_date_order').html(res.url.orders);
                    $('.pick_date_cpa_order').html(res.url.cpaOrders);
                    $('.pick_date_cps_order').html(res.url.cpsOrders);
                    $('.pick_date_award').html(res.url.award ? res.url.award : 0);
                }
            }, 'json')
        }
    });

</script>
<?php $this->endBlock(); ?>

<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<?php $this->beginBlock('header'); ?>
<style>
    .update-cate{padding: 15px 15px 0}
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
                            <div class="input-group-addon">分类名称</div>
                            <input type="text" class="form-control" id="query[name]" name="query[name]"
                                   value="<?= isset($query["name"]) ? $query["name"] : "" ?>">
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
                                            aria-sort="ascending">分类名称</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">排序</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">创建时间</span></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($model as $list):?>
                                        <tr id="rowid_$list->id">
                                            <td><label><input type="checkbox" value="<?= $list['id'] ?>"></label></td>
                                            <td><?= $list['id'] ?></td>
                                            <td><?= $list['name'] ?></td>
                                            <td><?= $list['sort'] ?></td>
                                            <td><?= date('Y-m-d H:i:s',$list['create_time'])?></td>
                                            <td class="center">
                                                <a id="edit_btn" class="btn btn-primary btn-sm"
                                                   href="javascript:;" onclick="updataAction('<?= $list['id']?>','<?= $list['name']?>','<?= $list['sort']?>')">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
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

<!--修改内容-->
<div class="update-cate" style="display: none">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">分类名称</div>
            <input type="text" class="form-control"  name="name" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;排&nbsp;&nbsp;序&nbsp;&nbsp;&nbsp;&nbsp;</div>
            <input type="number" class="form-control" name="sort" value="">
        </div>
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

    // 更新
    function updataAction(id,name,sort) {
        $('input[name="name"]').val(name);
        $('input[name="sort"]').val(sort);
        layer.open({
            type:1,
            title:'修改',
            content:$('.update-cate'),
            btn:['确定','取消'],
            yes:function () {
                var _csrf = '<?= Yii::$app->request->csrfToken?>';
                var name = $('input[name="name"]').val();
                var sort = parseInt($('input[name="sort"]').val());
                if(!name){
                    layer.msg('分类名称不能为空',{time:1500});
                    return false;
                }
                if(!sort){
                    layer.msg('排序不能为空',{time:1500});
                    return false;
                }
                $.post('<?= Url::to(['admin-product-category/update'])?>',{_csrf:_csrf,id:id,name:name,sort:sort},function (res) {
                    if(res.status === 200){
                        layer.closeAll();
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.reload();
                        });
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500});
                    }
                },'json')
            }
        })
    }

    // 添加
    function createAction(){
        $('input[name="name"]').val('');
        $('input[name="sort"]').val('');
        layer.open({
            type:1,
            title:'添加',
            content:$('.update-cate'),
            btn:['确定','取消'],
            yes:function () {
                var _csrf = '<?= Yii::$app->request->csrfToken?>';
                var name = $('input[name="name"]').val();
                var sort = parseInt($('input[name="sort"]').val());
                if(!name){
                    layer.msg('分类名称不能为空',{time:1500});
                    return false;
                }
                if(!sort){
                    layer.msg('排序不能为空',{time:1500});
                    return false;
                }
                $.post('<?= Url::to(['admin-product-category/create'])?>',{_csrf:_csrf,name:name,sort:sort},function (res) {
                    if(res.status === 200){
                        layer.closeAll();
                        layer.msg(res.msg,{icon:1,time:1500},function () {
                            location.reload();
                        });
                    }else{
                        layer.msg(res.msg,{icon:2,time:1500});
                    }
                },'json')
            }
        })
    }
</script>
<?php $this->endBlock(); ?>

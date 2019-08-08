<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminAnnounce;

$modelLabel = new \backend\models\AdminAnnounce()
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
                                <div class="form-group" style="margin: 5px; ">
                                    <label>用户:</label>
                                    <input type="text" class="form-control" id="query[tel]" name="query[tel]" value="<?= isset($query["tel"]) ? $query["tel"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px; ">
                                    <label>标题:</label>
                                    <input type="text" class="form-control" id="query[title]" name="query[title]" value="<?= isset($query["title"]) ? $query["title"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="glyphicon glyphicon-zoom-in icon-white"></i>搜索
                                    </button>
                                    <a class="btn btn-primary btn-sm"
                                       href="<?= Url::toRoute([$this->context->id . '/index']) ?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="col-sm-12">
                            <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>
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
                                        aria-sort="ascending">标题</th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending">内容</th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending">状态</th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending">创建时间</th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending">操作
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($model as $list) {
                                    ?>
                                    <tr id="rowid_$list->art_id">
                                        <td><label><input type="checkbox" value="<?= $list->id ?>"></label></td>
                                        <td><?= $list->id ?></td>
                                        <td><?= $list->member['tel'] ?></td>
                                        <td><?= $list->title ?></td>
                                        <td><?= $list->content ?></td>
                                        <td>
                                            <span class="<?php $color=[1=>'text-red',2=>'text-green'];echo $color[$list['is_read']]?>">
                                                <?php $text=[1=>'未读',2=>'已读'];echo $text[$list['is_read']]?>
                                            </span>
                                        </td>
                                        <td><?= date('Y-m-d H:i:s', $list->create_time) ?></td>
                                        <td class="center">
                                            <a id="delete_btn" onclick="deleteAction('<?= $list->id ?>')" class="btn btn-danger btn-sm" href="javascript:;">
                                            <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>
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
    // 删除
    function deleteAction(id) {
        layer.confirm('确定要删除吗？',{},function () {
            $.get('<?= Url::toRoute(['admin-notice/del'])?>',{id:id},function (res) {
                if(res.status === 200){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        window.location.reload();
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:2000});
                }
            },'json')
        })
    }

    //批量删除
    $('#delete_btn').click(function() {
        var ids = '';
        if ($('tbody input:checked').length === 0) {
            layer.msg('请选择你要删除的记录');
            return false;
        } else {
            layer.confirm('确定要删除吗？', {}, function () {
                $('tbody input:checked').each(function (index) {
                    ids += ($(this).val() + ',');
                });
                var csrfToken = '<?= Yii::$app->request->csrfToken?>';
                $.post('<?=Url::toRoute(['admin-notice/delrecord'])?>', {ids: ids, _csrf: csrfToken}, function (res) {
                    if (res.status === 200) {
                        layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                            location.reload();
                        })
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 2000});
                    }
                }, 'json')
            })
        }
    });
</script>
<?php $this->endBlock(); ?>

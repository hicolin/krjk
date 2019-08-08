<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminCommemts;

$modelLabel = new \backend\models\AdminCommemts()
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
                        <!--<div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<? /*=Url::toRoute([$this->context->id.'/create'])*/ ?>" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>
                            &nbsp;&nbsp;

                        </div>-->
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                                <div class="form-group" style="margin: 5px;">
                                    <label>用户名:</label>
                                    <input type="text" class="form-control" id="query[user_id]" name="query[user_id]"
                                           value="<?= isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                                    <label>关键字:</label>
                                    <input type="text" class="form-control" id="query[content]" name="query[content]"
                                           value="<?= isset($query["content"]) ? $query["content"] : "" ?>">
                                    <label>审核状态:</label>
                                    <select name="query[status]" id="query[status]"
                                            style="height:30px; width: 125px;border: 1px solid #ccc;">
                                        <option value="" <?= $query['status'] == NULL ? 'selected' : '' ?>>全　部</option>
                                        <option value="1" <?= $query['status'] == 1 ? 'selected' : '' ?>>待审核</option>
                                        <option value="2" <?= $query['status'] == 2 ? 'selected' : '' ?>>通过</option>
                                        <option value="3" <?= $query['status'] == 3 ? 'selected' : '' ?>>驳回</option>
                                    </select>

                                    <label>会员等级:</label>
                                    <select name="query[grade]">
                                             <option value="0"> 请选择 </option>
                                        <?php foreach ($grade as $key => $value): ?>
                                             <option value="<?=$value->id?>" <?= isset($query["grade"])&&($query["grade"]==$value->id)? 'selected' : "" ?>><?=$value->grade_name?> </option>
                                        <?php endforeach ?>
                                           
                                    </select>
                                    <label>是否推荐:</label>
                                    <select name="query[recommend]" id="query[recommend]"
                                           style="height:30px; width: 125px;border: 1px solid #ccc;">
                                        <option value="" <?= $query['recommend'] == NULL ? 'selected' : '' ?>>全　部
                                        </option>
                                        <option value="2" <?= $query['recommend'] == 2 ? 'selected' : '' ?>>是</option>
                                        <option value="1" <?= $query['recommend'] == 1 ? 'selected' : '' ?>>否</option>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="glyphicon glyphicon-zoom-in icon-white"></i>搜索
                                    </button>

                                    <a class="btn btn-primary btn-sm"
                                       href="<?= Url::toRoute([$this->context->id . '/index']) ?>"> <i
                                                class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
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
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("user_id") ?></th>

                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("grade_id") ?></th>

                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("content") ?></th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("create_time") ?></th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("recommend") ?></th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                        aria-sort="ascending"><?= $modelLabel->getAttributeLabel("status") ?></th>
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
                                        <td><?= $list->grade['grade_name'] ?></td>
                                        <td><?= $list->content ?></td>
                                        <td><?= date('Y-m-d H:i:s', $list->create_time) ?></td>
                                        <td>
                                            <?php
                                            if ($list->recommend == 1) {
                                                echo "<a class='btn btn-primary btn-sm' href=" . Url::toRoute([$this->context->id . '/recommend', 'id' => $list->id]) . " >未推荐</a>";
                                            } elseif ($list->recommend == 2) {
                                                echo "<a class='btn btn-danger btn-sm' href=" . Url::toRoute([$this->context->id . '/recommend', 'id' => $list->id]) . " >已推荐</a>";
                                            }
                                            ?></td>
                                        <td>
                                            <?php
                                            if ($list->status == 1) {
                                                echo "<a class='btn btn-primary btn-sm' href=" . Url::toRoute([$this->context->id . '/status', 'id' => $list->id,'status'=>2]) . ">通过</a>";echo "&nbsp&nbsp";
                                                echo "<a class='btn btn-danger btn-sm' href=" . Url::toRoute([$this->context->id . '/status', 'id' => $list->id,'status'=>3]) . ">驳回</a>";
                                              
                                            } elseif ($list->status == 2) {
                                                echo "已通过";
                                            }elseif($list->status ==3){

                                                echo "驳回";
                                            }
                                            ?>

                                        </td>
                                        <td class="center">
                                            <a id="view_btn"  class="btn btn-primary btn-sm"
                                               href="<?= Url::toRoute([$this->context->id . '/view', 'id' => $list->id]) ?>">
                                                <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看</a>
                                            <!--<a id="edit_btn"  class="btn btn-primary btn-sm" href="<?/*=Url::toRoute([$this->context->id.'/update','id'=>$list->id])*/ ?>"> <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>-->
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
<?php $this->endBlock(); ?>

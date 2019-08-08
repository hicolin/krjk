<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminDaiProduct;

$modelLabel = new \backend\models\AdminDaiProduct()
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
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/create']) ?>"
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
                            <div class="input-group-addon">产品名称</div>
                            <input type="text" class="form-control" id="query[title]" name="query[title]"
                                   value="<?= isset($query["title"]) ? $query["title"] : "" ?>">
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <div class="input-group">
                            <div class="input-group-addon">类型</div>
                            <select name="query[style]" id="query[style]" class="form-control">
                                <option value="">全部</option>
                                <?php $style = AdminDaiProduct::$style ?>
                                <option value="1" <?= $query['style'] == 1 ? 'selected' : ''?>><?= $style[1] ?></option>
                                <option value="2" <?= $query['style'] == 2 ? 'selected' : ''?> ><?= $style[2] ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: 5px">
                        <div class="input-group">
                            <div class="input-group-addon">分类</div>
                            <select name="query[cate_id]" id="query[cate_id]" class="form-control">
                                <option value="">全部</option>
                                <?php foreach ($categories as $category):?>
                                    <option value="<?= $category['id']?>" <?= $query['cate_id'] == $category['id'] ? 'selected' : ''?>><?= $category['name']?></option>
                                <?php endforeach;?>
                            </select>
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
                                <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("title") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("fy_info") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending"><?= $modelLabel->getAttributeLabel("title_info") ?></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">排序<br><span style="font-size: 12px;color: red;">(*数字越小越靠前)</span></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">类型</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">分类</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">前台显示/隐藏</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">是否热门显示/隐藏</th>
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
                                            <td><?= $list->title ?></td>
                                            <td><?= $list->fy_info ?></td>
                                            <td><?= $list->title_info ?></td>
                                        <td>
                                        <input type="text" name="listorder"  class="listorder<?=$list->id?>" onfocus="orderfocus('<?=$list->id?>')" onblur="listorder('<?=$list->id?>')" value="<?=$list->listorder?>"  style="width: 50px;height: 50px;text-indent: 20px;">
                                         <input type="hidden" class="focusval<?=$list->id?>">
                                        </td>
                                            <td><?php $style = AdminDaiProduct::$style;echo $style[$list->style]?></td>
                                            <td><?= $list->category->name?></td>
                                            <td> 
                                                   <?php
                                            if($list->is_open==0){
                                                echo "<a class='btn btn-primary btn-sm' href=".Url::toRoute([$this->context->id.'/status','id'=>$list->id,'type'=>$list->type])." >
                                                <span class='glyphicon glyphicon-remove'>隐藏</span></a>";
                                            }elseif($list->is_open==1){
                                                echo "<a class='btn btn-danger btn-sm' href=".Url::toRoute([$this->context->id.'/status','id'=>$list->id,'type'=>$list->type])." > <span class='glyphicon glyphicon-ok'>显示</span></a>";
                                            }
                                            ?>                      
                                                </td>
                                            <td> 
                                                   <?php
                                            if($list->is_hot==0){
                                                echo "<a class='btn btn-primary btn-sm' href=".Url::toRoute([$this->context->id.'/status2','id'=>$list->id,'type'=>$list->type])." >
                                                <span class='glyphicon glyphicon-remove'>隐藏</span></a>";
                                            }elseif($list->is_hot==1){
                                                echo "<a class='btn btn-danger btn-sm' href=".Url::toRoute([$this->context->id.'/status2','id'=>$list->id,'type'=>$list->type])." > <span class='glyphicon glyphicon-ok'>显示</span></a>";
                                            }
                                            ?>                    
                                            </td>
                                            <td class="center">
                                                <a id="edit_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/update', 'id' => $list->id]) ?>">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
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
    <script type="text/javascript">
    
    function orderfocus(id) {
       var listorders = $(".listorder"+id).val();
       $('.focusval'+id).val(listorders);
    }

    function listorder(id){
        var listorder = $(".listorder"+id).val();
        var focusval = $(".focusval"+id).val();
        $.ajax({
            type:"post",  
            url:"<?=Url::toRoute([$this->context->id . '/changeorder'])?>",
            data:{'listorder':listorder,'id':id},
            success:function(data){
                if(focusval!=listorder){     
                     window.location.href=location.href;
                }
               
            }

        })
        
    }


    </script>
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

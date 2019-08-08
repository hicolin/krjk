<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\AdminCardProgress;
$modelLabel=new \backend\models\AdminCardProgress()
?>
<?php  $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?php  $this->endBlock(); ?>
<style type="text/css">

table.table-bordered tbody td {
    border-left-width: 0;
    border-bottom-width: 0;
    line-height: 5;
}
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?=Url::toRoute([$this->context->id.'/create'])?>" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>
                            &nbsp;&nbsp;

                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'admin-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                                <div class="form-group" style="margin: 5px; ">
                                    <label>名称</label>
                                    <input type="text" class="form-control" id="query[name]" name="query[name]" value="<?= isset($query["name"]) ? $query["name"] : "" ?>">
                                </div>

    

                                 <div class="form-group" style="margin-left: 15px;width: 250px;">
                                     <label>会员权限:</label>
                                     <select name="query[grade]" id="query[grade]"
                                             style="height:30px; width: 125px;border: 1px solid #ccc;">
                                         <option value="4" <?php if ($query['grade'] == '4') {
                                             echo "selected";
                                         } ?>>全部
                                         </option>
                                         <option value="1" <?php if ($query['grade'] == '1') {
                                             echo "selected";
                                         } ?>>铜牌会员
                                         </option>
                                         <option value="2" <?php if ($query['grade'] == '2') {
                                             echo "selected";
                                         } ?>>银牌会员
                                         </option>
                                         <option value="3" <?php if ($query['grade'] == '3') {
                                             echo "selected";
                                         } ?>>金牌会员
                                         </option>
                                     </select>
                                 </div>

                               
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-zoom-in icon-white"></i>搜索</button>

                                    <a class="btn btn-primary btn-sm" href="<?= Url::toRoute([$this->context->id . '/index']) ?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>                        
                        <div class="row">
                            <hr/>
                        <div class="col-sm-12">
                            <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>
                            <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                <thead>
                                <tr role="row">
                                                                        <th><input id="data_table_check" type="checkbox"></th>
                                

<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" ><?=$modelLabel->getAttributeLabel("name")?></th>
<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" ><?=$modelLabel->getAttributeLabel("img")?></th>
<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" ><?=$modelLabel->getAttributeLabel("grade")?></th>
<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" ><?=$modelLabel->getAttributeLabel("links")?></th>
<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" ><?=$modelLabel->getAttributeLabel("type")?></th>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
foreach ($model as $list) {
?>
                                <tr id="rowid_$list->id">
                                    <td><label><input type="checkbox" value="<?=$list->id?>"></label></td>
                                                                                       
                                                                                        <td><?=$list->name?></td>
                                                                                        <td> <img id="thumb" width="80" height="60" src="<?=$list->img?>"></td>


                                                                                        <td>

                                                                           <!-- <?php
/*                                                                            if($list->permission==0){
                                                                                echo "<a class='btn btn-primary btn-sm' href=".Url::toRoute([$this->context->id.'/status','id'=>$list->id])." >
                                                                                <span class='glyphicon glyphicon-remove'>不可见</span></a>";
                                                                            }elseif($list->permission==1){
                                                                                echo "<a class='btn btn-danger btn-sm' href=".Url::toRoute([$this->context->id.'/status','id'=>$list->id])." >
                                                                                 <span class='glyphicon glyphicon-ok'>可见</span></a>";
                                                                            }
                                                                            */?>   -->
                                                                                            <a onclick="statusAction('<?= $list->id ?>')" class="btn btn-primary btn-sm" href="javascript:;">
                                                                                                <i class="glyphicon glyphicon-edit icon-white">

                                                                                                </i><?php $grade=[0=>'普通会员',1=>'铜牌会员',2=>'银牌会员',3=>'金牌会员'];echo $grade[$list->grade]?></a>
                                                                                        </td>
                                                                                  
                                                                                        <td><?=$list->links?></td>
                                                                                        <td><?=$type[$list->type]?></td>
                                                                                <td class="center">
                                
                                        <a id="edit_btn"  class="btn btn-primary btn-sm" href="<?=Url::toRoute([$this->context->id.'/update','id'=>$list->id])?>"> <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
                                        <a id="delete_btn" onclick="deleteAction('<?=$list->id?>')"  class="btn btn-danger btn-sm" href="javascript:;"> <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>
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
                                    从<?=  $pages->getPage() * $pages->getPageSize() + 1 ?>            		到 <?=  ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ?  $pageCount : $pages->totalCount?>            		 共 <?=  $pages->totalCount?> 条记录</div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate" style="text-align: right;padding-right: 50px;">
                                <?=  LinkPager::widget([
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

<?php $this->beginBlock('footer');  ?>
<script>

    function initModel(id, type, fun){
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute($this->context->id.'/view')?>",
            data: {"id":id},
            cache: false,
            dataType:"json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function(data){
                initEditSystemModule(data, type);
            }
        });
    }
    function editAction(id){
        initModel(id, 'edit');
    }

    function deleteAction(id){
        var ids = [];
        if(!!id == true){
            ids[0] = id;
        }
        else{
            var checkboxs = $('#data_table tbody :checked');
            if(checkboxs.size() > 0){
                var c = 0;
                for(i = 0; i < checkboxs.size(); i++){
                    var id = checkboxs.eq(i).val();
                    if(id != ""){
                        ids[c++] = id;
                    }
                }
            }
        }
        if(ids.length > 0){
            admin_tool.confirm('请确认是否删除', function(){
                $.ajax({
                    type: "GET",
                    url: "<?=Url::toRoute($this->context->id.'/delrecord')?>",
                    data: {"ids":ids},
                    cache: false,
                    dataType:"json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function(data){
                        for(i = 0; i < ids.length; i++){
                            $('#rowid_' + ids[i]).remove();
                        }
                        admin_tool.alert('msg_info', '删除成功', 'success');
                        window.location.reload();
                    }
                });
            });
        }
        else{
            admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
        }

    }

    function getSelectedIdValues(formId)
    {
        var value="";
        $( formId + " :checked").each(function(i)
        {
            if(!this.checked)
            {
                return true;
            }
            value += this.value;
            if(i != $("input[name='id']").size()-1)
            {
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
    function statusAction(id){
        var csrfToken = "<?=Yii::$app->request->csrfToken?>";
        var status ='';
        layer.confirm('请选择操作', {
                btn: ['普通', '金牌', '取消'],
                btn1: function () {
                    ajaxStatus(id, csrfToken, 0);
                }, btn2: function () {
                    ajaxStatus(id, csrfToken, 3);
                }
            }
        )
    }

    // 更改状态ajax
    function ajaxStatus(id,_csrf,grade) {
        layer.close();
        $.ajax({
            type:'post',
            data:{id:id,grade:grade,'_csrf': _csrf},
            url:"<?=Url::toRoute([$this->context->id.'/status'])?>",
            success:function(msg){
                if(msg==100){
                    layer.msg('操作成功',{icon:1},function(){
                        window.location.reload();
                    });
                }else if(msg==300){
                    layer.msg('操作失败',{icon:2});
                }
            }
        })
    }

</script>

<?php $this->endBlock(); ?>

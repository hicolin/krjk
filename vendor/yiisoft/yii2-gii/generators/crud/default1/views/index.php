<?php
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
echo "<?php\n";
echo "use "."yii\\bootstrap\\ActiveForm".";\n";
echo "use "."yii\\widgets\\LinkPager".";\n";
echo "use "."yii\\helpers\\Url".";\n";
echo "use "."yii\\helpers\\Html".";\n";
echo "use ".ltrim($generator->modelClass, '\\')."".";\n";
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$class = $generator->modelClass;
$pks = $class::primaryKey();
$label= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))));

echo '$modelLabel='."new "."\\".ltrim($generator->modelClass, '\\')."()\n"
?>
<?="?>"."\n"?>
<?="<?php "?> $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?="<?php "?> $this->endBlock(); ?>
<?php
$label=trim(strtolower(str_replace(' ','-',$label)),"'");
$arr=explode('\\',$generator->modelClass);;
$modelname=end($arr);
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?='<?='?>Url::toRoute([$this->context->id.'/create'])?>" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>
                            &nbsp;&nbsp;

                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <hr/>
                        <div class="col-sm-12">
                            <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>
                            <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                <thead>
                                <tr role="row">
                                    <?php
                                    $count=0;
                                    $tableSchema = $generator->getTableSchema();
                                    ?>
                                    <th><input id="data_table_check" type="checkbox"></th>
                                    <?php
                                    foreach ($tableSchema->columns as $column) {
                                        $format = $generator->generateColumnFormat($column);
                                        if (++$count < 6) {
                                            echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.'<?='.'$modelLabel->getAttributeLabel('.'"'.$column->name.'"'.')'.'?>'.'</th>'."\n";
                                        }
                                    }
                                    ?>
                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                echo "<?php\n";
                                echo 'foreach ($model as $list) {'."\n";
                                $count=0;
                                $tableSchema = $generator->getTableSchema();
                                echo "?>\n";

                                ?>
                                <tr id="rowid_<?='$list->'.$pks[0]?>">
                                    <td><label><input type="checkbox" value="<?='<?=$list->'.$pks[0].'?>'?>"></label></td>
                                    <?php
                                    foreach ($tableSchema->columns as $column) {
                                        $format = $generator->generateColumnFormat($column);
                                        if (++$count < 6) {
                                            ?>
                                            <td><?='<?=$list->'.$column->name.'?>'?></td>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <td class="center">
                                        <a id="view_btn"  class="btn btn-primary btn-sm" href="<?='<?=Url::toRoute([$this->context->id.'."'".'/view'."'".",'id'"."=>".'$list->'.$pks[0].'])?>'?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看</a>
                                        <a id="edit_btn"  class="btn btn-primary btn-sm" href="<?='<?=Url::toRoute([$this->context->id.'."'".'/update'."'".",'id'"."=>".'$list->'.$pks[0].'])?>'?>"> <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
                                        <a id="delete_btn" onclick="deleteAction('<?='<?=$list->'.$pks[0].'?>'?>')"  class="btn btn-danger btn-sm" href="javascript:;"> <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                    </td>
                                </tr>
                                <?php
                                echo "<?php\n";
                                echo "}"."\n";
                                echo '?>'."\n";
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
                                    从<?="<?= "?> $pages->getPage() * $pages->getPageSize() + 1 ?>            		到 <?="<?= "?> ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ?  $pageCount : $pages->totalCount?>            		 共 <?="<?= "?> $pages->totalCount?> 条记录</div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate" style="text-align: right;padding-right: 50px;">
                                <?="<?= "?> LinkPager::widget([
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

<?="<?php "?>$this->beginBlock('footer');  ?>
<script>

    function initModel(id, type, fun){
        $.ajax({
            type: "GET",
            url: "<?='<?= '?>Url::toRoute($this->context->id.'/view')?>",
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
                    url: "<?='<?='?>Url::toRoute($this->context->id.'/delrecord')?>",
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
<?="<?php "?>$this->endBlock(); ?>

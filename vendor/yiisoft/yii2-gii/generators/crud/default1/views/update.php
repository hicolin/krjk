<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
echo "<?php\n";
echo "use "."yii\\bootstrap\\ActiveForm".";\n";
echo "use "."yii\\widgets\\LinkPager".";\n";
echo "use "."yii\\helpers\\Url".";\n";
echo "use "."yii\\helpers\\Html".";\n";
echo "use ".ltrim($generator->modelClass, '\\')."".";\n";
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$label= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))));
echo '$modelLabel='."new "."\\".ltrim($generator->modelClass, '\\')."()\n";
?>
<?="?>"."\n"?>
<?="<?php "?> $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?="<?php "?> $this->endBlock(); ?>
<?php
$label=trim(strtolower(str_replace(' ','-',$label)),"'");
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?='<?=Url::toRoute([$this->context->id.'."'".'/index'."'".'])?>'?>" class="btn btn-xs btn-primary"><?=$label?>列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <?="<?php"?>
                    $form=ActiveForm::begin([
                    'fieldConfig' => [
                    'template' => '<div class="span12 field-box">{input}</div>{error}',
                    ],
                    'options' => [
                    'class' => 'new_user_form inline-input',
                    ],
                    'id'=>'form',
                    ])
                    ?>
                    <div class="tab-content">
                        <?php
                        $count=0;
                        $tableSchema = $generator->getTableSchema();
                        foreach ($tableSchema->columns as $column) {
                            $format = $generator->generateColumnFormat($column);
                            if (++$count < 8) {
                                echo '<div class="form-group">'."\n";
                                echo '   <label for="'.$column->name.'" class="col-sm-2 control-label" ><?php echo $modelLabel->getAttributeLabel("'.$column->name.'")?></label>'."\n";
                                echo '   <div class="col-sm-8">'."\n";
                                echo '   <?php echo $form->field($model,'."'".$column->name."'".')->textInput(["class"=>"form-control","placeholder"=>$modelLabel->getAttributeLabel("'.$column->name.'"),"id"=>'."'".$column->name."'".']) ?>'."\n";
                                echo '   </div>'."\n";
                                echo '</div>'."\n";
                                echo ' <div class="clear"></div>'."\n";
                            }
                        }

                        ?>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?="<?php"?> echo Html::submitButton('保存', ['class' =>"btn btn-primary"]); ?>
                                <span>&nbsp;</span>
                                <?="<?php"?> echo Html::resetButton('重置', ['class' =>"btn btn-primary"]); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?="<?php "?> ActiveForm::end();?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<?="<?php "?> $this->beginBlock('footer');  ?>
<?="<?php "?> $this->endBlock(); ?>

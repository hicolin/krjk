<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminBankCardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-bank-card-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'beizhu') ?>

    <?= $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'interest') ?>

    <?php // echo $form->field($model, 'hk_way') ?>

    <?php // echo $form->field($model, 'range') ?>

    <?php // echo $form->field($model, 'time_limit') ?>

    <?php // echo $form->field($model, 'flow') ?>

    <?php // echo $form->field($model, 'condition') ?>

    <?php // echo $form->field($model, 'attention') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

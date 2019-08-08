<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminPay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-pay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pay_name')->textInput() ?>

    <?= $form->field($model, 'is_open')->textInput() ?>

    <?= $form->field($model, 'pay_site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

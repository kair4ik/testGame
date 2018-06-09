<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="row">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">

            <div class="site-about">
                <h2>Кабинет Админа</h2>
            </div>


<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'book_name') ?>
<?= $form->field($model, 'text')->textarea() ?>
<?= Html::submitButton('Генерировать задания', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>

        </div>
    </div>


<?php



?>
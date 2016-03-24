<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">Войти в аккаунт</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->label(false)->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

                <?= $form->field($model, 'password')->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?= Html::a('Войти через ' . Html::img(Yii::getAlias('@web/img/hh.ru-small.svg'), ['width' => 16]), ['/site/auth', 'authclient' => 'hh'], [
                        'class' => 'btn btn-primary',
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

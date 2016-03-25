<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('app', 'Sign in to your account') ?></div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username')->label(false)->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

                <?= $form->field($model, 'password')->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?= Html::a(Yii::t('app', 'Sign in with {source}', [
                        'source' => Html::img(Yii::getAlias('@web/img/hh.ru-small.svg'), ['width' => 16]),
                    ]), ['/site/auth', 'authclient' => 'hh'], [
                        'class' => 'btn btn-primary',
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

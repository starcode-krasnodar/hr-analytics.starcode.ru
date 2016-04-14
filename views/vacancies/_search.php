<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\VacanciesSearchForm */
/* @var $submitLabel string */
/* @var $resetUrl array */

?>

<?php $form = \yii\widgets\ActiveForm::begin(['method' => 'GET']) ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'queryName')->hint(false)->textInput([
                'autofocus' => true,
                'placeholder' => $model->getAttributeHint('queryName'),
                'data' => [
                    'toggle' => 'taggable',
                ]
            ]) ?>
            <?= $form->field($model, 'queryNameOperator')->label(false)->radioList($model->queryOperatorLabels()) ?>
            <?= $form->field($model, 'area')->hint(false)->widget(\app\widgets\AreaSelect2\Widget::className()) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'queryDescription')->hint(false)->textInput([
                'placeholder' => $model->getAttributeHint('queryDescription'),
                'data' => [
                    'toggle' => 'taggable',
                ]
            ]) ?>
            <?= $form->field($model, 'queryDescriptionOperator')->label(false)->radioList($model->queryOperatorLabels()) ?>
            <?= $form->field($model, 'industry')->hint(false)->widget(\app\widgets\IndustrySelect2\Widget::className()) ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= \yii\helpers\Html::submitInput($submitLabel, ['class' => 'btn btn-primary']) ?>
        <?= \yii\helpers\Html::a(Yii::t('app', 'Reset'), $resetUrl, ['class' => 'btn btn-danger']) ?>
    </div>
<?php $form->end() ?>

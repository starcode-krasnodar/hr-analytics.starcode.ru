<?php

/* @var $this \yii\web\View */
/* @var $searchModel \app\models\VacanciesSearchForm */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = Yii::t('app', 'Vacancies search');
?>

<div class="row">
    <div class="col-sm-12">
        <?php $form = \yii\widgets\ActiveForm::begin(['method' => 'get']) ?>
            <?= $form->field($searchModel, 'query')->label(false)->textInput(['autofocus' => true, 'placeholder' => $searchModel->getAttributeLabel('query')]) ?>
        <?php $form->end() ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => Yii::t('app', 'Vacancy Name'),
                    'attribute' => 'name',
                ],
                [
                    'label' => Yii::t('app', 'External URL'),
                    'attribute' => 'alternate_url',
                    'format' => 'url',
                ],
            ],
        ]) ?>
    </div>
</div>
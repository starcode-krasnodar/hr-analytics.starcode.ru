<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\VacanciesAnalyticsForm */
/* @var $totalCount int */
/* @var $salaryAverage int */

$this->title = Yii::t('app', 'Analytics');
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $form = \yii\widgets\ActiveForm::begin(['method' => 'GET']) ?>
    <?= $form->field($model, 'query')->label(false)->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('query')]) ?>
<?php $form->end() ?>

<ul class="list-group">
    <?php if (!empty($totalCount)): ?>
        <li class="list-group-item">
            <span class="badge"><?= $totalCount ?></span>
            Общее количество вакансий
        </li>
    <?php endif ?>
    <?php if (!empty($salaryAverage)): ?>
        <li class="list-group-item">
            <span class="badge"><?= Yii::$app->formatter->asCurrency($salaryAverage, 'RUB') ?></span>
            Средняя зарплата
        </li>
    <?php endif ?>
</ul>
<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\VacanciesAnalyticsForm */
/* @var $totalCount int */
/* @var $totalCountWithSalary int */
/* @var $totalCountWithSalaryPercent int */
/* @var $salaryAverage int */
/* @var $salaryMax int */
/* @var $salaryMin int */
/* @var $employmentCount array */

$this->title = Yii::t('app', 'Analytics');
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $form = \yii\widgets\ActiveForm::begin(['method' => 'GET']) ?>
    <?= $form->field($model, 'query')->label(false)->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('query')]) ?>
    <?= $form->field($model, 'area')->label(false)->widget(\app\widgets\AreaSelect2\Widget::className()) ?>
    <?= $form->field($model, 'industry')->label(false)->widget(\app\widgets\IndustrySelect2\Widget::className()) ?>

    <div class="form-group">
        <?= \yii\helpers\Html::submitInput(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        <?= \yii\helpers\Html::a(Yii::t('app', 'Reset'), ['/vacancies/analytics'], ['class' => 'btn btn-danger']) ?>
    </div>
<?php $form->end() ?>

<?php if ($totalCount !== null): ?>
    <ul class="list-group">
        <li class="list-group-item disabled"><?= Yii::t('app', 'Indicators') ?></li>
        <li class="list-group-item">
            <span class="badge"><?= $totalCount ?></span>
            <?= Yii::t('app', 'The total number of vacancies') ?>
            <?= \yii\helpers\Html::a(Yii::t('app', 'detail'), ['/vacancies/search', (new \app\models\VacanciesSearchForm())->formName() => ['query' => $model->query, 'area' => $model->area, 'industry' => $model->industry]]) ?>
        </li>
        <li class="list-group-item">
            <span class="badge"><?= $totalCountWithSalary ?></span>
            <?= Yii::t('app', 'The number of jobs with the specified salary') ?>
        </li>
        <li class="list-group-item">
            <span class="badge"><?= round($totalCountWithSalaryPercent) ?> %</span>
            <?= Yii::t('app', 'The number of jobs with the specified salary percentage') ?>
        </li>
        <?php if (!empty($salaryMax)): ?>
            <li class="list-group-item">
                <span class="badge"><?= Yii::$app->formatter->asCurrency($salaryMax, 'RUB') ?></span>
                <?= Yii::t('app', 'Max salary') ?>
            </li>
        <?php endif ?>
        <?php if (!empty($salaryMin)): ?>
            <li class="list-group-item">
                <span class="badge"><?= Yii::$app->formatter->asCurrency($salaryMin, 'RUB') ?></span>
                <?= Yii::t('app', 'Min salary') ?>
            </li>
        <?php endif ?>
        <?php if (!empty($salaryAverage)): ?>
            <li class="list-group-item">
                <span class="badge"><?= Yii::$app->formatter->asCurrency($salaryAverage, 'RUB') ?></span>
                <?= Yii::t('app', 'Average salary') ?>
            </li>
        <?php endif ?>
    </ul>
    <ul class="list-group">
        <li class="list-group-item disabled"><?= Yii::t('app', 'Employment') ?></li>
        <?php foreach ($employmentCount as $employment => $count): ?>
        <li class="list-group-item">
            <span class="badge"><?= $count ?></span>
            <?= $model->getEmploymentLabel($employment) ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif ?>
<?php

/* @var $this \yii\web\View */
use rmrevin\yii\fontawesome\FA;

/* @var $model \app\models\VacanciesAnalyticsForm */
/* @var $totalCount int */
/* @var $totalCountWithSalary int */
/* @var $totalCountWithSalaryPercent int */
/* @var $salaryAverage int */
/* @var $salaryMax int */
/* @var $salaryMin int */
/* @var $employmentCount array */
/* @var $employmentCountPercent array */
/* @var $scheduleCount array */
/* @var $scheduleCountPercent array */

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
        <li class="list-group-item disabled"><?= FA::icon(FA::_BAR_CHART_O, ['class' => 'fa-fw']) ?> <?= Yii::t('app', 'Indicators') ?></li>
        <li class="list-group-item">
            <span class="badge"><?= $totalCount ?></span>
            <?= Yii::t('app', 'The total number of vacancies') ?>
            <?= \yii\helpers\Html::a(Yii::t('app', 'detail'), [
                '/vacancies/search',
                (new \app\models\VacanciesSearchForm())->formName() => [
                    'query' => $model->query,
                    'area' => $model->area,
                    'industry' => $model->industry,
                ],
            ], ['target' => '_blank']) ?>
        </li>
        <li class="list-group-item">
            <span class="badge"><?= round($totalCountWithSalaryPercent) ?> %</span>
            <span class="badge"><?= $totalCountWithSalary ?></span>
            <?= Yii::t('app', 'The number of jobs with the specified salary') ?>
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
                <?= \yii\bootstrap\Button::widget([
                    'label' => FA::icon(FA::_QUESTION_CIRCLE)->__toString(),
                    'encodeLabel' => false,
                    'options' => [
                        'class' => 'btn btn-primary btn-xs',
                        'title' => Yii::t('app', 'How to calculate?'),
                        'data' => [
                            'toggle' => 'popover',
                            'trigger' => 'focus',
                            'html' => true,
//                            'content' => Yii::t('app', 'The calculation uses the sum of the salaries of all vacancies divided by the number of jobs with the specified salary.'),
                            'content' => '<p>Рассчитывается по формуле</p> 
                                <p><b>х = а/b</b>,</p> 
                                <p>где <b>а</b> - сумма зарплат по всем вакансиям*, <b>b</b> - количество вакансий с указанными зарплатами.</p> 
                                <hr />
                                <p>* Если в вакансии указан диапазон по зарплате, то зарплата по вакансии считается как среднее <b>(мин+макс)/2</b>.
Если в вакансии указан только нижняя граница зп, то она берется и  в качестве зп по вакансии.</p>',
                        ]
                    ],
                ]) ?>
            </li>
        <?php endif ?>
    </ul>
    <ul class="list-group">
        <li class="list-group-item disabled"><?= FA::icon(FA::_SUITCASE, ['class' => 'fa-fw']) ?> <?= Yii::t('app', 'Employment') ?></li>
        <?php foreach ($employmentCount as $employment => $count): ?>
        <li class="list-group-item">
            <span class="badge"><?= round($employmentCountPercent[$employment]) ?> %</span>
            <?= $model->getEmploymentLabel($employment) ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <ul class="list-group">
        <li class="list-group-item disabled"><?= FA::icon(FA::_CLOCK_O, ['class' => 'fa-fw']) ?> <?= Yii::t('app', 'Schedule') ?></li>
        <?php foreach ($scheduleCount as $schedule => $count): ?>
        <li class="list-group-item">
            <span class="badge"><?= round($scheduleCountPercent[$schedule]) ?> %</span>
            <?= $model->getScheduleLabel($schedule) ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif ?>
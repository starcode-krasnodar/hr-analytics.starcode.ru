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
$this->on(\yii\web\View::EVENT_BEGIN_PAGE, function() {
    \app\assets\vacancies\AnalyticsAsset::register($this);
});
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
            <span class="badge"><?= $totalCountWithSalary ?> (<?= round($totalCountWithSalaryPercent) ?> %)</span>
            <?= Yii::t('app', 'The number of jobs with the specified salary') ?>
        </li>
        <li class="list-group-item">
            <span class="badge">
                <?= Yii::$app->formatter->asCurrency($salaryMin, 'RUB') ?>
                -
                <?= Yii::$app->formatter->asCurrency($salaryMax, 'RUB') ?>
            </span>
            <?= Yii::t('app', 'Salaries range') ?>
        </li>
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
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?= FA::icon(FA::_SUITCASE, ['class' => 'fa-fw']) ?> <?= Yii::t('app', 'Employment') ?></div>
                <div class="panel-body">
                    <div id="hc-employment" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    <table id="hc-employment-datatable" class="hidden">
                        <thead>
                        <tr>
                            <th></th>
                            <th><?= Yii::t('app', 'Employment') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($employmentCountPercent as $employment => $percent): ?>
                            <tr>
                                <th><?= $model->getEmploymentLabel($employment) ?></th>
                                <td><?= round($percent) ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?= FA::icon(FA::_CLOCK_O, ['class' => 'fa-fw']) ?> <?= Yii::t('app', 'Schedule') ?></div>
                <div class="panel-body">
                    <div id="hc-schedule" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    <table id="hc-schedule-datatable" class="hidden">
                        <thead>
                        <tr>
                            <th></th>
                            <th><?= Yii::t('app', 'Schedule') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($scheduleCountPercent as $schedule => $percent): ?>
                            <tr>
                                <th><?= $model->getScheduleLabel($schedule) ?></th>
                                <td><?= round($percent) ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>
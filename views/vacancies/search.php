<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\VacanciesSearchForm */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = Yii::t('app', 'Vacancies search');
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->on(\yii\web\View::EVENT_BEGIN_PAGE, function() {
    \app\assets\controllers\VacanciesAsset::register($this);
});
?>

<div class="row">
    <div class="col-sm-12">
        <?= $this->render('_search', [
            'model' => $model,
            'submitLabel' => Yii::t('app', 'Search'),
            'resetUrl' => ['/vacancies/search'],
        ]) ?>
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
                    'label' => Yii::t('app', 'Employer'),
                    'attribute' => 'employer',
                    'content' => function($model) {
                        return !isset($model['employer']['alternate_url'])
                            ? $model['employer']['name']
                            : \yii\helpers\Html::a($model['employer']['name'], $model['employer']['alternate_url'], [
                                'target' => '_blank',
                            ]);
                    },
                ],
                [
                    'label' => Yii::t('app', 'Salary'),
                    'attribute' => 'salary',
                    'content' => function($model) {
                        if (!empty($model['salary'])) {
                            $to = isset($model['salary']['to']) ? $model['salary']['to'] : 0;
                            $from = isset($model['salary']['from']) ? $model['salary']['from'] : 0;
                            $currency = isset($model['salary']['currency']) ? $model['salary']['currency'] : '';

                            if (empty($to)) {
                                return Yii::t('app', 'From {from} {currency}', [
                                    'from' => '<b>' . $from . '</b>',
                                    'currency' => $currency,
                                ]);
                            } elseif (empty($from)) {
                                return Yii::t('app', 'To {to} {currency}', [
                                    'to' => '<b>' . $to . '</b>',
                                    'currency' => $currency,
                                ]);
                            } else {
                                return Yii::t('app', 'From {from} to {to} {currency}', [
                                    'from' => '<b>' . $from . '</b>',
                                    'to' => '<b>' . $to . '</b>',
                                    'currency' => $currency,
                                ]);
                            }
                        } else {
                            return Yii::t('app', 'Not specified');
                        }
                    },
                ],
                [
                    'label' => Yii::t('app', 'External URL'),
                    'attribute' => 'alternate_url',
                    'content' => function($model) {
                        return \yii\helpers\Html::a($model['alternate_url'], $model['alternate_url'], ['target' => '_blank']);
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
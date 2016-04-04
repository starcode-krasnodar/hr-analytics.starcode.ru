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
            <?= $form->field($searchModel, 'area')->label(false)->widget(\app\widgets\AreaSelect2\Widget::className()) ?>

            <div class="form-group">
                <?= \yii\helpers\Html::submitInput(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= \yii\helpers\Html::a(Yii::t('app', 'Reset'), ['/vacancies/search'], ['class' => 'btn btn-danger']) ?>
            </div>
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
                    'format' => 'url',
                ],
            ],
        ]) ?>
    </div>
</div>
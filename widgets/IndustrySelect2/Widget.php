<?php

namespace app\widgets\IndustrySelect2;

use app\components\auth\clients\Hh;
use app\models\VacanciesAnalyticsForm;
use app\widgets\IndustrySelect2\assets\WidgetAsset;
use yii\authclient\Collection;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;

class Widget extends InputWidget
{
    public $options = [
        'class' => ['form-control', 'js-industry-select2'],
    ];

    public $items = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var VacanciesAnalyticsForm $model */
        $model = $this->model;
        if ($model->industry && ($item = $this->fetchItems($model->industry))) {
            $this->items[] = $item;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $url = Url::to(['/suggests/industries']);
        WidgetAsset::register($this->view);
        return Html::activeDropDownList($this->model, $this->attribute, $this->items, ArrayHelper::merge($this->options, [
            'data' => [
                'language' => \Yii::$app->language,
                'placeholder' => $this->model->getAttributeLabel($this->attribute),
                'ajax--url' => $url,
                'ajax--data-type' => 'json',
                'ajax--delay' => 250,
                'ajax--cache' => true,
                'minimum-input-length' => 1,
            ],
        ]));
    }

    protected function fetchItems($id)
    {
        $hhClient = $this->getHhClient();
        $industries = $hhClient->api('industries', 'GET');
        $industries = array_filter($industries, function($industry) use ($id) {
            return $industry['id'] == $id;
        });
        $industries = array_values($industries);

        if (isset($industries[0])) {
            return [
                $industries[0]['id'] => $industries[0]['name'],
            ];
        } else {
            return null;
        }
    }

    /**
     * @return \yii\authclient\ClientInterface|Hh
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function getHhClient()
    {
        /** @var Collection $clientCollection */
        $clientCollection = \Yii::$app->get('authClientCollection');
        /** @var Hh $hhClient */
        if (!$clientCollection->hasClient('hh')) {
            throw new Exception('Not found hh client');
        }
        return $clientCollection->getClient('hh');
    }
}
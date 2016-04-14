<?php

namespace app\widgets\AreaSelect2;

use app\components\auth\clients\Hh;
use app\models\VacanciesSearchForm;
use app\widgets\AreaSelect2\assets\WidgetAsset;
use yii\authclient\Collection;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class Widget extends InputWidget
{
    public $options = [
        'class' => ['form-control', 'js-area-select2'],
    ];

    public $items = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var VacanciesSearchForm $model */
        $model = $this->model;
        if ($model->area && ($items = $this->fetchItems($model->area))) {
            $this->items = $items;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $url = $this->getHhClient()->apiBaseUrl . '/suggests/areas';
        WidgetAsset::register($this->view);
        return Html::activeDropDownList($this->model, $this->attribute, $this->items, ArrayHelper::merge($this->options, [
            'multiple' => true,
            'data' => [
                'multiple' => true,
                'language' => \Yii::$app->language,
                'placeholder' => $this->model->getAttributeHint($this->attribute),
                'ajax--url' => $url,
                'ajax--data-type' => 'json',
                'ajax--delay' => 250,
                'ajax--cache' => true,
                'minimum-input-length' => 1,
            ],
        ]));
    }

    protected function fetchItems($ids)
    {
        $items = [];
        $cache = \Yii::$app->cache;
        foreach ($ids as $id) {
            $key = 'area-select2-item-' . $id;
            if (!$cache->exists($key)) {
                $cache->set($key, $this->getHhClient()->api('areas/' . $id, 'GET'), 24 * 60 * 60);
            }
            $response = $cache->get($key);
            if (isset($response['name']) && isset($response['id'])) {
                $items[$response['id']] = $response['name'];
            }
        }
        return $items;
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
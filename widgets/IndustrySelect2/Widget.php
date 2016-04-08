<?php

namespace app\widgets\IndustrySelect2;

use app\components\auth\clients\Hh;
use app\widgets\IndustrySelect2\assets\WidgetAsset;
use yii\authclient\Collection;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class Widget extends InputWidget
{
    public $options = [
        'class' => ['form-control', 'js-industry-select2'],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        WidgetAsset::register($this->view);
        return Html::activeDropDownList($this->model, $this->attribute, $this->getItems(), ArrayHelper::merge($this->options, [
            'prompt' => '',
            'data' => [
                'language' => \Yii::$app->language,
                'placeholder' => $this->model->getAttributeLabel($this->attribute),
            ],
        ]));
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getItems()
    {
        $key = 'industry-select2-items';
        $cache = \Yii::$app->cache;
        if (!$cache->exists($key)) {
            $items = $this->getHhClient()->api('industries', 'GET');
            $items = ArrayHelper::map($items, 'id', 'name');
            $cache->set($key, $items, 7 * 24 * 60 * 60);
        }
        return $cache->get($key);
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
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
        $items = ArrayHelper::map($this->getHhClient()->api('industries', 'GET'), 'id', 'name');
        WidgetAsset::register($this->view);
        return Html::activeDropDownList($this->model, $this->attribute, $items, ArrayHelper::merge($this->options, [
            'prompt' => '',
            'data' => [
                'language' => \Yii::$app->language,
                'placeholder' => $this->model->getAttributeLabel($this->attribute),
            ],
        ]));
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
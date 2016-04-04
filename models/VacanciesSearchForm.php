<?php

namespace app\models;

use app\components\auth\clients\Hh;
use app\components\data\VacanciesDataProvider;
use Exception;
use Yii;
use yii\authclient\Collection;
use yii\base\Model;
use yii\data\DataProviderInterface;

class VacanciesSearchForm extends Model
{
    public $query;
    public $area;

    public function rules()
    {
        return [
            ['query', 'string'],
            ['area', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'query' => Yii::t('app', 'Enter your search phrase and press Enter'),
            'area' => \Yii::t('app', 'Select search area'),
        ];
    }

    /**
     * @return DataProviderInterface
     */
    public function search()
    {
        return new VacanciesDataProvider([
            'params' => [
                'text' => $this->query,
            ],
        ]);
    }


    /**
     * @return \yii\authclient\ClientInterface|Hh
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function getHhClient()
    {
        /** @var Collection $clientCollection */
        $clientCollection = Yii::$app->get('authClientCollection');
        /** @var HH $hhClient */
        if (!$clientCollection->hasClient('hh')) {
            throw new Exception('Not found hh client');
        }
        return $clientCollection->getClient('hh');
    }
}
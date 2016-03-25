<?php

namespace app\models;

use app\components\auth\clients\Hh;
use app\components\data\VacanciesDataProvider;
use Exception;
use Yii;
use yii\authclient\Collection;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\DataProviderInterface;

class VacanciesSearchForm extends Model
{
    public $query;

    public function rules()
    {
        return [
            ['query', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'query' => 'Введите поисковую фразу и нажмите Enter',
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
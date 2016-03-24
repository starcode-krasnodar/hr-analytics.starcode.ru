<?php

namespace app\models;

use app\components\auth\clients\Hh;
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
        $dataProvider = new ArrayDataProvider();
        $hhClient = $this->getHhClient();
        $result = $hhClient->api('vacancies', 'GET', [
            'text' => $this->query,
        ]);

        if (isset($result['items'])) {
            $dataProvider->setModels($result['items']);
        }

        return $dataProvider;
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
<?php

namespace app\components\data;

use app\components\auth\clients\Hh;
use Exception;
use Yii;
use yii\authclient\Collection;
use yii\data\BaseDataProvider;

class VacanciesDataProvider extends BaseDataProvider
{
    public $text;
    public $key;

    /**
     * Prepares the data models that will be made available in the current page.
     * @return array the available data models
     */
    protected function prepareModels()
    {
        $pagination = $this->getPagination();

        if ($pagination === false) {
            $response = $this->getHhClient()->api('vacancies', 'GET', [
                'text' => $this->text,
            ]);
            $models = $response['items'];
        } else {
            // in case there's pagination, read only a single page
            $pagination->totalCount = $this->getTotalCount();
            $response = $this->getHhClient()->api('vacancies', 'GET', [
                'text' => $this->text,
                'page' => $pagination->getPage(),
                'per_page' => $pagination->getPageSize(),
            ]);

            $models = $response['items'];
        }

        return $models;
    }

    /**
     * Prepares the keys associated with the currently available data models.
     * @param array $models the available data models
     * @return array the keys
     */
    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];

            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        } else {
            return array_keys($models);
        }
    }

    /**
     * Returns a value indicating the total number of data models in this data provider.
     * @return int total number of data models in this data provider.
     * @throws Exception
     * @throws \yii\base\Exception
     */
    protected function prepareTotalCount()
    {
        $response = $this->getHhClient()->api('vacancies', 'GET', [
            'text' => $this->text,
        ]);

        if (isset($response['found'])) {
            return $response['found'];
        } else {
            throw new \yii\base\Exception('Not found total page param');
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
        $clientCollection = Yii::$app->get('authClientCollection');
        /** @var HH $hhClient */
        if (!$clientCollection->hasClient('hh')) {
            throw new Exception('Not found hh client');
        }
        return $clientCollection->getClient('hh');
    }
}
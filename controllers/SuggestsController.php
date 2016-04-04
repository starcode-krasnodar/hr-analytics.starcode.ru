<?php

namespace app\controllers;

use app\components\auth\clients\Hh;
use yii\authclient\Collection;
use yii\base\Exception;
use yii\rest\Controller;

class SuggestsController extends Controller
{
    public function actionIndustries()
    {
        $hhClient = $this->getHhClient();
        $text = \Yii::$app->request->getQueryParam('text', '');
        $pattern = '/' . preg_quote($text) . '/ui';
        $industries = $hhClient->api('industries', 'GET');
        $industries = array_filter($industries, function($industry) use ($pattern) {
            return preg_match($pattern, $industry['name']);
        });

        return [
            'items' => array_map(function($item) {
                return [
                    'id' => $item['id'],
                    'text' => $item['name'],
                ];
            }, array_values($industries)),
        ];
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
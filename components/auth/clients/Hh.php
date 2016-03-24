<?php

namespace app\components\auth\clients;

use yii\authclient\OAuth2;

class Hh extends OAuth2
{
    public $authUrl = 'https://hh.ru/oauth/authorize';
    public $tokenUrl = 'https://hh.ru/oauth/token';
    public $apiBaseUrl = 'https://api.hh.ru';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'hh';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'HeadHunter';
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET');
    }

    /**
     * @inheritdoc
     *
     * For customize headers.
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $headers[] = 'Authorization: Bearer ' . $accessToken->getToken();

        $this->setCurlOptions([
            CURLOPT_USERAGENT => \Yii::$app->params['hh.userAgent'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        return $this->sendRequest($method, $url, $params, $headers);
    }
}
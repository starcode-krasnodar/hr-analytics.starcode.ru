<?php

namespace app\components\auth\clients;

use yii\authclient\OAuth2;

class HH extends OAuth2
{
    public $authUrl = 'https://hh.ru/oauth/authorize';
    public $tokenUrl = 'https://hh.ru/oauth/token';
    public $apiBaseUrl = 'https://api.hh.ru';

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        $headers[] = 'Authorization: Bearer ' . $accessToken->getToken();

        $this->setCurlOptions([
            CURLOPT_USERAGENT => 'anatoly.garkusha (anatoly.garkusha@starcode.ru)',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        return $this->sendRequest($method, $url, $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET');
    }

    protected function defaultName()
    {
        return 'hh';
    }

    protected function defaultTitle()
    {
        return 'HeadHunter';
    }
}
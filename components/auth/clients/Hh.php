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

    protected function composeUrl($url, array $params = [])
    {
        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }

        // not working with arrays
//        $url .= http_build_query($params, null, '&', PHP_QUERY_RFC3986);
        foreach ($params as $param => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    foreach ($value as $i => $v) {
                        $url .= '&' . $param . '=' . rawurlencode($v);
                    }
                } else {
                    $url .= '&' . $param . '=' . rawurlencode($value);
                }
            }
        }
        return $url;
    }
}
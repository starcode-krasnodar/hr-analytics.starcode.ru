<?php

namespace app\components;

use yii\authclient\ClientInterface;

class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        // handle here
    }
}
<?php

if (file_exists(__DIR__ . '/params-local.php')) {
    /** @noinspection PhpIncludeInspection */
    return require(__DIR__ . '/params-local.php');
} else {
    return [
        'adminEmail' => getenv('ADMIN_EMAIL'),

        'hh.clientId' => getenv('HH_CLIENT_ID'),
        'hh.clientSecret' => getenv('HH_CLIENT_SECRET'),
        'hh.returnUrl' => getenv('HH_RETURN_URL'),
        'hh.userAgent' => getenv('HH_USER_AGENT'),
    ];
}
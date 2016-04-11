<?php

if (file_exists(__DIR__ . '/db-local.php')) {
    /** @noinspection PhpIncludeInspection */
    return require(__DIR__ . '/db-local.php');
} else {
    $envDatabaseUrl = getenv('DATABASE_URL');
    $dbOpts = parse_url($envDatabaseUrl);
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:dbname=' . ltrim($dbOpts['path'],'/') . ';host=' . $dbOpts['host'] . ';port=' . $dbOpts['port'],
        'username' => $dbOpts['user'],
        'password' => $dbOpts['pass'],
        'charset' => 'utf8',
    ];
}

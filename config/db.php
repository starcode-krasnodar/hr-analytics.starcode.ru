<?php

$dbopts = parse_url(getenv('DATABASE_URL'));
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'psql:dbname=' . ltrim($dbopts['path'],'/') . ';host=' . $dbopts['host'] . ';port=' . $dbopts['port'],
    'username' => $dbopts['user'],
    'password' => $dbopts['pass'],
    'charset' => 'utf8',
];

<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=192.168.10.13;port=40003;dbname=config_system',
    'username' => 'root',
    'password' => 'leweisa',
    'charset' => 'utf8',
    'slaveConfig' => [
        'username' => 'root',
        'password' => 'leweisa',
        'attributes' => [
            PDO::ATTR_TIMEOUT => 10,
        ],
        'charset' => 'utf8',
    ],
    'slaves' => [
        ['dsn' => 'mysql:host=192.168.10.13;port=40003;dbname=config_system'],
    ],
];

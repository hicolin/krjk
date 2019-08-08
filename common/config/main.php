<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@runtime/cache2',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
           // 'dsn' => 'mysql:host=47.92.219.231;dbname=kaxsd_com',
            'dsn' => 'mysql:host=127.0.0.1;dbname=krjk_kaxsd_cn',
            'username' => 'krjk_kaxsd_cn',
            'password' => 'drCiCcZBBE5X7Ajc',
            'charset' => 'utf8',
            'tablePrefix' => 'admin_',
        ],

        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => '123@163.com',
                'password' => '授权码',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['123@163.com' => '名字']
            ],
        ],
    ],

];

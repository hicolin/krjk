<?php

// $con=mysqli_connect('211.149.233.13','daikfx','nPTFhTyEH36DsFAK','daikfx');
// if (!$con)
// {
//     die("连接错误: " . mysqli_connect_error());
// }else{

//     echo "11";exit;
// }
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@runtime/cache2',
        ],

//         'cache' => [
//             'class' => 'yii\caching\MemCache',
//             'servers' => [
//                 [
//                     'host' => '127.0.0.1',
//                     'port' => 11211,
//                     'weight' => 60,
//                 ],
//             ],
//         ],

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=xed_xinedai0308',
            'username' => 'xed_xinedai0308',
            'password' => 'sRyKpkTB4hQFmW3A',
            'charset' => 'utf8',
            'tablePrefix'=>'admin_',
        ],
        // 'db' => [
        //     'class' => 'yii\db\Connection',
        //     'dsn' => 'mysql:host=211.149.233.13;dbname=daikfx',
        //     'username' => 'daikfx',
        //     'password' => 'g7u6q8T2',
        //     'charset' => 'utf8',
        //     'tablePrefix'=>'admin_',
        // ],

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
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['123@163.com'=>'名字']
            ],
        ],

    ],
    
];
// return [
//     'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
//     'components' => [
//         'cache' => [
//             'class' => 'yii\caching\FileCache',
//             'cachePath' => '@runtime/cache2',
//         ],

// //         'cache' => [
// //             'class' => 'yii\caching\MemCache',
// //             'servers' => [
// //                 [
// //                     'host' => '127.0.0.1',
// //                     'port' => 11211,
// //                     'weight' => 60,
// //                 ],
// //             ],
// //         ],
//         'db' => [
//             'class' => 'yii\db\Connection',
//             'dsn' => 'mysql:host=rm-2zef5p346l59b43mx741.mysql.rds.aliyuncs.com;dbname=daikfx',
//             'username' => 'daikfx',
//             'password' => 'fs36fD056tTWMhm',
//             'charset' => 'utf8',
//             'tablePrefix'=>'admin_',
//         ],
//         'mailer' => [
//             'class' => 'yii\swiftmailer\Mailer',
//             'viewPath' => '@common/mail',
//             'useFileTransport' => false,
//             'transport' => [
//                 'class' => 'Swift_SmtpTransport',
//                 'host' => 'smtp.163.com',
//                 'username' => '123@163.com',
//                 'password' => '授权码',
//                 'port' => '25',
//                 'encryption' => 'tls',
//             ],
//             'messageConfig'=>[
//                 'charset'=>'UTF-8',
//                 'from'=>['123@163.com'=>'名字']
//             ],
//         ],
//         // 'urlManager' => [
//         //     //是否美化url
//         //     'enablePrettyUrl' => true,
//         //     //是否隐藏入口文件index.php
//         //     'showScriptName' => true,
//         //     //url后缀
//         //     //'suffix' => '.shtml',
//         //     'rules' => [
//         //     ],
//         // ],
//     ],

//     //     'aliases' => [
//     //     '@fankers' => '@vendor/oauth',
//     // ],
// ];
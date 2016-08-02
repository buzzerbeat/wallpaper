<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-wallpaper',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'wallpaper\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'album'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'image'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'category'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'photo'],
                'GET,HEAD images/<sid>' => 'image/view',
                'GET,HEAD albums/<sid>' => 'album/view',
                'GET,HEAD photos/<sid>' => 'photo/view',
                'GET,HEAD categories/<sid>' => 'category/view',
            ],
        ],
    ],
    'params' => $params,
];

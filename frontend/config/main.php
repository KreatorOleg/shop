<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'common\bootstrap\SetUp',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => $params['cookieValidationKey']
        ],
        //авторизация пользователя
        'user' => [
            'identityClass' => 'core\entities\User\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],

            ],
        ],
        //работа с сессией
        'session' => [
            'name' => '_session',
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
                'httpOnly' => true
            ]
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

        'frontendUrlManager' => require __DIR__ .'/UrlManager.php',
        'backendUrlManager' => require __DIR__ .'/../../backend/config/UrlManager.php',
        'urlManager' => function()
        {
            return Yii::$app->get('frontendUrlManager');
        },

    ],
    'params' => $params,
];

<?php
return [
    'class' => 'yii\web\UrlManager',//обязательно
    'hostInfo' => $params['frontendHostInfo'],
    //нужно прописать класс
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '/about' => 'site/about',
        '<_action:login|logout>' => '/auth/auth/<_action>',
        '<_action:signup>' => '/auth/signup/<_action>',
        '<_action:request>' => '/auth/reset/<_action>',
        '/contact' => '/contact/index',
        '<_controller:[\w\-]+>' => '<_controller>/index',
        '<_controller:[\w\-]+><_id:\d+>' => '<_controller>/view',
        '<_controller:[\w\-]+>/<_action:[\w\-]+>' => '<_controller>/<_action>',
        '<_controller:[\w\-]+><_id:\d+><_action:[\w\-]+>' => '<_controller>/<_action>',
    ],
];
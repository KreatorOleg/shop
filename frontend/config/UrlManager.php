<?php
return [
    'class' => 'yii\web\UrlManager',//обязательно
    'hostInfo' => $params['frontendHostInfo'],
    //нужно прописать класс
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        'contact' => 'site/contact',//не забыть изменить ссылку /contact
        '<_action:login|logout>' => 'site/<_action>',
        '<_controller:[\w\-]+>' => '<_controller>/index',
        '<_controller:[\w\-]+><_id:\d+>' => '<_controller>/view',
        '<_controller:[\w\-]+>/<_action:[\w\-]+>' => '<_controller>/<_action>',
        '<_controller:[\w\-]+><_id:\d+><_action:[\w\-]+>' => '<_controller>/<_action>',
    ],
];
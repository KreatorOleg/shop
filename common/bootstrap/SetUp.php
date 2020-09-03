<?php
namespace common\bootstrap;

use core\services\User\PasswordResetService;
use Yii;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;


        $container->setSingleton(MailerInterface::class,function() use ($app){
           return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class,[],[
           [$app->params['supportEmail'] => $app->name . ' robot']
        ]);


    }
}
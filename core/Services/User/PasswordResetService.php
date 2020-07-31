<?php
namespace core\Services\User;

use common\models\User;
use frontend\models\ResetPasswordForm;
use Yii;

use core\Repositories\User\UserRepository;
use frontend\models\PasswordResetRequestForm;
use yii\base\InvalidArgumentException;

class PasswordResetService
{

    private $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function request(PasswordResetRequestForm $form)
    {
        //поиск пользователя по email и по стату active
        $user = $this->repository->getByEmail($form->email);

        //проверяем токен
        $user->requestPasswordReset();

        //пробуем сохранить
        $this->repository->save($user);

        //отправляем письмо
        $result =  Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();


        if(!$result)
            throw new \RuntimeException('senf error');

        return $result;

    }

    public function validateToken($token): void
    {
        //если токен пустой или это не строка
        //бросаем исключение
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }

        //запрос на поиск пользователя, нам по сути нужно получить bool
        //нужно проверить что срок действия токена еще не истек
        //находим пользователя по данному токену
        if (!$this->repository->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(ResetPasswordForm $form, string $token)
    {
        //поиск пользователя по токену
        $user = $this->repository->getByToken($token);
        //устанавливаем новый пароль
        $user->setPassword($form->password);
        //сбрасываем старый
        $user->removePasswordResetToken();

        //сохраняем
        $this->repository->save($user);
    }
}
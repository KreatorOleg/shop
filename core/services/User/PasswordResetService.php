<?php
namespace core\services\User;

use Yii;

use core\forms\auth\ResetPasswordForm;
use core\Repositories\User\UserRepository;
use core\forms\auth\PasswordResetRequestForm;
use yii\mail\MailerInterface;


class PasswordResetService
{

    private $supportEmail;
    private $mailer;
    private $repository;

    public function __construct($supportEmail,MailerInterface $mailer,UserRepository $repository)
    {
        $this->supportEmail = $supportEmail;
        $this->mailer = $mailer;
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
        $result =  $this->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom($this->supportEmail)
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
        //сбрасываем токен
        $user->removePasswordResetToken();

        //сохраняем
        $this->repository->save($user);
    }
}
<?php
namespace core\Services\User;

use common\models\User;
use core\Repositories\User\UserRepository;
use frontend\models\SignupForm;


class SignupService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function signup(SignupForm $form) : User
    {
        //создаем нового пользователя
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );

        //пробуем создать новую запись
        $this->repository->save($user);

        //возвращаем пользователя
        return $user;
    }
}
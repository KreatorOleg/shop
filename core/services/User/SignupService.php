<?php
namespace core\services\User;

use core\entities\User\User;
use core\Repositories\User\UserRepository;
use core\forms\auth\SignupForm;


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
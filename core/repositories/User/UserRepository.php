<?php
namespace core\repositories\User;

use core\entities\User\User;

class UserRepository
{

    public function getId(int $userId) : User
    {
        return $this->getBy(['id' => $userId]);
    }

    public function getByEmail($email) : User
    {
        return $this->getBy(['and',['email' => $email],['status' => User::STATUS_ACTIVE]]);
    }

    public function getByToken(string $token) : User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }


    public function save(User $user) : void
    {
        if(!$user->save()){
            throw new \RuntimeException('save error');
        }
    }


    private function getBy($condition)
    {
        if(!$user = User::find()->where($condition)->one()){
            throw new \RuntimeException('User not found.');
        }

        return $user;
    }
}
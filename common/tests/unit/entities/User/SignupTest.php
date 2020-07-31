<?php
namespace common\tests\unit\entities\User;

use Codeception\Test\Unit;
use common\models\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        //создаем пользователя
        $user = User::signup(
            $username = 'username',
            $email = '$email@gmail.com',
            $password = 'password'
        );

        //проверяем совпадает ли то, что мы указали для username
        //и то что было присвоено пользователю
        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);

        //проверяем, что пароль не пустая строка
        $this->assertNotEmpty($user->password_hash);
        //проверяем, что они не совпадают
        //они не должны совпадать так как пароль мы захешировали
        $this->assertNotEquals($password , $user->password_hash);
        //проверяем, что дата была создана
        $this->assertNotEmpty($user->created_at);
        //проверяем что не пустое
        $this->assertNotEmpty($user->auth_key);
        //проверяем на совпадение status
        $this->assertEquals(User::STATUS_ACTIVE , $user->status);

    }
}
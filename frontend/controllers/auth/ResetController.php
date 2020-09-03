<?php
namespace frontend\controllers\auth;

use core\forms\auth\ResetPasswordForm;
use core\services\User\PasswordResetService;
use Yii;

use core\forms\auth\PasswordResetRequestForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class ResetController extends Controller
{
    private $service;

    public function __construct(
        $id,
        $module,
        PasswordResetService $service,
        $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->request($form);
                Yii::$app->session->setFlash('success','Проверьте почту и следуйте инструкциям');
                return $this->goHome();
            }catch (\RuntimeException $exception)
            {
                Yii::$app->session->setFlash('error',$exception->getMessage());
            }

        }

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */

    public function actionConfirm($token)
    {
        try {
            $this->service->validateToken($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->reset($form, $token);
                Yii::$app->session->setFlash('success', 'New password saved.');
            } catch (\RuntimeException $exception){
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }

            return $this->goHome();
        }

        return $this->render('reset', [
            'model' => $form,
        ]);
    }



}
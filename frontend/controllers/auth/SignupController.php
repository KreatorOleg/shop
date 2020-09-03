<?php
namespace frontend\controllers\auth;

use core\services\User\SignupService;
use Yii;

use core\forms\auth\SignupForm;
use yii\web\Controller;

class SignupController extends Controller
{
    private $service;

    public function __construct(
        $id,
        $module,
        SignupService $service,
        $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionSignup()
    {
        $form = new SignupForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);
            }catch (\RuntimeException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }
}

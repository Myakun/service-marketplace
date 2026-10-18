<?php

declare(strict_types=1);

namespace app\modules\partner\controllers;

use app\components\web\WebUser;
use app\models\Partner;
use app\modules\partner\models\user\Login;
use app\modules\partner\models\partner\Registration;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    public $layout = '@app/modules/admin/views/layouts/user';

    public function actionLogin(): Response
    {
        $model = new Login();

        $attributes = Yii::$app->getRequest()->post();
        if ($model->load($attributes) && $model->validate()) {
            $identity = Partner::findOne(['email' => $model->email]);
            WebUser::partner()->login($identity, 60 * 60 * 6);

            return $this->redirect(Url::to(['/partner']));
        }

        return new Response([
            'data' => $this->render('login', [
                'model' => $model,
            ]),
        ]);
    }

    public function actionLogout(): Response
    {
        WebUser::partner()->logout(false);

        return $this->redirect('/partner/user/login');
    }

    public function actionRegistration(): Response
    {
        $model = new Registration();

        $attributes = Yii::$app->getRequest()->post();
        if ($model->load($attributes) && $model->validate()) {
            $partner = Partner::register($model->contactPerson, $model->email, $model->name, $model->phone);

            WebUser::partner()->login($partner, 60 * 60 * 6);

            return $this->redirect(['/partner/user/registration-success']);
        }

        return new Response([
            'data' => $this->render('registration', [
                'model' => $model,
            ]),
        ]);
    }

    public function actionRegistrationSuccess(): Response
    {
        /**
         * @var Partner $partner
         */
        $partner = WebUser::partner()->getIdentity();
        if (Partner::STATUS_ACTIVE === $partner->status) {
            return $this->redirect(['/partner']);
        }

        return new Response([
            'data' => $this->render('registration-success', [
                'partner' => $partner,
            ]),
        ]);
    }

    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'user' => WebUser::PARTNER,
                'only' => ['login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'registration'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'registration-success'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
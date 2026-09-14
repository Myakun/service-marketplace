<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\models\user\AdminLogin;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    public $layout = '@app/modules/admin/views/layouts/user';

    public function actionLogin(): Response
    {
        $model = new AdminLogin();

        $attributes = Yii::$app->getRequest()->post();
        if ($model->load($attributes) && $model->validate()) {
            $identity = User::findOne(['email' => $model->email]);
            Yii::$app->getUser()->login($identity, 60 * 60 * 6);

            return $this->redirect(Yii::$app->getUser()->getReturnUrl());
        }

        return new Response([
            'data' => $this->render('login', [
                'model' => $model,
            ]),
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->getUser()->logout();

        return $this->redirect('/admin/user/login');
    }

    #[ArrayShape(['access' => "array"])]
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Customer;
use app\models\customer\Login;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class CustomerController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionLogin(): Response
    {
        $model = new Login();

        $attributes = Yii::$app->getRequest()->post();
        if ($model->load($attributes) && $model->validate()) {
            $identity = Customer::findOne(['email' => $model->email]);
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
        Yii::$app->getUser()->logout(false);

        return $this->redirect('/');
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
<?php

declare(strict_types=1);

namespace app\modules\admin\components\web;

use app\components\web\WebUser;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Base controller of the administration module: every action requires a logged-in administrator.
 */
class Controller extends \yii\web\Controller
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'user' => WebUser::ADMIN,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace app\modules\partner\components\web;

use app\components\web\WebUser;
use app\modules\partner\components\filters\StatusFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Base controller of the partner module: every action requires a logged-in, active partner.
 */
class Controller extends \yii\web\Controller
{
    #[\Override]
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'user' => WebUser::PARTNER,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'status' => [
                'class' => StatusFilter::class,
            ],
        ]);
    }
}

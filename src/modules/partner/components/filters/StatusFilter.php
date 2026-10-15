<?php

declare(strict_types=1);

namespace app\modules\partner\components\filters;

use app\components\web\WebUser;
use app\models\Partner;
use Yii;
use yii\base\ActionFilter;

class StatusFilter extends ActionFilter
{
    public function beforeAction($action): bool
    {
        /**
         * @var Partner $partner
         */
        $partner = WebUser::partner()->getIdentity();

        if (!$partner->isActive()) {
            Yii::$app->getResponse()->redirect(['/partner/user/registration-success']);
            Yii::$app->end();
        }

        return true;
    }
}
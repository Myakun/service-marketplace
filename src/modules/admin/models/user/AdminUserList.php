<?php

declare(strict_types=1);

namespace app\modules\admin\models\user;

use app\models\User;
use yii\base\Model;
use yii\db\ActiveQuery;

final class AdminUserList extends Model
{
    public function getQuery(): ActiveQuery
    {
        return User::find()
            ->with(['createdBy']);
    }
}

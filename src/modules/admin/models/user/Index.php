<?php

declare(strict_types=1);

namespace app\modules\admin\models\user;

use app\models\User;
use yii\base\Model;
use yii\db\ActiveQuery;

class Index extends Model
{
    public function getQuery(): ActiveQuery
    {
        $query = User::find()
            ->with(['createdBy']);

        return $query;
    }
}
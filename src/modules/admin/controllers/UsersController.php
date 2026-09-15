<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\User;
use app\modules\admin\models\user\AdminUserList;
use app\modules\admin\models\user\Save;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UsersController extends Controller
{
    use CRUDTrait;

    public function actionCreate(): Response
    {
        return $this->create(new Save(new User()), [
            'successMessage' => Yii::t('app', 'Administrator created successfully'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $user = User::findOne($id);
        if (null == $user) {
            throw new NotFoundHttpException();
        }

        return $this->delete($user, [
            'successMessage' => Yii::t('app', 'Administrator deleted successfully'),
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new AdminUserList();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $user = User::findOne($id);
        if (null == $user) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(User::findOne($id)), [
            'successMessage' => Yii::t('app', 'Administrator updated successfully'),
        ]);
    }
}
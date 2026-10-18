<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\components\web\crud\CRUDTrait;
use app\models\Order;
use app\modules\admin\components\web\Controller;
use app\modules\admin\models\order\Index;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrdersController extends Controller
{
    use CRUDTrait;

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'sort' => false,
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionSetRating(int $id, int $rating): Response
    {
        $order = Order::findOne($id);
        if (null == $order) {
            throw new NotFoundHttpException();
        }

        $order->done($rating);

        return new Response([
            'data' => $this->renderPartial('grid/status', [
                'order' => $order,
            ])
        ]);
    }
}
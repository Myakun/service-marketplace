<?php

declare(strict_types=1);

namespace app\modules\partner\controllers;

use app\components\web\crud\CRUDTrait;
use app\models\Order;
use app\modules\partner\components\web\Controller;
use app\modules\partner\models\order\Index;
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

    public function actionSetNextStatus(int $id): Response
    {
        $order = Order::findOne($id);
        if (null == $order) {
            throw new NotFoundHttpException();
        }

        if (Order::STATUS_CALL == $order->status) {
            $order->processing();
        } elseif (Order::STATUS_PROCESSING == $order->status) {
            $order->qualityCheck();
        }

        return new Response([
            'data' => $this->renderPartial('grid/status', [
                'order' => $order,
            ])
        ]);
    }

    public function actionSetStatusNew(int $id): Response
    {
        $order = Order::findOne($id);
        if (null == $order) {
            throw new NotFoundHttpException();
        }

        $order->partner_id = null;
        $order->status = Order::STATUS_NEW;
        $order->save();

        return new Response();
    }
}
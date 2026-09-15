<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Category;
use app\models\Offer;
use app\models\Order;
use app\models\Service;
use Yii;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrderController extends Controller
{
    public function actionSelectPartner(int $offerId, int $orderId): Response
    {
        $customer = Yii::$app->getUser()->getIdentity();
        if (null == $customer) {
            throw new ForbiddenHttpException();
        }

        $order = Order::findOne($orderId);
        if (null == $order) {
            throw new NotFoundHttpException();
        }
        if ($order->customer_id != $customer->id) {
            throw new ForbiddenHttpException();
        }

        $offer = Offer::findOne($offerId);
        if (null == $offer) {
            throw new NotFoundHttpException();
        }

        $order->status = Order::STATUS_CALL;
        $order->partner_id = $offer->partner_id;
        $order->save();

        Yii::$app->mailer
            ->compose('order/select-partner', [
                'order' => $order,
                'service' => $order->service,
            ])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setSubject(
                Yii::t('app', '{site} - new order for {service}', [
                    'site' => Yii::$app->params['siteName'],
                    'service' => $order->service->name,
                ])
            )
            ->setTo($order->partner->email)
            ->send();

        return $this->actionView($order->security_code);
    }

    public function actionView(string $securityCode): Response
    {
        $order = Order::find()
            ->andWhere(['security_code' => $securityCode])
            ->with([
                'offers' => function (ActiveQuery $query) {
                    $query->with(['partner']);
                }
            ])
            ->one();

        if (null == $order) {
            throw new NotFoundHttpException();
        }

        return new Response([
            'data' => $this->render('view', [
                'order' => $order,
            ]),
        ]);
    }
}


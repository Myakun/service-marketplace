<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Customer;
use app\models\Offer;
use app\models\Order;
use app\models\Price;
use app\models\Service;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ServiceController extends Controller
{
    public function actionSearchPartners(int $serviceId, int $price, ?string $email = null): Response
    {
        $service = $this->getServiceOr404($serviceId);

        $prices = Price::find()
            ->andWhere([
                'service_id' => $serviceId,
                'status' => Price::STATUS_ACTIVE
            ])
            ->with(['partner'])
            ->all();
        usort($prices, function (Price $a, Price $b) use($price) {
            $aAbs = abs($a->price - $price);
            $bAbs = abs($b->price - $price);

            return $aAbs <=> $bAbs;
        });

        $isNewUser = false;
        $customer = Yii::$app->getUser()->getIdentity();
        if (null == $customer) {
            $customer = Customer::findOne(['email' => $email]);
            if (null == $customer) {
                $customer = Customer::register($email);
                $isNewUser = true;
                Yii::$app->getUser()->login($customer, 60 * 60 * 6);
            }
        }

        $order = new Order();
        $order->customer_id = $customer->id;
        $order->price = $price;
        $order->service_id = $serviceId;
        $order->save();

        $length = min(count($prices), 3);
        foreach (array_slice($prices, 0, $length) as $price) {
            $offer = new Offer();
            $offer->order_id = $order->id;
            $offer->partner_id = $price->partner_id;
            $offer->price = $price->price;
            $offer->save();
        }

        $password = null;
        if ($isNewUser) {
            $password = Yii::$app->security->generateRandomString(Customer::PASSWORD_MIN_LENGTH);
            $customer->password = $password;
            $customer->save();
        }

        Yii::$app->mailer
            ->compose('service/search-partners', [
                'customer' => $customer,
                'order' => $order,
                'password' => $password,
                'service' => $service,
            ])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setSubject(
                Yii::t('app', '{site} - provider search for {service}', [
                    'site' => Yii::$app->params['siteName'],
                    'service' => $service->name,
                ])
            )
            ->setTo($customer->email)
            ->send();

        return new Response([
            'data' => $this->renderPartial('search-partners', [
                'order' => $order,
            ]),
        ]);
    }

    public function actionView(int $id): Response
    {
        $service = $this->getServiceOr404($id);

        return new Response([
            'data' => $this->render('view', [
                'service' => $service
            ]),
        ]);
    }

    private function getServiceOr404(int $id): Service
    {
        $service = Service::find()
            ->andWhere([
                'id' => $id,
                'status' => Service::STATUS_ACTIVE
            ])
            ->one();

        if (null == $service) {
            throw new NotFoundHttpException();
        }

        /**
         * @var Service $service
         */

        return $service;
    }
}
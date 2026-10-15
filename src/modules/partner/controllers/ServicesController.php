<?php

declare(strict_types=1);

namespace app\modules\partner\controllers;

use app\components\web\crud\CRUDTrait;
use app\components\web\WebUser;
use app\models\Price;
use app\models\User;
use app\modules\partner\components\web\Controller;
use app\modules\partner\models\Service;
use app\modules\partner\models\service\Index;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ServicesController extends Controller
{
    use CRUDTrait;

    public function actionDeletePrice(int $id): Response
    {
        $service = Service::findOne($id);
        if (null == $service) {
            throw new NotFoundHttpException();
        }

        $partnerPrice = $service->price;
        if (null == $partnerPrice) {
            throw new NotFoundHttpException();
        }

        $partnerPrice->delete();

        $service->refresh();

        return new Response([
            'data' => $this->renderPartial('grid/price', [
                'service' => $service
            ])
        ]);
    }

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

    public function actionSetPrice(int $id, int $price): Response
    {
        $service = Service::findOne($id);
        if (null == $service) {
            throw new NotFoundHttpException();
        }

        $partnerPrice = $service->price;
        if (null == $partnerPrice) {
            $partnerPrice = new Price();
            $partnerPrice->partner_id = WebUser::partner()->getId();
            $partnerPrice->service_id = $id;
        }

        $partnerPrice->price = $price;
        $partnerPrice->save();

        $service->refresh();

        return new Response([
            'data' => $this->renderPartial('grid/price', [
                'service' => $service
            ])
        ]);
    }
}
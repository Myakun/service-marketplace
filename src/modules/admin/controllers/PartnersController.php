<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Partner;
use app\modules\admin\models\partner\Index;
use app\modules\admin\models\partner\Save;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PartnersController extends Controller
{
    use CRUDTrait;


    public function actionActivate(int $id): string
    {
        $partner = Partner::findOne($id);
        if (null == $partner) {
            throw new NotFoundHttpException();
        }

        $partner->activate();

        return $this->renderPartial('grid/status', [
            'partner' => $partner,
        ]);
    }

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Partner::find()
            ->select(['address', 'id', 'name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['id'],
                'text' => $row['name'] . ' (' . $row['address'] . ')',
            ];
        }

        return $result;
    }

    public function actionDeactivate(int $id): string
    {
        $partner = Partner::findOne($id);
        if (null == $partner) {
            throw new NotFoundHttpException();
        }

        $partner->deactivate();

        return $this->renderPartial('grid/status', [
            'partner' => $partner,
        ]);
    }

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Partner()), [
            'successMessage' => Yii::t('app', 'Partner created successfully'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $partner = Partner::findOne($id);
        if (null == $partner) {
            throw new NotFoundHttpException();
        }

        if (!empty($partner->receipts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($partner, [
            'successMessage' => Yii::t('app', 'Partner deleted successfully'),
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
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
        $partner = Partner::findOne($id);
        if (null == $partner) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Partner::findOne($id)), [
            'successMessage' => Yii::t('app', 'Partner updated successfully'),
        ]);
    }
}
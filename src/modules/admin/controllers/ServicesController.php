<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\components\actions\Sorting;
use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Service;
use app\modules\admin\models\service\Index;
use app\modules\admin\models\service\Save;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ServicesController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Service::find()
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

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Service()), [
            'successMessage' => Yii::t('app', 'Service created successfully'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $service = Service::findOne($id);
        if (null == $service) {
            throw new NotFoundHttpException();
        }

        if (!empty($service->receipts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($service, [
            'successMessage' => Yii::t('app', 'Service deleted successfully'),
        ]);
    }

    public function actionIndex(): Response
    {
        $filterModel = new Index();
        $attributes = Yii::$app->getRequest()->get();
        $filterModel->load($attributes);

        return $this->index([
            'dataProvider' => [
                'pagination' => false,
                'sort' => false,
                'query' => $filterModel->getQuery(),
            ],
            'viewParams' => [
                'filterModel' => $filterModel,
            ],
        ]);
    }

    public function actionUpdate(int $id): Response
    {
        $service = Service::findOne($id);
        if (null == $service) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Service::findOne($id)), [
            'successMessage' => Yii::t('app', 'Service updated successfully'),
        ]);
    }

    #[ArrayShape(['sorting' => "array"])]
    public function actions(): array
    {
        return [
            'sorting' => [
                'class' => Sorting::class,
                'query' => Service::find(),
            ],
        ];
    }
}
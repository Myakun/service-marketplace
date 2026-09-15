<?php

declare(strict_types=1);

namespace app\modules\admin\controllers;

use app\components\actions\Sorting;
use app\components\web\Controller;
use app\components\web\crud\CRUDTrait;
use app\models\Category;
use app\modules\admin\models\category\Index;
use app\modules\admin\models\category\Save;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoriesController extends Controller
{
    use CRUDTrait;

    public function actionAutocomplete(string $query = ''): array
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if ('' == $query) {
            return $result;
        }

        $query = Category::find()
            ->select(['id', 'name'])
            ->andWhere(['like', 'lower(name)', mb_strtolower(trim($query))])
            ->andWhere(['status' => Category::STATUS_ACTIVE])
            ->limit(10)
            ->asArray();

        foreach ($query->all() as $row) {
            $result['results'][] = [
                'id' => $row['id'],
                'text' => $row['name']
            ];
        }

        return $result;
    }

    public function actionCreate(): Response
    {
        return $this->create(new Save(new Category()), [
            'successMessage' => Yii::t('app', 'Category created successfully'),
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $category = Category::findOne($id);
        if (null == $category) {
            throw new NotFoundHttpException();
        }

        if (!empty($category->receipts)) {
            throw new ForbiddenHttpException();
        }

        return $this->delete($category, [
            'successMessage' => Yii::t('app', 'Category deleted successfully'),
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
        $category = Category::findOne($id);
        if (null == $category) {
            throw new NotFoundHttpException();
        }

        return $this->update(new Save(Category::findOne($id)), [
            'successMessage' => Yii::t('app', 'Category updated successfully'),
        ]);
    }

    #[ArrayShape(['sorting' => "array"])]
    public function actions(): array
    {
        return [
            'sorting' => [
                'class' => Sorting::class,
                'query' => Category::find(),
            ],
        ];
    }
}
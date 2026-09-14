<?php

declare(strict_types=1);

namespace app\modules\admin\models\service;

use app\components\base\FilterModel;
use app\models\Category;
use app\models\Service;
use yii\db\ActiveQuery;

class Index extends FilterModel
{
    public ?string $categoryId = null;

    public ?string $name = null;

    public function attributeLabels(): array
    {
        $labels = (new Service())->attributeLabels();

        return [
            'categoryId' => $labels['category_id'],
            'name' => $labels['name'],
        ];
    }

    public function getCategoryIdDisplayName(): string
    {
        $category = Category::findOne($this->categoryId);

        return null == $category ? '' : $category->name;
    }

    public function getQuery(): ActiveQuery
    {
        $tableName = Service::tableName();

        $query = Service::find()
            ->with(['category', 'createdBy'])
            ->orderBy("{$tableName}.position ASC");

        if (null != $this->categoryId) {
            $this->enableFilter();
            $query->andWhere(['category_id' => $this->categoryId]);
        }

        if (null != $this->name) {
            $this->enableFilter();
            $query->andWhere(['like', 'name', $this->name]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['categoryId', 'safe'],

            ['name', 'safe'],
        ];
    }
}
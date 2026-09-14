<?php

declare(strict_types=1);

namespace app\modules\admin\models\service;

use app\models\Category;
use app\models\Service;
use app\components\web\crud\Model;
use yii\db\ActiveRecord;

class Save extends Model
{
    public ?string $categoryId = null;

    public ?string $description = null;

    public ?string $name = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        if ($entity->getIsNewRecord()) {
            return;
        }

        /**
         * @var Service $entity
         */

        $this->categoryId = (string) $entity->category_id;
        $this->description = $entity->description;
        $this->name = $entity->name;
    }


    public function attributeLabels(): array
    {
        $labels = (new Service())->attributeLabels();

        return [
            'categoryId' => $labels['category_id'],
            'description' => $labels['description'],
            'name' => $labels['name']
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'category_id' => $this->categoryId,
            'description' => $this->description,
            'name' => $this->name,
        ]);
    }

    public function getCategoryIdDisplayName(): string
    {
        $category = Category::findOne($this->categoryId);

        return null == $category ? '' : $category->name;
    }

    public function rules(): array
    {
        return [
            ['categoryId', 'required'],
            ['categoryId', 'integer'],
            ['categoryId', 'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id'
            ],

            ['description', 'string'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Service::NAME_MAX_LENGTH],
        ];
    }
}
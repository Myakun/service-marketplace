<?php

declare(strict_types=1);

namespace app\modules\admin\models\category;

use app\models\Category;
use app\components\web\crud\Model;
use yii\db\ActiveRecord;

class Save extends Model
{
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
         * @var Category $entity
         */
        
        $this->name = $entity->name;
    }

    public function attributeLabels(): array
    {
        $labels = (new Category())->attributeLabels();

        return [
            'name' => $labels['name']
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'name' => $this->name,
        ]);
    }

    public function rules(): array
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Category::NAME_MAX_LENGTH],
        ];
    }
}
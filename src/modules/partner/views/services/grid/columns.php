<?php

declare(strict_types=1);

use app\modules\partner\models\Service;

\app\modules\partner\assets\services\Grid::register($this);

return [
    'name' => [
        'attribute' => 'name',
        'contentOptions' => [
            'class' => 'name',
        ]
    ],
    'category_id' => [
        'attribute' => 'category_id',
        'value' => function(Service $service) {
            return $service->category->name;
        }
    ],
    'price' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Price'),
        'value' => function(Service $service) {
            return $this->render('grid/price', [
                'service' => $service
            ]);
        }
    ],
    /*'description' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Description'),
        'value' => function(Service $service) {
            return $this->render('grid/price', [
                'service' => $service
            ]);
        }
    ],*/
];
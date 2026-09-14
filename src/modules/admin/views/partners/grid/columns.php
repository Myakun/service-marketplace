<?php

declare(strict_types=1);

use app\models\Partner;
use app\components\widgets\grid\ActionColumn;

\app\modules\admin\assets\partners\Grid::register($this);

return [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(Partner $partner) {
            return $this->render('grid/id', [
                'partner' => $partner
            ]);
        }
    ],
    'name',
    'contact_person' => [
        'attribute' => 'contactPerson',
        'label' => Yii::t('app', 'Contact person'),
        'value' => function(Partner $partner) {
            return $partner->contact_person;
        }
    ],
    'phone',
    'email',
    'status' => [
        'format' => 'raw',
        'header' => Yii::t('app', 'Status'),
        'value' => function(Partner $partner) {
            return $this->render('grid/status', [
                'partner' => $partner
            ]);
        }
    ],
    [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}',
    ]
];
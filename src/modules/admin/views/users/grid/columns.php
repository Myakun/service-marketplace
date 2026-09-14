<?php

declare(strict_types=1);

use app\models\User;
use app\components\widgets\grid\ActionColumn;

$labels = new User()->attributeLabels();

return [
    'id' => [
        'format' => 'raw',
        'header' => '#',
        'value' => function(User $user) {
            return $this->render('grid/id', [
                'user' => $user
            ]);
        }
    ],
    'name',
    'email',
    [
        'class' => ActionColumn::class,
        'template' => '{update}',
    ]
];
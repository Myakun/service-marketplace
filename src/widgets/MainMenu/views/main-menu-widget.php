<?php

declare(strict_types=1);

use kartik\nav\NavX;
use yii\bootstrap5\NavBar;

/**
 * @var array $items
 */

NavBar::begin([
    'innerContainerOptions' => [
        'class' => 'container'
    ],
    'togglerContent' => Yii::t('app', 'Menu')
]);

echo NavX::widget([
    'activateParents' => true,
    'options' => ['class' => 'navbar-nav'],
    'encodeLabels' => false,
    'items' => $items
]);

NavBar::end();
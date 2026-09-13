<?php

declare(strict_types=1);

use yii\helpers\ArrayHelper;

$params = include __DIR__ . '/params.php';

return ArrayHelper::merge([
    'components' => [
        'urlManager' => [
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'hostInfo' => $params['app']['scriptUrl'],
            'scriptUrl' => $params['app']['scriptUrl'],
            'showScriptName' => false,
        ],
    ],
    'controllerNamespace' => 'app\commands',
], include(__DIR__ . '/main.php'));

<?php

declare(strict_types=1);

use app\components\web\WebUser;
use app\models\Customer;
use app\models\Partner;
use app\models\User;
use yii\helpers\ArrayHelper;

$params = include __DIR__ . '/params.php';

$config = ArrayHelper::merge(include __DIR__ . '/console.php', [
    'components' => [
        WebUser::ADMIN => [
            'class' => WebUser::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'identityClass' => User::class,
        ],
        'db' => [
            'dsn' => sprintf(
                'mysql:dbname=%s_test;host=%s;port=3306;',
                $params['MySQL']['database'],
                getenv('APP_MYSQL_TEST_HOST'),
            ),
            'enableSchemaCache' => false,
        ],
        'mailer' => [
            'fileTransportPath' => '@runtime/mail',
            'useFileTransport' => true,
        ],
        WebUser::PARTNER => [
            'class' => WebUser::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'identityClass' => Partner::class,
        ],
        WebUser::CUSTOMER => [
            'class' => WebUser::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'identityClass' => Customer::class,
        ],
    ],
    'id' => 'service-marketplace-test',
    'runtimePath' => dirname(__DIR__, 2) . '/var/runtime/test',
]);

$config['components']['log']['targets'] = [];

return $config;

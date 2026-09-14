<?php

declare(strict_types=1);

/**
 * Application config for the PHPUnit integration suite.
 */

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\web\User as WebUser;

$params = include __DIR__ . '/params.php';

$config = ArrayHelper::merge(include __DIR__ . '/console.php', [
    'components' => [
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
        // Lets behaviors resolve Yii::$app->user->id; tests attach an identity with setIdentity().
        'user' => [
            'class' => WebUser::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'identityClass' => User::class,
        ],
    ],
    'id' => 'service-marketplace-test',
    'runtimePath' => dirname(__DIR__, 2) . '/var/runtime/test',
]);

$config['components']['log']['targets'] = [];

return $config;

<?php

declare(strict_types=1);

use app\components\i18n\Formatter;
use yii\caching\FileCache;
use yii\helpers\ArrayHelper;

$params = include __DIR__ . '/params.php';

$config = [
    'aliases' => [
        '@bower' => dirname(__DIR__, 2) . '/vendor/bower-asset'
    ],
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(__DIR__, 2) . '/var/runtime',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'bootstrap' => ['log'],
    'components' => [
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
        ],
        'cache' => [
            'class' => FileCache::class
        ],
        'db' => [
            'charset' => 'utf8',
            'class' => yii\db\Connection::class,
            'tablePrefix' => '',
            'enableSchemaCache' => !YII_DEBUG,
        ],
        'errorHandler' => [
            'discardExistingOutput' => !YII_DEBUG
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
        'formatter' => [
            'class' => Formatter::class,
            'dateFormat' => 'php:d.m.Y',
            'datetimeFormat' => 'php:d.m.Y H:i',
            'timeFormat' => 'php:H:i',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    'class' => yii\log\FileTarget::class,
                    'enabled' => true,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'htmlLayout' => false,
            'useFileTransport' => APP_ENV === 'dev',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
            ],
        ],
    ],
    'id' => 'warehouse',
    'language' => 'en',
    'params' => $params['params'],
    'sourceLanguage' => 'en',
    'timeZone' => 'Europe/Moscow',
];

$config['components']['db'] = ArrayHelper::merge($config['components']['db'], [
    'dsn' => sprintf(
        'mysql:dbname=%s;host=%s;port=%d;',
        $params['MySQL']['database'],
        $params['MySQL']['host'],
        $params['MySQL']['port'],
    ),
    'password' => $params['MySQL']['password'],
    'username' =>  $params['MySQL']['username'],
]);

$config['components']['mailer']['transport'] = ArrayHelper::merge($config['components']['mailer']['transport'], [
    'encryption' => $params['mailer']['encryption'],
    'host' => $params['mailer']['host'],
    'password' => $params['mailer']['password'],
    'port' => $params['mailer']['port'],
    'username' => $params['mailer']['username'],
]);

return $config;
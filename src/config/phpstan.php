<?php

declare(strict_types=1);

/**
 * Application config for static analysis only.
 * Consumed by yii2-extensions/phpstan to resolve the types of Yii::$app components and params.
 */

use app\components\i18n\Formatter;
use app\components\web\WebUser;
use app\models\Customer;
use app\models\Partner;
use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\rbac\DbManager;
use yii\symfonymailer\Mailer;

return [
    'params' => [
        'bsVersion' => '5.x',
        'contacts' => [
            'email' => '',
            'phone' => '',
        ],
        'emailFrom' => '',
        'siteName' => '',
    ],
    'components' => [
        'authManager' => [
            'class' => DbManager::class,
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:',
        ],
        'formatter' => [
            'class' => Formatter::class,
        ],
        'mailer' => [
            'class' => Mailer::class,
        ],
        'reCaptcha' => [
            'class' => ReCaptchaConfig::class,
        ],
        'user' => [
            'class' => WebUser::class,
            'identityClass' => Customer::class,
        ],
        'adminUser' => [
            'class' => WebUser::class,
            'identityClass' => User::class,
        ],
        'partnerUser' => [
            'class' => WebUser::class,
            'identityClass' => Partner::class,
        ],
    ],
];

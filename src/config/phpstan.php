<?php

declare(strict_types=1);

/**
 * Application config for static analysis only.
 * Consumed by yii2-extensions/phpstan to resolve the types of Yii::$app components
 * and params.
 */

use app\components\i18n\Formatter;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\rbac\DbManager;
use yii\swiftmailer\Mailer;
use yii\web\IdentityInterface;

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
            'class' => yii\web\User::class,
            // The real app switches between User, Partner and Customer by URL section.
            'identityClass' => IdentityInterface::class,
        ],
    ],
];

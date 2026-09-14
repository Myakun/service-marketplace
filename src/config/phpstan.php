<?php

declare(strict_types=1);

/**
 * Application config for static analysis only.
 * Consumed by yii2-extensions/phpstan to resolve the types of Yii::$app components,
 * params, and model behaviors.
 */

use app\components\behaviors\BlameableBehavior as ComponentsBlameableBehavior;
use app\components\i18n\Formatter;
use app\models\Category;
use app\models\Customer;
use app\models\Order;
use app\models\Partner;
use app\models\Price;
use app\models\Service;
use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\caching\FileCache;
use yii\db\Connection;
use yii\rbac\DbManager;
use yii\swiftmailer\Mailer;
use yii\web\IdentityInterface;
use yii2tech\ar\position\PositionBehavior;

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
    'behaviors' => [
        Category::class => [BlameableBehavior::class, PositionBehavior::class, TimestampBehavior::class],
        Customer::class => [TimestampBehavior::class],
        Order::class => [TimestampBehavior::class],
        Partner::class => [TimestampBehavior::class],
        Price::class => [TimestampBehavior::class],
        Service::class => [BlameableBehavior::class, PositionBehavior::class, TimestampBehavior::class],
        User::class => [ComponentsBlameableBehavior::class, TimestampBehavior::class],
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

<?php

declare(strict_types=1);

use app\models\Customer;
use app\models\Partner;
use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\helpers\ArrayHelper;

define('IS_ADMIN', str_contains($_SERVER['REQUEST_URI'], '/admin'));
define('IS_PARTNER', str_contains($_SERVER['REQUEST_URI'], '/partner'));

$params = include __DIR__ . '/params.php';

$config = [
    'components' => [
        'assetManager' => [
            'basePath' => APP_ENV == 'dev' ? '@runtime/assets' : '@webroot/assets',
            'linkAssets' => APP_ENV == 'dev',
        ],
        'reCaptcha' => [
            'class' => ReCaptchaConfig::class,
            'secretV3' => $params['reCaptcha']['secretKey'],
            'siteKeyV3' => $params['reCaptcha']['siteKey'],
        ],
        'request' => [
            'baseUrl' => '',
            'cookieValidationKey' => $params['app']['cookieValidationKey'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'category/<id:\d+>' => 'category/view',
                'order/select-partner' => 'order/select-partner',
                'order/<securityCode>' => 'order/view',
                'service/<id:\d+>' => 'service/view',
            ],
        ],
        'user' => IS_ADMIN ? [
            'enableAutoLogin' => true,
            'idParam' => '__id-admin',
            'identityClass' => User::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-admin',
            ],
            'loginUrl' => ['/admin/user/login'],
        ] : (
        IS_PARTNER ? [
            'enableAutoLogin' => true,
            'idParam' => '__id-partner',
            'identityClass' => Partner::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-partner',
            ],
            'loginUrl' => ['/partner/user/login'],
        ] : [
            'enableAutoLogin' => true,
            'idParam' => '__id-customer',
            'identityClass' => Customer::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-customer',
            ],
            'loginUrl' => ['/customer/login'],
        ]
        ),
        'view' => [
            'class' => \app\components\web\View::class
        ]
    ],
    'defaultRoute' => 'default/index',
    'modules' => [
        'admin' => [
            'class' => \app\modules\admin\Module::class,
            'layout' => 'main'
        ],
        'gridview' => [
            'class' => kartik\grid\Module::class
        ],
        'partner' => [
            'class' => \app\modules\partner\Module::class,
            'layout' => 'main'
        ],
    ],
    'params' => [
        'bsVersion' => '5.x'
    ],
];

if (APP_ENV === 'dev') {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];
}

return ArrayHelper::merge($config, include(__DIR__ . '/main.php'));
<?php

declare(strict_types=1);

use app\components\web\WebUser;
use app\models\Customer;
use app\models\Partner;
use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaConfig;
use yii\helpers\ArrayHelper;

$params = include __DIR__ . '/params.php';

$config = [
    'components' => [
        WebUser::ADMIN => [
            'class' => WebUser::class,
            'enableAutoLogin' => true,
            'idParam' => '__id-admin',
            'identityClass' => User::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-admin',
            ],
            'loginUrl' => ['/admin/user/login'],
            'returnUrlParam' => '__returnUrl-admin',
        ],
        'assetManager' => [
            'basePath' => APP_ENV == 'dev' ? '@runtime/assets' : '@webroot/assets',
            'linkAssets' => APP_ENV == 'dev',
        ],
        WebUser::PARTNER => [
            'class' => WebUser::class,
            'enableAutoLogin' => true,
            'idParam' => '__id-partner',
            'identityClass' => Partner::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-partner',
            ],
            'loginUrl' => ['/partner/user/login'],
            'returnUrlParam' => '__returnUrl-partner',
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
        WebUser::CUSTOMER => [
            'class' => WebUser::class,
            'enableAutoLogin' => true,
            'idParam' => '__id-customer',
            'identityClass' => Customer::class,
            'identityCookie' => [
                'httpOnly' => true,
                'name' => '_identity-customer',
            ],
            'loginUrl' => ['/customer/login'],
            'returnUrlParam' => '__returnUrl-customer',
        ],
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

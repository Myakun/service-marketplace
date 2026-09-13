<?php

return [
    'app' => [
        'cookieValidationKey' => '${APP_COOKIE_VALIDATION_KEY}',
        'scriptUrl' => '${APP_SCRIPT_URL}',
    ],
    'mailer' => [
        'encryption' => '${APP_MAILER_ENCRYPTION}',
        'host' => '${APP_MAILER_HOST}',
        'port' => (int)'${APP_MAILER_PORT}',
        'username' => '${APP_MAILER_USER}',
        'password' => '${APP_MAILER_PASS}',
    ],
    'MySQL' => [
        'database' => '${APP_MYSQL_DB}',
        'host' => '${APP_MYSQL_HOST}',
        'username' => '${APP_MYSQL_USER}',
        'password' => '${APP_MYSQL_PASS}',
        'port' => (int)'${APP_MYSQL_PORT}',
    ],
    'MySQL1' => [
        'database' => 'u1712448_default',
        'host' => '31.31.196.138',
        'username' => 'u1712448_default',
        'password' => 'wxmmIil7VOr4P9H3',
        'port' => (int)'3306',
    ],
    'params' => [
        'contacts' => [
            'email' => '${APP_CONTACTS_EMAIL}',
            'phone' => '${APP_CONTACTS_PHONE}',
        ],
        'emailFrom' => '${APP_MAILER_USER}',
        'siteName' => 'Service Marketplace',
    ],
    'reCaptcha' => [
        'siteKey' => '${APP_RECAPTCHA_SITE_KEY}',
        'secretKey' => '${APP_RECAPTCHA_SECRET_KEY}',
    ],
];
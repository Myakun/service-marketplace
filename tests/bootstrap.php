<?php

declare(strict_types=1);

use app\tests\support\SilentMigrateController;
use yii\console\Application;

defined('APP_ENV') || define('APP_ENV', 'test');

require dirname(__DIR__) . '/src/components/bootstrap.php';

$app = new Application(require dirname(__DIR__) . '/src/config/test.php');

$exitCode = new SilentMigrateController('migrate', $app, ['interactive' => false])->runAction('up');

if ($exitCode !== 0) {
    throw new RuntimeException(sprintf('Migrations failed on the test database (exit code %d).', $exitCode));
}

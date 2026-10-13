<?php

declare(strict_types=1);

use app\tests\utils\MigrateController;
use yii\console\Application;

defined('APP_ENV') || define('APP_ENV', 'test');

require dirname(__DIR__) . '/src/components/bootstrap.php';

try {
    $app = new Application(require dirname(__DIR__) . '/src/config/test.php');
} catch (\yii\base\InvalidConfigException $e) {
    echo $e->getMessage();
    exit;
}

// Refuse to touch any database that is not a dedicated test database.
$database = $app->db->createCommand('SELECT DATABASE()')->queryScalar();
if (!is_string($database) || !str_ends_with($database, '_test')) {
    throw new RuntimeException(sprintf('Tests must run against a "_test" database, got "%s".', $database));
}

$exitCode = new MigrateController('migrate', $app, ['interactive' => false])->runAction('up');
if ($exitCode !== 0) {
    throw new RuntimeException(sprintf('Migrations failed on the test database (exit code %d).', $exitCode));
}

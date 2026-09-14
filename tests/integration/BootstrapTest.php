<?php

declare(strict_types=1);

namespace app\tests\integration;

use app\models\User;
use PHPUnit\Framework\TestCase;
use Yii;

/**
 * Guards the test environment itself: the right database, a migrated schema, and no real mail.
 */
final class BootstrapTest extends TestCase
{
    public function testAppRunsAgainstTheTestDatabase(): void
    {
        $database = Yii::$app->db->createCommand('SELECT DATABASE()')->queryScalar();

        self::assertIsString($database);
        self::assertStringEndsWith('_test', $database);
    }

    public function testSchemaIsFullyMigrated(): void
    {
        $migrationFiles = glob(Yii::getAlias('@app/migrations/m*.php')) ?: [];
        $applied = (int) Yii::$app->db->createCommand('SELECT COUNT(*) FROM migration')->queryScalar();

        // The migration table holds one base row in addition to the applied migrations.
        self::assertSame(count($migrationFiles) + 1, $applied);
        self::assertInstanceOf(User::class, User::findOne(1), 'The users migration seeds the admin');
    }

    public function testMailNeverLeavesTheBox(): void
    {
        self::assertTrue(Yii::$app->mailer->useFileTransport);
    }
}

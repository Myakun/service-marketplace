<?php

declare(strict_types=1);

namespace app\tests\integration\support;

use app\models\User;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class DatabaseTestCaseTest extends DatabaseTestCase
{
    public function testDatabaseStartsWithOnlyTheSeededAdmin(): void
    {
        self::assertSame(1, (int) User::find()->count());
        self::assertSame('admin@example.com', $this->admin()->email);
    }

    public function testWritesAreVisibleWithinTheTestAndRolledBackAfterIt(): void
    {
        Factory::user();
        Factory::user();

        // If rollback ever stopped working, the first test would see three users when run after this one.
        self::assertSame(3, (int) User::find()->count());
    }

    public function testLoginAsProvidesTheIdentityBehaviorsRead(): void
    {
        $author = Factory::user();
        $this->loginAs($author);

        $category = Factory::category();

        self::assertSame($author->id, $category->created_by);
        self::assertSame($author->id, $category->updated_by);
    }
}

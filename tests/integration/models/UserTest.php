<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\User;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;
use Yii;

final class UserTest extends DatabaseTestCase
{
    public function testPasswordIsHashedOnInsert(): void
    {
        $user = Factory::user(['password' => 'plain-text']);

        self::assertNotSame('plain-text', $user->password);
        self::assertTrue(Yii::$app->security->validatePassword('plain-text', $user->password));
    }

    public function testPasswordIsRehashedOnlyWhenChanged(): void
    {
        $user = Factory::user(['password' => 'first']);
        $hash = $user->password;

        $user->name = 'Renamed';
        self::assertTrue($user->save());
        self::assertSame($hash, $user->password, 'An unrelated update keeps the hash');

        $user->password = 'second';
        self::assertTrue($user->save());
        self::assertTrue(Yii::$app->security->validatePassword('second', $user->password));
    }

    public function testAuditColumnsAreFilledFromTheCurrentUser(): void
    {
        $this->loginAs($this->admin());

        $user = Factory::user();

        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $user->created_at);
        self::assertSame($user->created_at, $user->updated_at);
        self::assertSame(1, $user->created_by);
        self::assertTrue($user->hasCreator());
        self::assertSame('admin@example.com', $user->createdBy->email);
    }

    public function testSeededAdminHasNoCreator(): void
    {
        self::assertFalse($this->admin()->hasCreator());
    }

    public function testEmailMustBeUnique(): void
    {
        Factory::user(['email' => 'taken@example.com']);

        $duplicate = new User(['email' => 'taken@example.com', 'name' => 'Dup', 'password' => 'x']);

        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('email', $duplicate->getErrors());
    }
}

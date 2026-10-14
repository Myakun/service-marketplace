<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\components\behaviors\BlameableBehavior;
use app\components\behaviors\TimestampBehavior;
use app\models\User;
use app\tests\utils\IntegrationTestCase;
use app\tests\utils\models\ModelTestFactory;
use app\tests\utils\models\UserTestFactory;
use Yii;

final class UserTest extends IntegrationTestCase
{
    public function testCreatedByReturnsAuthor(): void
    {
        $author = UserTestFactory::create();
        $this->loginAs($author);

        $user = UserTestFactory::create();

        self::assertSame($author->id, $user->createdBy?->id);
    }

    public function testEmailIsRequired(): void
    {
        $user = UserTestFactory::prepare(['email' => null]);

        self::assertFalse($user->validate(['email']));
    }

    public function testEmailLengthIsLimited(): void
    {
        $user = UserTestFactory::prepare([
            'email' => str_repeat('a', User::EMAIL_MAX_LENGTH - strlen('@example.com') + 1) . '@example.com',
        ]);

        self::assertFalse($user->validate(['email']));
    }

    public function testEmailMustBeUnique(): void
    {
        $existing = UserTestFactory::create();
        $user = UserTestFactory::prepare(['email' => $existing->email]);

        self::assertFalse($user->validate(['email']));
    }

    public function testEmailMustBeValid(): void
    {
        $user = UserTestFactory::prepare(['email' => 'not-an-email']);

        self::assertFalse($user->validate(['email']));
    }

    public function testModelHasExpectedBehaviors(): void
    {
        $actual = array_map(fn ($b) => $b['class'], new User()->behaviors());

        self::assertArraysHaveEqualValuesIgnoringOrder([BlameableBehavior::class, TimestampBehavior::class], $actual);
    }

    public function testNameIsRequired(): void
    {
        $user = UserTestFactory::prepare(['name' => null]);

        self::assertFalse($user->validate(['name']));
    }

    public function testNameLengthIsLimited(): void
    {
        $user = UserTestFactory::prepare(['name' => str_repeat('a', User::NAME_MAX_LENGTH + 1)]);

        self::assertFalse($user->validate(['name']));
    }

    public function testPasswordCannotBeEmptiedOnUpdate(): void
    {
        $user = UserTestFactory::create();
        $user->password = '';

        self::assertFalse($user->validate(['password']));
    }

    public function testPasswordIsHashedOnInsert(): void
    {
        $user = UserTestFactory::create();

        self::assertTrue(
            Yii::$app->security->validatePassword(ModelTestFactory::RAW_PASSWORD, $user->password)
        );
    }

    public function testPasswordIsKeptOnUnrelatedUpdate(): void
    {
        $user = UserTestFactory::create();
        $hash = $user->password;
        $user->name = 'Renamed';
        $user->save();

        self::assertSame($hash, $user->password);
    }


    public function testPasswordIsRehashedWhenChanged(): void
    {
        $user = UserTestFactory::create();

        $user->password = 'changed1234';
        $user->save();

        self::assertTrue(Yii::$app->security->validatePassword('changed1234', $user->password));
    }

    public function testPasswordIsRequiredOnInsert(): void
    {
        $user = UserTestFactory::prepare(['password' => null]);

        self::assertFalse($user->validate(['password']));
    }
}

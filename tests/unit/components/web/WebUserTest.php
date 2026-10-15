<?php

declare(strict_types=1);

namespace app\tests\unit\components\web;

use app\components\web\WebUser;
use app\tests\utils\StubIdentity;
use PHPUnit\Framework\TestCase;

final class WebUserTest extends TestCase
{
    protected function tearDown(): void
    {
        WebUser::admin()->setIdentity(null);
        WebUser::customer()->setIdentity(null);
        WebUser::partner()->setIdentity(null);

        parent::tearDown();
    }

    public function testLoginIsScopedToOneComponent(): void
    {
        WebUser::admin()->setIdentity(new StubIdentity(1));

        self::assertFalse(WebUser::admin()->getIsGuest());
        self::assertTrue(WebUser::customer()->getIsGuest());
        self::assertTrue(WebUser::partner()->getIsGuest());
    }
}

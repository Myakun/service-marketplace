<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Customer;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class CustomerTest extends DatabaseTestCase
{
    public function testRegisterCreatesActiveCustomerWithHashedPassword(): void
    {
        $customer = Customer::register('new@example.com');

        self::assertFalse($customer->getIsNewRecord());
        self::assertSame(Customer::STATUS_ACTIVE, $customer->status);
        self::assertStringStartsWith('$2y$', $customer->password, 'Stored as a bcrypt hash, never in plain text');
        self::assertFalse($customer->isProfileFilled());
    }

    public function testProfileIsFilledOnceNameAndPhoneAreSet(): void
    {
        $customer = Customer::register('new@example.com');

        $customer->name = 'Jane';
        $customer->phone = '9000000001';
        self::assertTrue($customer->save());

        self::assertTrue($customer->isProfileFilled());
    }

    public function testPhoneMustHaveExactlyTenDigitsAndBeUnique(): void
    {
        Factory::customer(['phone' => '9000000001']);

        $short = new Customer(['email' => 'a@example.com', 'password' => 'x', 'phone' => '900000001']);
        $duplicate = new Customer(['email' => 'b@example.com', 'password' => 'x', 'phone' => '9000000001']);

        self::assertFalse($short->validate());
        self::assertArrayHasKey('phone', $short->getErrors());
        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('phone', $duplicate->getErrors());
    }

    public function testEmailMustBeUnique(): void
    {
        Factory::customer(['email' => 'taken@example.com']);

        $duplicate = new Customer(['email' => 'taken@example.com', 'password' => 'x']);

        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('email', $duplicate->getErrors());
    }
}

<?php

declare(strict_types=1);

namespace app\tests\integration\support;

use app\models\Category;
use app\models\Customer;
use app\models\Order;
use app\models\Partner;
use app\models\Price;
use app\models\Service;
use app\models\User;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class FactoryTest extends DatabaseTestCase
{
    public function testBuildsEveryModelWithDefaults(): void
    {
        self::assertInstanceOf(User::class, Factory::user());
        self::assertInstanceOf(Customer::class, Factory::customer());
        self::assertInstanceOf(Partner::class, Factory::partner());
        self::assertInstanceOf(Category::class, Factory::category());
        self::assertInstanceOf(Service::class, Factory::service());
        self::assertInstanceOf(Price::class, Factory::price());
        self::assertInstanceOf(Order::class, Factory::order());
    }

    public function testOverridesWinOverDefaults(): void
    {
        $partner = Factory::partner(['name' => 'Acme', 'status' => Partner::STATUS_INACTIVE]);

        self::assertSame('Acme', $partner->name);
        self::assertSame(Partner::STATUS_INACTIVE, $partner->status);
        self::assertNotSame('secret', $partner->password, 'Passwords are hashed by the model');
    }

    public function testRelatedModelsAreCreatedOnDemandOrReused(): void
    {
        $category = Factory::category();
        $service = Factory::service(['category_id' => $category->id]);
        $order = Factory::order(['service_id' => $service->id]);

        self::assertSame($category->id, $service->category_id);
        self::assertSame($service->id, $order->service_id);
        self::assertSame(1, (int) Category::find()->count(), 'No extra category was created');
    }

    public function testGeneratedValuesAreUnique(): void
    {
        $first = Factory::customer();
        $second = Factory::customer();

        self::assertNotSame($first->email, $second->email);
        self::assertNotSame($first->phone, $second->phone);
    }
}

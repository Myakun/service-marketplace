<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Service;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class ServiceTest extends DatabaseTestCase
{
    public function testPriceAggregatesOverPartnerPrices(): void
    {
        $service = Factory::service();
        foreach ([300, 100, 200] as $price) {
            Factory::price(['service_id' => $service->id, 'price' => $price]);
        }

        self::assertSame(200, $service->getAveragePrice());
        self::assertSame(300, $service->getMaxPrice());
        self::assertSame(100, $service->getMinPrice());
    }

    public function testMinimumPriceMayBeZero(): void
    {
        $service = Factory::service();
        Factory::price(['service_id' => $service->id, 'price' => 0]);
        Factory::price(['service_id' => $service->id, 'price' => 100]);

        self::assertSame(0, $service->getMinPrice());
    }

    public function testAggregatesAreZeroWithoutPrices(): void
    {
        $service = Factory::service();

        self::assertSame(0, $service->getAveragePrice());
        self::assertSame(0, $service->getMaxPrice());
        self::assertSame(0, $service->getMinPrice());
    }

    public function testNameIsUniquePerCategoryOnly(): void
    {
        $category = Factory::category();
        Factory::service(['category_id' => $category->id, 'name' => 'Window cleaning']);

        $sameCategory = new Service(['category_id' => $category->id, 'name' => 'Window cleaning']);
        $otherCategory = new Service(['category_id' => Factory::category()->id, 'name' => 'Window cleaning']);

        self::assertFalse($sameCategory->validate());
        self::assertTrue($otherCategory->validate());
    }

    public function testPositionsAreNumberedPerCategory(): void
    {
        $category = Factory::category();
        $first = Factory::service(['category_id' => $category->id]);
        $second = Factory::service(['category_id' => $category->id]);
        $elsewhere = Factory::service();

        self::assertSame([1, 2, 1], [$first->position, $second->position, $elsewhere->position]);
    }
}

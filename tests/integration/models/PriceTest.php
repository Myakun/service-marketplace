<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Price;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class PriceTest extends DatabaseTestCase
{
    public function testOnePricePerPartnerAndService(): void
    {
        $existing = Factory::price();

        $duplicate = new Price(['partner_id' => $existing->partner_id, 'service_id' => $existing->service_id, 'price' => 1]);
        $otherPartner = new Price(['partner_id' => Factory::partner()->id, 'service_id' => $existing->service_id, 'price' => 1]);

        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('service_id', $duplicate->getErrors());
        self::assertTrue($otherPartner->validate());
    }

    public function testPriceMustNotBeNegative(): void
    {
        $price = new Price(['partner_id' => Factory::partner()->id, 'service_id' => Factory::service()->id, 'price' => -1]);

        self::assertFalse($price->validate());
        self::assertArrayHasKey('price', $price->getErrors());
    }

    public function testPartnerAndServiceMustExist(): void
    {
        $price = new Price(['partner_id' => 999999, 'service_id' => 999999, 'price' => 1]);

        self::assertFalse($price->validate());
        self::assertSame(['partner_id', 'service_id'], array_keys($price->getErrors()));
    }
}

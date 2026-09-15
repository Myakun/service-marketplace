<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Offer;
use app\models\Order;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;
use Yii;
use yii\validators\ExistValidator;

final class OfferTest extends DatabaseTestCase
{
    public function testOrderMustExistAsAnOrder(): void
    {
        $offer = new Offer();

        $targets = [];
        foreach ($offer->getActiveValidators('order_id') as $validator) {
            if ($validator instanceof ExistValidator) {
                $targets[] = $validator->targetClass;
            }
        }

        self::assertSame([Order::class], $targets, 'order_id is validated against the orders table');
    }

    public function testUnknownOrderIsRejected(): void
    {
        $offer = new Offer(['order_id' => 999999, 'partner_id' => Factory::partner()->id, 'price' => 1]);

        self::assertFalse($offer->validate());
        self::assertArrayHasKey('order_id', $offer->getErrors());
    }

    public function testOnePartnerMayOfferOncePerOrder(): void
    {
        $existing = Factory::offer();

        $duplicate = new Offer(['order_id' => $existing->order_id, 'partner_id' => $existing->partner_id, 'price' => 1]);

        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('partner_id', $duplicate->getErrors());
    }

    public function testSchemaReferencesOrdersTable(): void
    {
        $schema = Yii::$app->db->getTableSchema('offers', true);
        self::assertNotNull($schema);

        $orderKeys = array_values(array_filter($schema->foreignKeys, static fn (array $fk): bool => isset($fk['order_id'])));

        self::assertCount(1, $orderKeys);
        self::assertSame('orders', $orderKeys[0][0], 'offers.order_id must reference orders(id)');
    }
}

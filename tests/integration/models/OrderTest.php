<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Order;
use app\models\Partner;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;
use Yii;

final class OrderTest extends DatabaseTestCase
{
    public function testNewOrderGetsSecurityCodeAndNewStatus(): void
    {
        $order = Factory::order();

        self::assertSame(Order::STATUS_NEW, $order->status);
        self::assertSame(Order::SECURITY_CODE_LENGTH, strlen($order->security_code));
        self::assertSame(['partner_id' => null, 'rating' => null], $order->getAttributes(['partner_id', 'rating']));
    }

    public function testSecurityCodeIsUniqueAndStableAcrossUpdates(): void
    {
        $first = Factory::order();
        $second = Factory::order();
        $code = $first->security_code;

        $first->price = 2000;
        self::assertTrue($first->save());

        self::assertNotSame($first->security_code, $second->security_code);
        self::assertSame($code, $first->security_code);
    }

    public function testProcessing(): void
    {
        $order = Factory::order();

        $order->processing();

        self::assertSame(Order::STATUS_PROCESSING, Order::findOne($order->id)?->status);
    }

    public function testDoneStoresRatingAndUpdatesThePartnerRating(): void
    {
        $partner = Factory::partner();
        $order = Factory::order(['partner_id' => $partner->id]);

        $order->done(4);

        self::assertSame(Order::STATUS_DONE, $order->status);
        self::assertSame(4, $order->rating);
        self::assertSame(4.0, (float) Partner::findOne($partner->id)?->rating);
    }

    public function testQualityCheckNotifiesTheAdmin(): void
    {
        $order = Factory::order(['partner_id' => Factory::partner()->id]);

        $order->qualityCheck();

        self::assertSame(Order::STATUS_QUALITY_CHECK, $order->status);
        $mail = $this->mailbox->assertSentTo(Yii::$app->params['emailFrom']);
        self::assertStringContainsString((string) $order->id, $mail->getSubject());
    }

    public function testOfferRelationReturnsTheChosenPartnersOffer(): void
    {
        $chosen = Factory::partner();
        $other = Factory::partner();
        $order = Factory::order(['partner_id' => $chosen->id]);
        $chosenOffer = Factory::offer(['order_id' => $order->id, 'partner_id' => $chosen->id]);
        Factory::offer(['order_id' => $order->id, 'partner_id' => $other->id]);

        self::assertSame($chosenOffer->id, $order->offer->id);
        self::assertCount(2, $order->offers);
    }

    public function testRatingIsBetweenZeroAndFive(): void
    {
        $order = Factory::order();

        $order->rating = 6;

        self::assertFalse($order->validate());
        self::assertArrayHasKey('rating', $order->getErrors());
    }
}

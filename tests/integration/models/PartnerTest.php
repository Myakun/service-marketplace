<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Order;
use app\models\Partner;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;
use Yii;

final class PartnerTest extends DatabaseTestCase
{
    public function testRegisterSavesInactivePartnerAndNotifiesTheAdmin(): void
    {
        $partner = Partner::register('John Doe', 'john@example.com', 'Acme', '9000000001');

        self::assertFalse($partner->getIsNewRecord());
        self::assertSame(Partner::STATUS_INACTIVE, $partner->status);
        self::assertStringStartsWith('$2y$', $partner->password);

        $mail = $this->mailbox->assertSentTo(Yii::$app->params['emailFrom']);
        self::assertStringContainsString('Acme', $mail->getSubject());
    }

    public function testActivateSetsNewPasswordAndMailsThePartner(): void
    {
        $partner = Factory::partner(['status' => Partner::STATUS_INACTIVE, 'password' => 'old']);
        $oldHash = $partner->password;

        $partner->activate();

        self::assertSame(Partner::STATUS_ACTIVE, $partner->status);
        self::assertNotSame($oldHash, $partner->password);
        self::assertFalse(Yii::$app->security->validatePassword('old', $partner->password));
        $this->mailbox->assertSentTo($partner->email);
    }

    public function testDeactivate(): void
    {
        $partner = Factory::partner();

        $partner->deactivate();

        self::assertSame(Partner::STATUS_INACTIVE, Partner::findOne($partner->id)?->status);
    }

    public function testRatingIsTheAverageOfDoneOrders(): void
    {
        $partner = Factory::partner();
        Factory::order(['partner_id' => $partner->id, 'status' => Order::STATUS_DONE, 'rating' => 4]);
        Factory::order(['partner_id' => $partner->id, 'status' => Order::STATUS_DONE, 'rating' => 5]);
        Factory::order(['partner_id' => $partner->id, 'status' => Order::STATUS_PROCESSING, 'rating' => 1]);

        $partner->calculateRating();

        self::assertSame(4.5, (float) Partner::findOne($partner->id)?->rating);
    }

    public function testRatingWithoutDoneOrdersIsEmptyRatherThanAnError(): void
    {
        $partner = Factory::partner();

        $partner->calculateRating();

        self::assertNull($partner->rating);
    }

    public function testStatusOptionsCoverEveryStatus(): void
    {
        self::assertSame([Partner::STATUS_ACTIVE, Partner::STATUS_INACTIVE], array_keys(Partner::getStatusOptions()));
    }

    public function testPhoneAndEmailMustBeUnique(): void
    {
        $existing = Factory::partner();

        $duplicate = new Partner([
            'contact_person' => 'X', 'name' => 'Y', 'password' => 'z',
            'email' => $existing->email, 'phone' => $existing->phone,
        ]);

        self::assertFalse($duplicate->validate());
        self::assertSame(['email', 'phone'], array_keys($duplicate->getErrors()));
    }
}

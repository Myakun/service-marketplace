<?php

declare(strict_types=1);

namespace app\tests\integration\support;

use app\tests\support\DatabaseTestCase;
use Yii;

final class MailboxTest extends DatabaseTestCase
{
    public function testCapturesMessagesInsteadOfSendingThem(): void
    {
        $mailDir = Yii::getAlias(Yii::$app->mailer->fileTransportPath);
        $filesBefore = count(glob($mailDir . '/*.eml') ?: []);

        Yii::$app->mailer
            ->compose()
            ->setFrom('noreply@example.com')
            ->setTo('someone@example.com')
            ->setSubject('Hello')
            ->setTextBody('Body')
            ->send();

        self::assertSame(1, $this->mailbox->count());
        self::assertSame('Hello', $this->mailbox->last()->getSubject());
        self::assertSame($filesBefore, count(glob($mailDir . '/*.eml') ?: []), 'Nothing reaches the file transport');
    }

    public function testFindsMessageByRecipient(): void
    {
        Yii::$app->mailer->compose()->setFrom('a@example.com')->setTo('first@example.com')->setSubject('One')->send();
        Yii::$app->mailer->compose()->setFrom('a@example.com')->setTo(['second@example.com' => 'Second'])->setSubject('Two')->send();

        self::assertSame('One', $this->mailbox->assertSentTo('first@example.com')->getSubject());
        self::assertSame('Two', $this->mailbox->assertSentTo('second@example.com')->getSubject());
    }

    public function testStartsEmptyForEveryTest(): void
    {
        $this->mailbox->assertNothingSent();
    }
}

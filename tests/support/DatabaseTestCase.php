<?php

declare(strict_types=1);

namespace app\tests\support;

use app\models\User;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\db\Transaction;
use yii\web\IdentityInterface;

/**
 * Base class for tests that touch the database.
 *
 * Every test runs inside a transaction that is rolled back afterwards, so the schema stays in the
 * state the migrations left it in (only the seeded admin exists). Outgoing mail is captured by
 * {@see Mailbox} and never reaches a transport.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected Mailbox $mailbox;

    private Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction = Yii::$app->db->beginTransaction();
        $this->mailbox = Mailbox::open();
        Factory::reset();
    }

    protected function tearDown(): void
    {
        $this->mailbox->close();
        Yii::$app->user->setIdentity(null);
        $this->transaction->rollBack();

        parent::tearDown();
    }

    /**
     * Makes the given identity the current user, which is what BlameableBehavior reads.
     */
    protected function loginAs(IdentityInterface $identity): void
    {
        Yii::$app->user->setIdentity($identity);
    }

    /**
     * The administrator inserted by the users migration.
     */
    protected function admin(): User
    {
        $admin = User::findOne(1);
        self::assertInstanceOf(User::class, $admin, 'The users migration seeds the admin with id 1');

        return $admin;
    }
}

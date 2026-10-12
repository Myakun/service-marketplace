<?php

declare(strict_types=1);

namespace app\tests\utils;

use app\tests\utils\models\ModelTestFactory;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\db\Transaction;
use yii\web\IdentityInterface;

/**
 * Base class for integration tests.
 * Every test runs inside a transaction that is rolled back.
 */
abstract class IntegrationTestCase extends TestCase
{
    private Transaction $transaction;

    protected function loginAs(IdentityInterface $identity): void
    {
        Yii::$app->user->setIdentity($identity);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction = Yii::$app->db->beginTransaction();
        ModelTestFactory::reset();
    }

    protected function tearDown(): void
    {
        Yii::$app->user->setIdentity(null);
        $this->transaction->rollBack();

        parent::tearDown();
    }
}

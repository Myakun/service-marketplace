<?php

declare(strict_types=1);

namespace app\tests\utils;

use app\components\web\WebUser;
use app\models\Customer;
use app\models\Partner;
use app\models\User;
use app\tests\utils\models\ModelTestFactory;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\db\Transaction;

/**
 * Base class for integration tests.
 * Every test runs inside a transaction that is rolled back.
 */
abstract class IntegrationTestCase extends TestCase
{
    private Transaction $transaction;

    protected function loginAsAdmin(User $admin): void
    {
        WebUser::admin()->switchIdentity($admin);
    }

    protected function loginAsCustomer(Customer $customer): void
    {
        WebUser::customer()->switchIdentity($customer);
    }

    protected function loginAsPartner(Partner $partner): void
    {
        WebUser::partner()->switchIdentity($partner);
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->transaction = Yii::$app->db->beginTransaction();
        ModelTestFactory::reset();
    }

    #[\Override]
    protected function tearDown(): void
    {
        WebUser::admin()->setIdentity(null);
        WebUser::customer()->setIdentity(null);
        WebUser::partner()->setIdentity(null);
        $this->transaction->rollBack();

        parent::tearDown();
    }
}

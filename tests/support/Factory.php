<?php

declare(strict_types=1);

namespace app\tests\support;

use app\models\Category;
use app\models\Customer;
use app\models\Offer;
use app\models\Order;
use app\models\Partner;
use app\models\Price;
use app\models\Service;
use app\models\User;
use PHPUnit\Framework\Assert;
use Yii;
use yii\db\ActiveRecord;

/**
 * Builds and saves valid models with sensible defaults; pass attributes to override any of them.
 *
 * Every builder goes through validation, so a factory that fails points at a broken rule or
 * schema, which is exactly what the integration suite is there to catch.
 */
final class Factory
{
    private static int $sequence = 0;

    public static function reset(): void
    {
        self::$sequence = 0;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function user(array $attributes = []): User
    {
        $n = self::next();

        return self::save(new User(), $attributes + [
            'email' => "user{$n}@example.com",
            'name' => "User {$n}",
            'password' => 'secret',
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function customer(array $attributes = []): Customer
    {
        $n = self::next();

        return self::save(new Customer(), $attributes + [
            'email' => "customer{$n}@example.com",
            'name' => "Customer {$n}",
            'password' => 'secret',
            'phone' => self::phone($n),
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function partner(array $attributes = []): Partner
    {
        $n = self::next();

        return self::save(new Partner(), $attributes + [
            'contact_person' => "Contact {$n}",
            'email' => "partner{$n}@example.com",
            'name' => "Partner {$n}",
            'password' => 'secret',
            'phone' => self::phone($n),
            'status' => Partner::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function category(array $attributes = []): Category
    {
        self::ensureIdentity();
        $n = self::next();

        return self::save(new Category(), $attributes + [
            'name' => "Category {$n}",
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function service(array $attributes = []): Service
    {
        self::ensureIdentity();
        $n = self::next();

        return self::save(new Service(), $attributes + [
            'category_id' => $attributes['category_id'] ?? self::category()->id,
            'description' => "Description {$n}",
            'name' => "Service {$n}",
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function price(array $attributes = []): Price
    {
        return self::save(new Price(), $attributes + [
            'partner_id' => $attributes['partner_id'] ?? self::partner()->id,
            'price' => 1000,
            'service_id' => $attributes['service_id'] ?? self::service()->id,
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function order(array $attributes = []): Order
    {
        return self::save(new Order(), $attributes + [
            'customer_id' => $attributes['customer_id'] ?? self::customer()->id,
            'price' => 1000,
            'service_id' => $attributes['service_id'] ?? self::service()->id,
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function offer(array $attributes = []): Offer
    {
        return self::save(new Offer(), $attributes + [
            'order_id' => $attributes['order_id'] ?? self::order()->id,
            'partner_id' => $attributes['partner_id'] ?? self::partner()->id,
            'price' => 900,
        ]);
    }

    private static function next(): int
    {
        return ++self::$sequence;
    }

    private static function phone(int $n): string
    {
        return '9' . str_pad((string) $n, 9, '0', STR_PAD_LEFT);
    }

    /**
     * Category and Service carry BlameableBehavior, which needs a current user.
     */
    private static function ensureIdentity(): void
    {
        if (Yii::$app->user->getIdentity() !== null) {
            return;
        }

        $admin = User::findOne(1);
        Assert::assertInstanceOf(User::class, $admin, 'The users migration seeds the admin with id 1');
        Yii::$app->user->setIdentity($admin);
    }

    /**
     * @template T of ActiveRecord
     *
     * @param T $model
     * @param array<string, mixed> $attributes
     *
     * @return T
     */
    private static function save(ActiveRecord $model, array $attributes): ActiveRecord
    {
        $model->setAttributes($attributes, false);

        Assert::assertTrue(
            $model->save(),
            sprintf('%s could not be saved: %s', $model::class, json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE)),
        );

        return $model;
    }
}

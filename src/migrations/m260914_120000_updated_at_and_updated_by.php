<?php

declare(strict_types=1);

use app\models\Category;
use app\models\Customer;
use app\models\Order;
use app\models\Partner;
use app\models\Price;
use app\models\Service;
use app\models\User;
use yii\db\Migration;

/**
 * Completes the audit trail: every table that records creation now records the last update too.
 */
final class m260914_120000_updated_at_and_updated_by extends Migration
{
    private const array TIMESTAMPED_TABLES = [
        Category::class,
        Customer::class,
        Order::class,
        Partner::class,
        Price::class,
        Service::class,
        User::class,
    ];

    private const array BLAMED_TABLES = [
        Category::class,
        Service::class,
        User::class,
    ];

    public function safeUp(): bool
    {
        foreach (self::TIMESTAMPED_TABLES as $model) {
            $table = $model::tableName();
            $after = in_array($model, self::BLAMED_TABLES, true) ? 'created_by' : 'created_at';

            $this->addColumn($table, 'updated_at', (string) $this->dateTime()->after($after));
            $this->update($table, ['updated_at' => new \yii\db\Expression('created_at')]);
            $this->alterColumn($table, 'updated_at', (string) $this->dateTime()->notNull());
        }

        foreach (self::BLAMED_TABLES as $model) {
            $table = $model::tableName();

            $this->addColumn($table, 'updated_by', (string) $this->integer()->defaultValue(null)->after('updated_at'));
            $this->addForeignKey("fk-{$table}-updated_by", $table, 'updated_by', User::tableName(), 'id');
        }

        return true;
    }

    public function safeDown(): bool
    {
        foreach (self::BLAMED_TABLES as $model) {
            $table = $model::tableName();

            $this->dropForeignKey("fk-{$table}-updated_by", $table);
            $this->dropColumn($table, 'updated_by');
        }

        foreach (self::TIMESTAMPED_TABLES as $model) {
            $this->dropColumn($model::tableName(), 'updated_at');
        }

        return true;
    }
}

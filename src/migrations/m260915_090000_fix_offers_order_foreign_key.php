<?php

declare(strict_types=1);

use app\models\Offer;
use app\models\Order;
use app\models\Service;
use yii\db\Migration;

/**
 * The offers table was created with order_id referencing services(id) instead of orders(id).
 */
final class m260915_090000_fix_offers_order_foreign_key extends Migration
{
    private const string WRONG_KEY = 'offers_ibfk_1';

    private const string KEY = 'fk-offers-order_id';

    public function safeUp(): bool
    {
        $this->dropForeignKey(self::WRONG_KEY, Offer::tableName());
        $this->addForeignKey(self::KEY, Offer::tableName(), 'order_id', Order::tableName(), 'id');

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropForeignKey(self::KEY, Offer::tableName());
        $this->addForeignKey(self::WRONG_KEY, Offer::tableName(), 'order_id', Service::tableName(), 'id');

        return true;
    }
}

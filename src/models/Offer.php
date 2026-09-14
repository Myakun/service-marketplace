<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property Order $order
 * @property int $order_id
 * @property Partner $partner
 * @property int $partner_id
 * @property int $price
 */
class Offer extends ActiveRecord
{
    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getPartner(): ActiveQuery
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public function rules(): array
    {
        return [
            ['order_id', 'required'],
            ['order_id', 'integer'],
            ['order_id', 'exist',
                'targetClass' => Partner::class,
                'targetAttribute' => 'id',
            ],

            ['partner_id', 'required'],
            ['partner_id', 'integer'],
            ['partner_id', 'exist',
                'targetClass' => Partner::class,
                'targetAttribute' => 'id',
            ],
            ['partner_id', 'unique', 'targetAttribute' => ['order_id', 'partner_id']],

            ['price', 'required'],
            ['price', 'integer', 'min' => 0],
        ];
    }

    public static function tableName(): string
    {
        return 'offers';
    }
}

<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property string $created_at
 * @property int $id
 * @property Partner $partner
 * @property int $partner_id
 * @property int $price
 * @property int $service_id
 * @property string $status
 * @property string $updated_at
 *
 * @mixin TimestampBehavior
 */
class Price extends ActiveRecord
{
    public const string STATUS_ACTIVE = 'active';

    public const string STATUS_INACTIVE = 'inactive';

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    public function getPartner(): ActiveQuery
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public function rules(): array
    {
        return [
            ['partner_id', 'required'],
            ['partner_id', 'integer'],
            ['partner_id', 'exist',
                'targetClass' => Partner::class,
                'targetAttribute' => 'id',
            ],

            ['price', 'required'],
            ['price', 'integer', 'min' => 0],

            ['service_id', 'required'],
            ['service_id', 'integer'],
            ['service_id', 'exist',
                'targetClass' => Service::class,
                'targetAttribute' => 'id',
            ],
            ['service_id', 'unique', 'targetAttribute' => ['partner_id', 'service_id']],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public static function tableName(): string
    {
        return 'prices';
    }
}

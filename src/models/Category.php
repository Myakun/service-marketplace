<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\BlameableBehavior;
use app\components\behaviors\TimestampBehavior;
use JetBrains\PhpStorm\ArrayShape;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * @property User $createdBy
 * @property int $id
 * @property string $name
 * @property int $position
 * @property Service[] $services
 *
 * @mixin BlameableBehavior
 * @mixin PositionBehavior
 * @mixin TimestampBehavior
 */
class Category extends ActiveRecord
{
    public const NAME_MAX_LENGTH = 50;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    #[ArrayShape(['name' => 'string'])]
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }

    #[ArrayShape(['blameable' => 'array', 'position' => 'string[]', 'timestamp' => 'array'])]
    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            'position' => [
                'class' => PositionBehavior::class,
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getServices(): ActiveQuery
    {
        return $this->hasMany(Service::class, ['category_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'unique'],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public static function tableName(): string
    {
        return 'categories';
    }
}

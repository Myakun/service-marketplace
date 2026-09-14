<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\BlameableBehavior;
use app\components\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * @property Category $category
 * @property int $category_id
 * @property string $created_at
 * @property int $created_by
 * @property User $createdBy
 * @property string $description
 * @property int $id
 * @property string $name
 * @property int $position
 * @property Price[] $prices
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @mixin BlameableBehavior
 * @mixin PositionBehavior
 * @mixin TimestampBehavior
 */
class Service extends ActiveRecord
{
    public const int NAME_MAX_LENGTH = 50;

    public const string STATUS_ACTIVE = 'active';

    public const string STATUS_INACTIVE = 'inactive';

    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Категория',
            'description' => 'Описание',
            'name' => 'Название',
        ];
    }

    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
            ],
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['category_id'],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }


    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getAveragePrice(): int
    {
        $count = 0;
        $totalPrice = 0;

        foreach ($this->prices as $price) {
            $count++;
            $totalPrice += $price->price;
        }

        if (0 == $count) {
            return 0;
        }

        return (int) ($totalPrice / $count);
    }


    public function getMaxPrice(): int
    {
        $maxPrice = 0;

        foreach ($this->prices as $price) {
            if ($price->price > $maxPrice) {
                $maxPrice = $price->price;
            }
        }

        return $maxPrice;
    }

    public function getMinPrice(): int
    {
        $minPrice = 0;

        foreach ($this->prices as $price) {
            if (null == $minPrice || $price->price < $minPrice) {
                $minPrice = $price->price;
            }
        }

        return $minPrice;
    }

    public function getPrices(): ActiveQuery
    {
        return $this->hasMany(Price::class, ['service_id' => 'id']);
    }

    public function rules(): array
    {
        return [
            ['category_id', 'required'],
            ['category_id', 'integer'],
            ['category_id', 'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
            ],

            ['description', 'string'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'unique', 'targetAttribute' => ['category_id', 'name']],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public static function tableName(): string
    {
        return 'services';
    }
}

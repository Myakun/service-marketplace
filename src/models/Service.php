<?php

declare(strict_types=1);

namespace app\models;

use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii2tech\ar\position\PositionBehavior;

/**
 * @property Category $category
 * @property int $category_id
 * @property User $createdBy
 * @property string $description
 * @property int $id
 * @property string $name
 * @property Price[] $prices
 * @property int $position
 */
class Service extends ActiveRecord
{
    public const NAME_MAX_LENGTH = 50;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    #[ArrayShape(['category_id' => "string", 'description' => "string", 'name' => "string"])]
    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Категория',
            'description' => 'Описание',
            'name' => 'Название',
        ];
    }

    #[ArrayShape(['blameable' => "array", 'position' => "string[]", 'timestamp' => "array"])]
    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['category_id'],
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()')
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
                'targetAttribute' => 'id'
            ],

            ['description', 'string'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'unique', 'targetAttribute' => ['category_id', 'name']],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    public static function tableName(): string
    {
        return 'services';
    }
}

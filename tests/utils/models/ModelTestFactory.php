<?php

declare(strict_types=1);

namespace app\tests\utils\models;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use PHPUnit\Framework\Assert;
use yii\db\ActiveRecord;

abstract class ModelTestFactory
{
    public const string RAW_PASSWORD = 'test1234';

    private const int SEED = 1234;

    private static ?Generator $faker = null;

    protected static function faker(): Generator
    {
        if (self::$faker === null) {
            self::$faker = FakerFactory::create('en_US');
            self::$faker->seed(self::SEED);
        }

        return self::$faker;
    }

    /**
     * Sets the attributes, failing the test on a name the model does not have.
     *
     * @template T of ActiveRecord
     *
     * @param T $model
     * @param array<string, mixed> $attributes
     *
     * @return T
     */
    protected static function fill(ActiveRecord $model, array $attributes): ActiveRecord
    {
        // setAttributes() silently drops names the model does not have, which would hide a typo in a test.
        $unknown = array_diff(array_keys($attributes), $model->attributes());
        Assert::assertSame([], array_values($unknown), sprintf('%s has no such attributes', $model::class));

        $model->setAttributes($attributes, false);

        return $model;
    }

    public static function reset(): void
    {
        self::$faker = null;
    }

    /**
     * @template T of ActiveRecord
     *
     * @param T $model
     *
     * @return T
     */
    protected static function save(ActiveRecord $model): ActiveRecord
    {
        Assert::assertTrue(
            $model->save(),
            sprintf('%s could not be saved: %s', $model::class, json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE)),
        );

        return $model;
    }
}

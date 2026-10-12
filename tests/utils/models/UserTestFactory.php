<?php

declare(strict_types=1);

namespace app\tests\utils\models;

use app\models\User;

final class UserTestFactory extends ModelTestFactory
{
    /**
     * @param array<string, mixed> $attributes
     */
    public static function create(array $attributes = []): User
    {
        return self::save(self::prepare($attributes));
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function prepare(array $attributes = []): User
    {
        return self::fill(new User(), $attributes + [
                'email' => self::faker()->unique()->safeEmail(),
                'name' => self::faker()->name(),
                'password' => self::RAW_PASSWORD,
            ]);
    }
}

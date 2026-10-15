<?php

declare(strict_types=1);

namespace app\components\web;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\User;

/**
 * The authentication component of each section of the site.
 */
final class WebUser extends User
{
    public const string ADMIN = 'adminUser';

    public const string CUSTOMER = 'user';

    public const string PARTNER = 'partnerUser';

    public static function admin(): self
    {
        return self::component(self::ADMIN);
    }

    private static function component(string $id): self
    {
        $component = Yii::$app->get($id);
        if (!$component instanceof self) {
            throw new InvalidConfigException(sprintf('Component "%s" must be an instance of %s.', $id, self::class));
        }

        return $component;
    }

    public static function customer(): self
    {
        return self::component(self::CUSTOMER);
    }

    public static function partner(): self
    {
        return self::component(self::PARTNER);
    }
}

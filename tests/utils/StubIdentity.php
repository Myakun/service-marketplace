<?php

declare(strict_types=1);

namespace app\tests\utils;

use yii\web\IdentityInterface;

final readonly class StubIdentity implements IdentityInterface
{
    public function __construct(private int $id)
    {
    }

    public static function findIdentity($id): ?self
    {
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        return null;
    }

    public function getAuthKey(): ?string
    {
        return null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function validateAuthKey($authKey): ?bool
    {
        return null;
    }
}

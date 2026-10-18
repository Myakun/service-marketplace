<?php

declare(strict_types=1);

namespace app\components\behaviors;

use app\components\web\WebUser;
use Yii;
use yii\web\User;

final class BlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    public string $userComponent = WebUser::ADMIN;

    protected function getValue($event): mixed
    {
        if ($this->value !== null) {
            return parent::getValue($event);
        }

        $user = Yii::$app->get($this->userComponent, false);
        $id = $user instanceof User ? $user->getId() : null;

        return $id ?? $this->getDefaultValue($event);
    }

    public function hasCreator(): bool
    {
        return $this->owner->{$this->createdByAttribute} !== null;
    }
}

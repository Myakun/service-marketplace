<?php

declare(strict_types=1);

namespace app\components\behaviors;

final class BlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    public function hasCreator(): bool
    {
        return $this->owner->{$this->createdByAttribute};
    }
}
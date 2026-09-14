<?php

declare(strict_types=1);

namespace app\components\behaviors;

final class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public function init(): void
    {
        parent::init();

        if ($this->value === null) {
            $this->value = static fn (): string => gmdate('Y-m-d H:i:s');
        }
    }
}

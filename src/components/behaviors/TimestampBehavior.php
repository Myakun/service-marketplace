<?php

declare(strict_types=1);

namespace app\components\behaviors;

final class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public function init(): void
    {
        parent::init();

        // Replace the default behavior timestamp with DATETIME format
        if ($this->value === null) {
            $this->value = static fn (): string => gmdate('Y-m-d H:i:s');
        }
    }
}

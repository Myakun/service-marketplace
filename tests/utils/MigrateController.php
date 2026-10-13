<?php

declare(strict_types=1);

namespace app\tests\utils;

use Override;

/**
 * Runs migrations without console output.
 */
final class MigrateController extends \yii\console\controllers\MigrateController
{
    #[Override]
    public function stderr($string): int
    {
        return 0;
    }

    #[Override]
    public function stdout($string): int
    {
        return 0;
    }
}

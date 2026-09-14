<?php

declare(strict_types=1);

namespace app\tests\support;

use yii\console\controllers\MigrateController;

/**
 * Migrate controller that does not print, so migrations can run inside the PHPUnit bootstrap.
 */
final class SilentMigrateController extends MigrateController
{
    public function stdout($string): int
    {
        return 0;
    }

    public function stderr($string): int
    {
        return 0;
    }
}

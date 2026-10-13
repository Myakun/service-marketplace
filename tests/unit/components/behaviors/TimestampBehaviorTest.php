<?php

declare(strict_types=1);

namespace app\tests\unit\components\behaviors;

use app\components\behaviors\TimestampBehavior;
use Override;
use PHPUnit\Framework\TestCase;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

final class TimestampBehaviorTest extends TestCase
{
    public function testValueIsDatetimeString(): void
    {
        $record = new class () extends ActiveRecord {
            #[Override]
            public function attributes(): array
            {
                return ['created_at', 'updated_at'];
            }

            #[Override]
            public function behaviors(): array
            {
                return ['timestamp' => TimestampBehavior::class];
            }
        };
        $record->trigger(BaseActiveRecord::EVENT_BEFORE_INSERT);

        self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', (string)$record->getAttribute('created_at'));
    }
}

<?php

declare(strict_types=1);

namespace app\tests\unit\components\behaviors;

use app\components\behaviors\BlameableBehavior;
use app\components\web\WebUser;
use app\tests\utils\StubIdentity;
use PHPUnit\Framework\TestCase;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\db\BaseActiveRecord;

final class BlameableBehaviorTest extends TestCase
{
    public function testHasCreator(): void
    {
        $behavior = new BlameableBehavior();
        $behavior->owner = new DynamicModel(['created_by' => null]); // @phpstan-ignore argument.type, assign.propertyType
        self::assertFalse($behavior->hasCreator());

        $behavior->owner = new DynamicModel(['created_by' => 1]); // @phpstan-ignore argument.type, assign.propertyType
        self::assertTrue($behavior->hasCreator());
    }
}

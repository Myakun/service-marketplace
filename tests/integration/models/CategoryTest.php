<?php

declare(strict_types=1);

namespace app\tests\integration\models;

use app\models\Category;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;

final class CategoryTest extends DatabaseTestCase
{
    public function testPositionsAreAssignedSequentially(): void
    {
        $first = Factory::category();
        $second = Factory::category();

        self::assertSame(1, $first->position);
        self::assertSame(2, $second->position);
    }

    public function testServicesRelationReturnsOnlyOwnServices(): void
    {
        $category = Factory::category();
        $other = Factory::category();
        $mine = Factory::service(['category_id' => $category->id]);
        Factory::service(['category_id' => $other->id]);

        self::assertSame([$mine->id], array_map(static fn ($service) => $service->id, $category->services));
    }

    public function testNameMustBeUnique(): void
    {
        Factory::category(['name' => 'Cleaning']);

        $duplicate = new Category(['name' => 'Cleaning']);

        self::assertFalse($duplicate->validate());
        self::assertArrayHasKey('name', $duplicate->getErrors());
    }

    public function testDefaultStatusIsActive(): void
    {
        self::assertSame(Category::STATUS_ACTIVE, Factory::category()->status);
    }
}

<?php

declare(strict_types=1);

namespace app\tests\unit\components\web;

use app\components\web\AssetVersion;
use PHPUnit\Framework\TestCase;

final class AssetVersionTest extends TestCase
{
    public function testAppliesVersionPreservingKeysAndOrder(): void
    {
        $result = AssetVersion::apply(['main' => 'css/app.css', 'print' => 'css/print.css'], 2);

        self::assertSame(['main' => 'css/app.css?v=2', 'print' => 'css/print.css?v=2'], $result);
    }

    public function testAppliesVersionToArrayEntryPathKeepingAttributes(): void
    {
        $result = AssetVersion::apply([['css/print.css', 'media' => 'print']], 7);

        self::assertSame([['css/print.css?v=7', 'media' => 'print']], $result);
    }

    public function testAppliesVersionToExistingQueryString(): void
    {
        self::assertSame(
            ['css/themed.css?rev=1&v=7'],
            AssetVersion::apply(['css/themed.css?rev=1'], 7),
        );
    }

    public function testAppliesVersionToRelativePath(): void
    {
        self::assertSame(['css/app.css?v=7'], AssetVersion::apply(['css/app.css'], 7));
    }

    public function testSkipsAbsoluteUrls(): void
    {
        $assets = ['https://cdn.example.com/lib.css', '//cdn.example.com/lib.js'];

        self::assertSame($assets, AssetVersion::apply($assets, 7));
    }

    public function testSkipsArrayEntryWithoutPath(): void
    {
        self::assertSame([['media' => 'print']], AssetVersion::apply([['media' => 'print']], 7));
    }

    public function testSkipsEmptyList(): void
    {
        self::assertSame([], AssetVersion::apply([], 7));
    }

    public function testSkipsPathWithExplicitVersion(): void
    {
        $assets = ['css/old.css?v=3', 'css/themed.css?theme=dark&v=3', 'css/empty.css?v='];

        self::assertSame($assets, AssetVersion::apply($assets, 7));
    }
}

<?php

declare(strict_types=1);

namespace app\components\helpers;

use yii\helpers\Url;

final class AssetVersion
{
    /**
     * Appends a version query parameter to every relative asset path in the list.
     *
     * Entries may be a plain path or an array whose index 0 is the path and whose
     * remaining keys are HTML attributes, as accepted by {@see \yii\web\AssetBundle::$css}
     * or {@see \yii\web\AssetBundle::$js}.
     * Absolute URLs and paths that already carry an explicit `v` parameter are left untouched.
     *
     * @param array<int|string, string|array<int|string, string>> $assets
     * @return array<int|string, string|array<int|string, string>>
     */
    public static function apply(array $assets, int $version): array
    {
        foreach ($assets as $key => $asset) {
            if (is_string($asset)) {
                $assets[$key] = self::applyToPath($asset, $version);
            } elseif (is_string($asset[0] ?? null)) {
                $assets[$key] = [self::applyToPath($asset[0], $version)] + $asset;
            }
        }

        return $assets;
    }

    private static function applyToPath(string $path, int $version): string
    {
        if (!Url::isRelative($path)) {
            return $path;
        }

        $query = parse_url($path, PHP_URL_QUERY);
        if (is_string($query)) {
            parse_str($query, $params);

            if (array_key_exists('v', $params)) {
                return $path;
            }
        }

        return $path . (is_string($query) ? '&' : '?') . 'v=' . $version;
    }
}

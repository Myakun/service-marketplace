<?php

declare(strict_types=1);

namespace app\assets;

use app\components\web\AssetBundle;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\JqueryAsset;

final class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app';

    public $css = [
        'css/app.css'
    ];

    public $js = [
        'js/app.js'
    ];

    public $depends = [
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        JqueryAsset::class
    ];
}

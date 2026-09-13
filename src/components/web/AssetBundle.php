<?php

declare(strict_types=1);

namespace app\components\web;

use app\components\helpers\AssetVersion;

abstract class AssetBundle extends \yii\web\AssetBundle
{
    protected const int ASSETS_VERSION = 1;

    public function init(): void
    {
        parent::init();

        $this->css = AssetVersion::apply($this->css, static::ASSETS_VERSION);
        $this->js = AssetVersion::apply($this->js, static::ASSETS_VERSION);
    }
}

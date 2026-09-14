<?php

declare(strict_types=1);

namespace app\components\widgets;

use yii\base\Model;

class ActiveForm extends \kartik\widgets\ActiveForm
{
    public $enableClientScript = false;

    public $enableClientValidation = false;

    public $errorSummaryCssClass = 'alert alert-danger';

    /**
     * @param Model|array<Model> $models
     * @param array<string, mixed> $options
     */
    public function errorSummary($models, $options = []): string
    {
        if (!isset($options['header'])) {
            $options['header'] = '';
        }

        return parent::errorSummary($models, $options);
    }
}

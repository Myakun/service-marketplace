<?php

declare(strict_types=1);

use app\components\widgets\ActiveForm;
use app\widgets\FormSubmit\FormSubmit;

/**
 * @var app\modules\admin\models\category\Save $model
 */

$this->title = Yii::t('app', 'New category');

?>

<div class="card">
    <div class="card-header">
        <?php echo $this->title; ?>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="card-body">
        <?php echo $this->render('save/form', [
            'form' => $form,
            'model' => $model,
        ]); ?>
    </div>
    <div class="card-footer">
        <?php echo FormSubmit::widget(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php

declare(strict_types=1);

use app\components\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \app\modules\partner\models\partner\Registration $model
 */

$this->title = Yii::t('app', 'Partner registration');


$form = ActiveForm::begin();

echo $form->errorSummary($model);

echo $form->field($model, 'name');

echo $form->field($model, 'contactPerson');

echo $form
    ->field($model, 'email', [
        'inputOptions' => [
            'type' => 'email',
        ],
    ]);

echo $form
    ->field($model, 'phone')
    ->widget(\yii\widgets\MaskedInput::class, [
        'mask' => '+7 (999) 999-99-99'
    ]);

?>

<div class="d-grid">
    <?php echo Html::submitButton(Yii::t('app', 'Register'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
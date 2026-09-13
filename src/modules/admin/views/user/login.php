<?php

use app\components\widgets\ActiveForm;
use app\modules\admin\models\user\Login;
use himiklab\yii2\recaptcha\ReCaptcha3;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Login $model
 */

$this->title = Yii::t('app', 'Sign in');

$form = ActiveForm::begin();

echo $form->errorSummary($model);

echo $form
        ->field($model, 'email')
        ->input('email');

echo $form
        ->field($model, 'password')
        ->input('password');

echo $form
        ->field($model, 'reCaptcha')
        ->widget(ReCaptcha3::class)
        ->error(false)
        ->label(false)

?>

    <div class="d-grid">
        <?php echo Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
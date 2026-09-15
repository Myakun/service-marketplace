<?php

declare(strict_types=1);

/**
 * @var \app\models\customer\Profile $model
 * @var bool $showSuccessMessage
 */

use app\components\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'My account - profile');

?>

<div class="container">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><?php echo Yii::t('app', 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo $this->title; ?></li>
        </ol>
    </nav>
</div>

<section class="mb-4">
    <div class="container">
        <h2 class="section-title">
            <span><?php echo $this->title; ?></span>
        </h2>
        <div class="section-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="/account"><?php echo Yii::t('app', 'Orders'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/account/profile"><?php echo Yii::t('app', 'Profile'); ?></a>
                </li>
            </ul>
            <br>
            <?php if ($showSuccessMessage) { ?>
                <div class="alert alert-success">
                    <?php echo Yii::t('app', 'Your data has been saved.'); ?>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-12 col-md-4">
                    <?php
                    $form = ActiveForm::begin();

                    echo $form->errorSummary($model);

                    echo $form->field($model, 'name');

                    echo $form
                        ->field($model, 'email')
                        ->input('email');

                    echo $form
                        ->field($model, 'phone')
                        ->widget(\yii\widgets\MaskedInput::class, [
                            'mask' => '+7 (999) 999-99-99'
                        ]);

                    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary'])
                    ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
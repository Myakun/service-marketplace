<?php

declare(strict_types=1);

/**
 * @var app\components\widgets\ActiveForm $form
 * @var app\modules\admin\models\user\Save $model
 */

echo $form->errorSummary([$model, $model->getEntity()])

?>

<div class="row">
    <div class="col-12 col-md-4">
        <?php

        echo $form->field($model, 'name');

        echo $form
                ->field($model, 'email', [
                        'inputOptions' => [
                                'type' => 'email',
                        ],
                ]);
        ?>
    </div>
</div>
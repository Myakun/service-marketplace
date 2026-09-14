<?php

use app\components\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \app\modules\admin\models\service\Index $model
 */

?>

<div class="card" style="clear:both;margin-top:48px;">
    <div class="card-header <?php if ($model->filterEnabled()) { ?>bg-primary text-white<?php } ?>">
        <div class="panel-title">
            <?php echo Yii::t('app', $model->filterEnabled() ? 'Filter applied' : 'Filter'); ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin(['method'=>'get']); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <?php echo $form->field($model, 'name'); ?>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <?php echo $form
                    ->field($model, 'categoryId')
                    ->widget(Select2::class, [
                        'initValueText' => $model->getCategoryIdDisplayName(),
                        'pluginOptions' => [
                            'ajax' => [
                                'url' => Url::to(['/admin/categories/autocomplete']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {query:params.term}; }')
                            ],
                            'minimumInputLength' => 2
                        ]]); ?>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-primary" type="submit"><?php echo Yii::t('app', 'Apply filter'); ?></button>
            <?php if ($model->filterEnabled()) { ?>
                <a class="btn btn-danger" href="/admin/services/index"><?php echo Yii::t('app', 'Reset filter'); ?></a>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>



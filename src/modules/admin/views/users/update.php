<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\components\widgets\ActiveForm;
use app\widgets\FormSubmit\FormSubmit;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\admin\models\user\Save $model
 * @var app\components\web\View $this
 */

$this->title = Yii::t('app', 'Administrator {name}', ['name' => $model->name]);

?>

<?php echo GridView::widget([
        'columns' => include(__DIR__ . '/grid/columns.php'),
        'dataProvider' => $dataProvider,
        'summary' => false,
]); ?>

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

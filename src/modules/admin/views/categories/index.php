<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\admin\models\category\Index $filterModel
 */

$this->title = Yii::t('app', 'Categories');

$useSorting = true;

?>

<?php echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'heading' => $this->title,
    ],
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['data-sortable-id' => $model->id];
    },
    'options' => [
        'data' => [
            'sortable-widget' => 1,
            'sortable-url' => \yii\helpers\Url::toRoute(['sorting']),
        ]
    ],

    'summary' => false,
    'toolbar' => [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
    ]
]); ?>
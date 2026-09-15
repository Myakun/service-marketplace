<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\partner\models\service\Index $filterModel
 */

$this->title = Yii::t('app', 'Services');

?>

<?php echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'before' => $this->render('index/filter', [
            'model' => $filterModel
        ]),
        'heading' => $this->title,
    ],
    'summary' => false,
    'toolbar' => false
]); ?>
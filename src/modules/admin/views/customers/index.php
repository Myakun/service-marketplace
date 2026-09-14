<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use app\models\Customer;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\admin\models\customer\Index $filterModel
 */

$this->title = Yii::t('app', 'Customers');

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
    'toolbar' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
]); ?>
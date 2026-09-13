<?php

declare(strict_types=1);

use app\components\widgets\grid\GridView;
use yii\helpers\Html;

/**
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\admin\models\user\AdminUserList $filterModel
 * @var app\components\web\View $this
 */

$this->title = Yii::t('app', 'Administrators');

?>

<?php echo GridView::widget([
    'columns' => include(__DIR__ . '/grid/columns.php'),
    'dataProvider' => $dataProvider,
    'panel' => [
        'after' => false,
        'heading' => $this->title,
    ],
    'summary' => false,
    'toolbar' => [
        'content' => Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])
    ]
]); ?>
<?php

use app\components\assets\FontAwesome;
use app\models\User;
use kartik\nav\NavX;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

/**
 * @var string $content
 * @var User $identity
 */

$identity = Yii::$app->getUser()->getIdentity();

?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <?php $scheme = Yii::$app->getRequest()->getIsSecureConnection() ? 'https' : 'http'; ?>
    <base href="<?php echo $scheme . '://' . Yii::$app->getRequest()->getServerName() . Yii::$app->getRequest()->getBaseUrl(); ?>">
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title><?php echo Html::encode($this->title); ?></title>
    <?php echo Html::csrfMetaTags(); ?>
    <?php FontAwesome::register($this); ?>
    <?php \app\modules\admin\assets\App\App::register($this); ?>
    <?php $this->head(); ?>
</head>
<body class="<?php echo Yii::$app->language; ?>">
<?php $this->beginBody() ?>
<header class="mb-4">
    <?php
    NavBar::begin([
            'brandLabel' => $identity->name,
            'brandUrl' => ['/admin'],
            'innerContainerOptions' => [
                    'class' => 'container-fluid'
            ]
    ]);
    echo NavX::widget([
            'activateParents' => true,
            'options' => ['class' => 'navbar-nav'],
            'encodeLabels' => false,
            'items' => [
                    [
                            'items' => [
                                    [
                                            'label' => Yii::t('app', 'Categories'),
                                            'url' => ['/admin/categories/index'],
                                    ], [
                                            'label' => Yii::t('app', 'Services'),
                                            'url' => ['/admin/services/index'],
                                    ]
                            ],
                            'label' => Yii::t('app', 'Services'),
                            'url' => '#',
                    ], [
                            'label' => Yii::t('app', 'Orders'),
                            'url' => ['/admin/orders/index'],
                    ], [
                            'label' => Yii::t('app', 'Customers'),
                            'url' => ['/admin/customers/index'],
                    ], [
                            'label' => Yii::t('app', 'Partners'),
                            'url' => ['/admin/partners/index'],
                    ], [
                            'label' => Yii::t('app', 'Administrators'),
                            'url' => ['/admin/users/index'],
                    ],
            ]
    ]);
    echo NavX::widget([
            'activateParents' => true,
            'options' => ['class' => 'navbar-nav ms-auto'],
            'encodeLabels' => false,
            'items' => [
                    [
                            'label' => Yii::t('app', 'Logout'),
                            'url' => ['/admin/user/logout']
                    ],
            ]
    ]);
    NavBar::end();
    ?>
</header>

<div class="container-fluid">
    <?php if (Yii::$app->getSession()->hasFlash('successMessage')) { ?>
        <div class="alert alert-success">
            <?php echo Yii::$app->getSession()->getFlash('successMessage'); ?>
        </div>
    <?php } ?>
    <?php echo $content; ?>
</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>


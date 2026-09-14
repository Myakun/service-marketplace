<?php


use yii\bootstrap5\BootstrapAsset;
use yii\helpers\Html;

/**
 * @var string $content
 */

$this->beginPage();
?>
<!doctype html>
<html lang="<?php echo Yii::$app->language; ?>">
<head>
    <?php $scheme = Yii::$app->getRequest()->getIsSecureConnection() ? 'https' : 'http'; ?>
    <base href="<?php echo $scheme . '://' . Yii::$app->getRequest()->getServerName() . Yii::$app->getRequest()->getBaseUrl(); ?>">
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title><?php echo $this->title; ?></title>
    <?php echo Html::csrfMetaTags(); ?>
    <?php BootstrapAsset::register($this); ?>
    <?php \app\modules\admin\assets\App\App::register($this); ?>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container">
    <div class="d-flex align-justify-content-md-center justify-content-center mt-5">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


<?php

declare(strict_types=1);

use app\components\assets\FontAwesome;
use app\models\User;
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
            <?php $appAsset = \app\assets\AppAsset::register($this); ?>
            <?php $this->head(); ?>
        </head>
        <body class="<?php echo Yii::$app->language; ?>">
            <?php $this->beginBody() ?>
            <?php echo $this->render('main/header', ['appAsset' => $appAsset]); ?>
            <main id="main">
                <div class="container">
                    <?php echo $content; ?>
                </div>
            </main>
            <?php echo $this->render('main/footer', ['appAsset' => $appAsset]); ?>
            <?php $this->endBody(); ?>
        </body>
    </html>
<?php $this->endPage() ?>


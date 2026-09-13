<?php

declare(strict_types=1);

/**
 * @var \app\assets\AppAsset $appAsset
 */

$email = Yii::$app->params['contacts']['email'];
$phone = Yii::$app->formatter->formatPhone(Yii::$app->params['contacts']['phone']);

?>

<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 logo">
                <a href="/">
                    <img class="img-fluid" src="<?php echo $appAsset->baseUrl; ?>/img/logo.svg" alt="">
                </a>
            </div>
            <div class="col-12 col-md-4 copyright">
                Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::$app->params['siteName']; ?>
            </div>
            <div class="col-12 col-md-4 contacts">
                <div class="contact">
                    <a href="tel:<?php echo $phone; ?>>"><?php echo $phone; ?></a>
                </div>
                <div class="contact">
                    <a href="mailto:<?php echo $email; ?>">
                        <?php echo $email; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
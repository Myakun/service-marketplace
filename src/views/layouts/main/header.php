<?php

declare(strict_types=1);

use app\assets\AppAsset;
use yii\bootstrap5\ButtonDropdown;

/**
 * @var AppAsset $appAsset
 */

$isGuest = Yii::$app->getUser()->getIsGuest();

/**
 * @var \app\models\Customer $identity
 */
$identity = Yii::$app->getUser()->getIdentity();

$email = Yii::$app->params['contacts']['email'];
$phone = Yii::$app->formatter->formatPhone(Yii::$app->params['contacts']['phone']);

?>

<header id="header">
    <div id="header-top">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 logo">
                    <a href="/">
                        <img class="img-fluid" src="<?php echo $appAsset->baseUrl; ?>/img/logo.svg" alt="">
                    </a>
                </div>
                <div class="col-12 col-md-5 contacts">
                    <div class="contact">
                        <span class="icon">
                            <span class="fas fa-phone"></span>
                        </span>
                        <a href="tel:<?php echo $phone; ?>>"><?php echo $phone; ?></a>
                    </div>
                    <div class="contact">
                        <span class="icon">
                            <span class="fas fa-envelope"></span>
                        </span>
                        <a href="mailto:<?php echo $email; ?>">
                            <?php echo $email; ?>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-4 user <?php if (!$isGuest) { ?>authed<?php } ?>">
                    <?php if ($isGuest) { ?>
                        <?php echo ButtonDropdown::widget([
                            'buttonOptions' => ['class' => 'btn-warning'],
                            'dropdown' => [
                                'items' => [
                                    ['label' => Yii::t('app', 'Customer'), 'url' => '/account'],
                                    ['label' => Yii::t('app', 'Partner'), 'url' => '/partner'],
                                ],
                            ],
                            'label' => Yii::t('app', 'My account'),
                        ]); ?>
                    <?php } else { ?>
                        <span><?php echo Yii::t('app', 'Hello, {name}', ['name' => empty($identity->name) ? $identity->email : $identity->name]); ?></span>
                        <div>
                            <a class="btn btn-link btn-sm" href="/account"><?php echo Yii::t('app', 'My account'); ?></a>
                            <a class="btn btn-link btn-sm" href="/customer/logout"><?php echo Yii::t('app', 'Logout'); ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="header-menu">
        <?php echo \app\widgets\MainMenu\MainMenu::widget(); ?>
    </div>
</header>
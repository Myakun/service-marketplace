<?php

declare(strict_types=1);

/**
 * @var app\models\Customer $customer
 * @var app\models\Order $order
 * @var string|null $password
 * @var app\models\Service $service
 */

?>

<?php if (empty($customer->name)) { ?>
    <?php echo Yii::t('app', 'Hello!'); ?>
<?php } else { ?>
    <?php echo Yii::t('app', 'Hello, {name}.', ['name' => $customer->name]); ?>
<?php } ?>

<br><br>

<?php echo Yii::t('app', '{site} has found potential providers for {service}.', ['site' => Yii::$app->params['siteName'], 'service' => $service->name]); ?>
<br>
<?php echo Yii::t('app', 'To view the list, follow the link:'); ?> <a href="<?php echo $order->getLink(); ?>"><?php echo $order->getLink(); ?></a>
<br><br>

<?php if (null != $password) { ?>
    <?php echo Yii::t('app', 'To proceed with your order (selecting a suitable provider), you will need to sign in to the website.'); ?>
    <br>
    <?php echo Yii::t('app', 'Your password: {password}', ['password' => $password]); ?>
<?php } ?>







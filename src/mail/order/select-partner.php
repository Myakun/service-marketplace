<?php

declare(strict_types=1);

/**
 * @var app\models\Order $order
 * @var app\models\Service $service
 */

?>

<?php echo Yii::t('app', 'Hello!'); ?>
<br><br>
<?php echo Yii::t('app', 'You have been selected to provide the service {service}.', ['service' => $service->name]); ?>
<br><br>
<?php echo Yii::t('app', 'Order details:'); ?> <a href="<?php echo $order->getPartnerLink(); ?>"><?php echo $order->getPartnerLink(); ?></a>




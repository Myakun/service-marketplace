<?php

declare(strict_types=1);

/**
 * @var app\models\Order $order
 */

?>

<?php echo Yii::t('app', 'Hello!'); ?>
<br><br>
<?php echo Yii::t('app', 'Partner {partner} has completed the work for order #{id}.', ['partner' => $order->partner->name, 'id' => $order->id]); ?>
<br>
<?php echo Yii::t('app', 'Please contact the customer to check the quality of the provided service.'); ?>
<br><br>
<?php echo Yii::t('app', 'Order:'); ?> <a href="<?php echo $order->getAdminLink(); ?>"><?php echo $order->getAdminLink(); ?></a>




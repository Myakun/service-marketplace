<?php

declare(strict_types=1);

use app\models\Order;

/**
 * @var Order $order
 */

?>

<?php echo $order->getStatusName(); ?>

<?php if (Order::STATUS_CALL == $order->status) {  ?>
    <br>
    <br>
    <button class="btn btn-success btn-sm set-next-status"><?php echo Yii::t('app', 'Details agreed'); ?></button>
    &nbsp;&nbsp;
    <button class="btn btn-danger btn-sm set-status-new"><?php echo Yii::t('app', 'Decline order'); ?></button>
<?php } elseif (Order::STATUS_PROCESSING == $order->status) { ?>
    <br>
    <br>
    <button class="btn btn-success btn-success btn-sm set-next-status"><?php echo Yii::t('app', 'Order completed'); ?></button>
<?php } ?>



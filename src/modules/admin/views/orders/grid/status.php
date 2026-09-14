<?php

declare(strict_types=1);

use app\models\Order;

/**
 * @var Order $order
 */

?>

<?php echo $order->getStatusName(); ?>

<?php if (Order::STATUS_QUALITY_CHECK == $order->status) {  ?>
    <br>
    <br>
    <button class="btn btn-primary btn-sm set-rating"><?php echo Yii::t('app', 'Rate'); ?></button>
<?php } ?>



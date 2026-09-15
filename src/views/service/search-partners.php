<?php

declare(strict_types=1);

/**
 * @var app\models\Order $order
 */

?>

<?php echo Yii::t('app', 'To view the list, follow the link:'); ?> <a href="<?php echo $order->getLink(); ?>"><?php echo $order->getLink(); ?></a>

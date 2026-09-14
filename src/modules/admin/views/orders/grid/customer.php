<?php

declare(strict_types=1);

/**
 * @var app\models\Customer $customer
 */

?>

<b><?php echo Yii::t('app', 'Name'); ?>:</b> <?php echo $customer->name; ?>
<br>
<b><?php echo Yii::t('app', 'Phone'); ?>:</b> <?php echo Yii::$app->formatter->formatPhone($customer->phone); ?>
<br>
<b><?php echo Yii::t('app', 'Email'); ?>:</b> <?php echo $customer->email; ?>

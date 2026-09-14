<?php

declare(strict_types=1);

use app\models\Customer;

/**
 * @var Customer $customer
 */

?>

<?php echo $customer->id; ?>
<br>
<small class="text-muted">
    <?php echo Yii::t('app', 'Created at'); ?>
    <?php echo Yii::$app->formatter->asDatetime($customer->created_at); ?>
</small>
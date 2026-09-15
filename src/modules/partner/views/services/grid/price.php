<?php

declare(strict_types=1);

/**
 * @var \app\modules\partner\models\Service $service
 */

?>

<?php if (null == $service->price) { ?>
    <button class="btn btn-success btn-sm set-price"><?php echo Yii::t('app', 'Set price'); ?></button>
<?php } else { ?>
    <?php echo $service->price->price; ?>
    <br>
    <br>
    <button class="btn btn-success btn-sm set-price"><?php echo Yii::t('app', 'Set new price'); ?></button>
    <br><br>
    <button class="btn btn-danger btn-sm delete-price"><?php echo Yii::t('app', 'Service no longer provided'); ?></button>
<?php } ?>

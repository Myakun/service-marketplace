<?php

declare(strict_types=1);

use app\models\Partner;

/**
 * @var Partner $partner
 */

?>

<?php echo Partner::getStatusOptions()[$partner->status]; ?>
<?php if (Partner::STATUS_INACTIVE == $partner->status) {  ?>
    <br>
    <br>
    <button class="btn btn-primary btn-sm activate-partner"><?php echo Yii::t('app', 'Activate'); ?></button>
<?php } else { ?>
    <br>
    <br>
    <button class="btn btn-primary btn-danger btn-sm deactivate-partner"><?php echo Yii::t('app', 'Deactivate'); ?></button>
<?php } ?>



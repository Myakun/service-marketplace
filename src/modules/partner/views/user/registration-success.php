<?php

declare(strict_types=1);

/**
 * @var \app\models\Partner $partner
 */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Registration completed');

?>

<div class="alert alert-success">
    <?php echo Yii::t('app', 'You have successfully registered as a partner.'); ?>
    <br>
    <?php echo Yii::t('app', 'Our representative will contact you to verify your account, after which you will get access to your partner dashboard.'); ?>
</div>

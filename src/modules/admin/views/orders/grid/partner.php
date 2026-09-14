<?php

declare(strict_types=1);

/**
 * @var app\models\Partner $partner
 */

?>

<b><?php echo Yii::t('app', 'Name'); ?>:</b> <?php echo $partner->name; ?>
<br>
<b><?php echo Yii::t('app', 'Contact person'); ?>:</b> <?php echo $partner->contact_person; ?>
<br>
<b><?php echo Yii::t('app', 'Phone'); ?>:</b> <?php echo Yii::$app->formatter->formatPhone($partner->phone); ?>
<br>
<b><?php echo Yii::t('app', 'Email'); ?>:</b> <?php echo $partner->email; ?>

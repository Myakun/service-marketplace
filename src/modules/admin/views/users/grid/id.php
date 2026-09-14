<?php

declare(strict_types=1);

use app\models\User;

/**
 * @var User $user
 */

?>

<?php echo $user->id; ?>
<br>
<small class="text-muted">
    <?php echo Yii::t('app', 'Created at'); ?>
    <?php /** @noinspection PhpUnhandledExceptionInspection */
    echo Yii::$app->formatter->asDatetime($user->created_at); ?>
    <?php if ($user->hasCreator()) { ?>
        <?php echo Yii::t('app', 'by'); ?>
        <?php echo $user->createdBy->name; ?>
    <?php } ?>
</small>
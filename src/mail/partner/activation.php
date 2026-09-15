<?php

declare(strict_types=1);

/**
 * @var app\models\Partner $partner
 * @var string $password
 */

$link = \yii\helpers\Url::to(['/partner'], true);

?>

<?php echo Yii::t('app', 'Hello!'); ?>
<br><br>
<?php echo Yii::t('app', 'The administrator of {site} has activated your partner account.', ['site' => Yii::$app->params['siteName']]); ?>
<br><br>
<?php echo Yii::t('app', 'Partner account:'); ?> <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
<br><br>
<?php echo Yii::t('app', 'Login password: {password}', ['password' => $password]); ?>



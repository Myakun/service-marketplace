<?php

declare(strict_types=1);

/**
 * @var app\models\Partner $partner
 */

?>

<?php echo Yii::t('app', 'Hello!'); ?>
<br><br>

<?php echo Yii::t('app', 'A new partner has registered on {site}: {partner}', ['site' => Yii::$app->params['siteName'], 'partner' => $partner->name]); ?>
<br><br>



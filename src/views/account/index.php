<?php

declare(strict_types=1);

/**
 * @var \app\models\Order[] $orders
 */

$this->title = Yii::t('app', 'My account - orders');

?>

<div class="container">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><?php echo Yii::t('app', 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo $this->title; ?></li>
        </ol>
    </nav>
</div>

<section>
    <div class="container">
        <h2 class="section-title">
            <span><?php echo $this->title; ?></span>
        </h2>
        <div class="section-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="/account"><?php echo Yii::t('app', 'Orders'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/account/profile"><?php echo Yii::t('app', 'My profile'); ?></a>
                </li>
            </ul>
            <table class="table table-bordered mt-4">
                <tr>
                    <th>#</th>
                    <th><?php echo Yii::t('app', 'Date'); ?></th>
                    <th><?php echo Yii::t('app', 'Service'); ?></th>
                    <th><?php echo Yii::t('app', 'Expected price'); ?></th>
                    <th><?php echo Yii::t('app', 'Status'); ?></th>
                    <th></th>
                </tr>
                <?php foreach ($orders as $order) { ?>
                    <tr>
                        <td>
                            <?php echo $order->id; ?>
                        </td>
                        <td>
                            <?php echo Yii::$app->formatter->asDate($order->created_at); ?>
                        </td>
                        <td>
                            <?php echo $order->service->name; ?>
                        </td>
                        <td>
                            <?php echo number_format($order->price, 0, ',', ' '); ?>
                        </td>
                        <td>
                            <?php echo $order->getStatusName(); ?>
                        </td>
                        <td>
                            <a class="btn btn-link btn-sm" href="<?php echo $order->getLink(); ?>"><?php echo Yii::t('app', 'Details'); ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</section>
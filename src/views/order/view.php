<?php

declare(strict_types=1);

/**
 * @var app\models\Order $order
 */

use kartik\widgets\StarRating;

$this->title = Yii::t('app', 'Order #{id}', ['id' => $order->id]);

$identity = null;
$isGuest = Yii::$app->getUser()->getIsGuest();
if (!$isGuest) {
    $identity = Yii::$app->getUser()->getIdentity();
    /**
     * @var app\models\Customer $identity
     */
}

?>

<div class="container">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><?php echo Yii::t('app', 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="/account"><?php echo Yii::t('app', 'My account'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo $this->title; ?></li>
        </ol>
    </nav>
</div>

<section id="order">
    <div class="container">
        <h2 class="section-title">
            <span><?php echo $this->title; ?></span>
        </h2>
        <div class="section-body">
            <?php if (!$isGuest) { ?>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="/order/<?php echo $order->security_code; ?>">
                            <?php echo $this->title; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/account"><?php echo Yii::t('app', 'Orders'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/account/profile"><?php echo Yii::t('app', 'My profile'); ?></a>
                    </li>
                </ul>
            <?php } ?>
            <br>
            <?php if ($order->status == 'new') { ?>
                <table class="table table-bordered">
                    <tr>
                        <th><?php echo Yii::t('app', 'Company'); ?></th>
                        <th><?php echo Yii::t('app', 'Service price'); ?></th>
                        <th><?php echo Yii::t('app', 'Company rating'); ?></th>
                        <th></th>
                    </tr>
                    <?php foreach ($order->offers as $offer) { ?>
                        <tr>
                            <td>
                                <?php echo $offer->partner->name; ?>
                            </td>
                            <td>
                                <?php echo number_format($offer->price, 0, ',', ' '); ?> <?php echo Yii::t('app', 'RUB'); ?>
                            </td>
                            <td class="rating">
                                <?php if (null == $offer->partner->rating) { ?>
                                    <?php echo Yii::t('app', 'No reviews yet'); ?>
                                <?php } else { ?>
                                    <?php echo StarRating::widget([
                                        'name' => 'rating' . $offer->id,
                                        'value' => $offer->partner->rating,
                                        'pluginOptions' => [
                                            'readonly' => true,
                                            'showClear' => false,
                                            'showCaption' => false,
                                        ]
                                    ]); ?>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isGuest) { ?>
                                    <?php echo Yii::t('app', 'To select a provider, please <a href="{url}">sign in</a>', ['url' => '/customer/login']); ?>
                                <?php } elseif ($identity->isProfileFilled()) { ?>
                                    <button
                                            class="btn btn-success btn-sm select-partner"
                                            data-offer-id="<?php echo $offer->id; ?>"
                                            data-order-id="<?php echo $order->id; ?>">
                                        <span class="icon fas fa-spinner fa-spin"></span>
                                        <span class="text"><?php echo Yii::t('app', 'Select'); ?></span>
                                    </button>
                                <?php } else { ?>
                                    <?php echo Yii::t('app', 'To select a provider, please fill in your <a href="{url}">profile</a>', ['url' => '/account/profile']); ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <h3><?php echo Yii::t('app', 'Your order is {status}', ['status' => mb_strtolower($order->getStatusNameForCustomer(), 'utf-8')]); ?></h3>
                <b><?php echo Yii::t('app', 'Provider:'); ?></b> <?php echo $order->partner->name; ?>
                <br>
                <b><?php echo Yii::t('app', 'Price:'); ?></b> <?php echo number_format($order->price, 0, ',', ' '); ?> <?php echo Yii::t('app', 'RUB'); ?>
                <?php if (!empty($order->offer->description)) { ?>
                    <br>
                    <b><?php echo Yii::t('app', 'Service notes:'); ?></b>
                    <br>
                    <?php echo $order->offer->description; ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>
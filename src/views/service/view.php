<?php

declare(strict_types=1);

/**
 * @var \app\models\Service $service
 */

$this->title = $service->name;

$isGuest = Yii::$app->getUser()->getIsGuest();

?>

<div class="container">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><?php echo Yii::t('app', 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="/category/<?php echo $service->category->id; ?>">
                    <?php echo $service->category->name; ?>
                </a>
            </li>
            <li class="breadcrumb-item active"><?php echo $service->name; ?></li>
        </ol>
    </nav>
</div>

<section id="service" data-id="<?php echo $service->id; ?>">
    <div class="container">
        <h2 class="section-title">
            <span><?php echo Yii::t('app', 'Find a provider for {service}', ['service' => $service->name]); ?></span>
        </h2>
        <div class="section-body">
            <p id="search-partner-text">
                <?php echo Yii::t('app', 'To find a provider, please specify the price you are willing to pay for the service.'); ?>
            </p>
            <div id="search-partner-price-slider">
                <div id="search-partner-price-slider-value">
                    <span id="search-partner-price-slider-value-value">
                        <?php echo $service->getAveragePrice(); ?>
                    </span>
                    <?php echo Yii::t('app', 'RUB'); ?>
                </div>
                <div id="search-partner-price-slider-slider">
                    <div class="form-group">
                        <input
                                class="form-range"
                                max="<?php echo $service->getMaxPrice(); ?>"
                                min="<?php echo $service->getMinPrice(); ?>"
                                type="range"
                                value="<?php echo $service->getAveragePrice(); ?>">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-warning btn-lg" id="search-partner-start-search"><?php echo Yii::t('app', 'Search'); ?></button>
            </div>
            <div class="d-none <?php if ($isGuest) { ?>is-guest<?php } ?>" id="search-partner-result">
                <?php if ($isGuest) { ?>
                    <div class="alert alert-success">
                        <?php echo Yii::t('app', 'We have found providers matching your request.'); ?>
                        <br>
                        <?php echo Yii::t('app', 'Enter your email address to receive the list of providers.'); ?>
                    </div>
                    <div class="row" id="search-partner-guest-data">
                        <div class="col-12 col-md-4 col-lg-3">
                            <input id="search-partner-guest-data-email" type="email" class="form-control" placeholder="<?php echo Yii::t('app', 'Your email address'); ?>">
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-warning" id="search-partner-start-search-guest">
                            <span class="icon fas fa-spinner fa-spin"></span>
                            <span class="text"><?php echo Yii::t('app', 'Get the list'); ?></span>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
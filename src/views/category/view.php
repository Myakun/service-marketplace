<?php

declare(strict_types=1);

/**
 * @var \app\models\Category $category
 */

$this->title = $category->name;

?>

<div class="container">
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><?php echo Yii::t('app', 'Home'); ?></a>
            </li>
            <li class="breadcrumb-item active"><?php echo $category->name; ?></li>
        </ol>
    </nav>
</div>

<section id="category">
    <div class="container">
        <h2 class="section-title">
            <span><?php echo $category->name; ?></span>
        </h2>
        <div class="table-wrapper">
            <table class="table table-bordered">
                <tr>
                    <th>
                        <?php echo Yii::t('app', 'Service'); ?>
                    </th>
                    <th>
                        <?php echo Yii::t('app', 'Average price'); ?>
                    </th>
                    <th></th>
                </tr>
                <?php foreach ($category->services as $service) { ?>
                    <tr>
                        <td>
                            <?php echo $service->name; ?>
                        </td>
                        <td>
                            <?php echo number_format($service->getAveragePrice(), 0, ',', ' '); ?>
                        <td>
                            <a href="/service/<?php echo $service->id; ?>" class="btn btn-warning btn-sm"><?php echo Yii::t('app', 'Find a provider'); ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</section>


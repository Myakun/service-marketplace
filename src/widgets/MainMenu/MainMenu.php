<?php

declare(strict_types=1);

namespace app\widgets\MainMenu;

use app\models\Category;
use app\models\Service;
use Yii;
use yii\base\Widget;
use yii\db\ActiveQuery;
use yii\helpers\Url;

class MainMenu extends Widget
{
    public function run(): string
    {
        $items = [];

        $query = Category::find()
            ->andWhere([Category::tableName() . '.status' => Category::STATUS_ACTIVE])
            ->with([
                'services' => function (ActiveQuery $query) {
                    $query->andWhere([Service::tableName() . '.status' => Service::STATUS_ACTIVE]);
                },
            ]);

        foreach ($query->all() as $category) {
            /**
             * @var Category $category
             */
            $item = [
                'label' => $category->name,
                'url' => Url::to(['/category/' . $category->id]),
            ];

            if (count($category->services) > 0) {
                $item['items'] = [];
                $i = 0;
                foreach ($category->services as $service) {
                    if ($i > 7) {
                        $item['items'][] = [
                            'label' => Yii::t('app', 'All services'),
                            'url' => $item['url']
                        ];

                        break;
                    }


                    /**
                     * @var Service $service
                     */
                    $item['items'][] = [
                        'label' => $service->name,
                        'url' => Url::to(['/service/' . $service->id]),
                    ];

                    $i++;
                }
            }

            $items[] = $item;
        }

        return $this->render('main-menu-widget', [
            'items' => $items,
        ]);
    }
}
<?php

declare(strict_types=1);

namespace app\modules\partner\models\order;

use app\components\base\FilterModel;
use app\models\Order;
use app\models\Partner;
use Yii;
use yii\db\ActiveQuery;

class Index extends FilterModel
{
    public ?string $customer = null;

    public ?string $id = null;

    public ?string $status = null;

    public function attributeLabels(): array
    {
        $labels = (new Order())->attributeLabels();

        return [
            'customer' => Yii::t('app', 'Customer'),
            'status' => $labels['status'],
        ];
    }


    public static function getStatusOptions(): array
    {
        return [
            Order::STATUS_DONE => Yii::t('app', 'Completed'),
            Order::STATUS_CALL => Yii::t('app', 'Agreeing on details'),
            Order::STATUS_NEW => Yii::t('app', 'Waiting for a provider to be chosen'),
            Order::STATUS_PROCESSING => Yii::t('app', 'In progress'),
            Order::STATUS_QUALITY_CHECK => Yii::t('app', 'Quality check'),
        ];
    }

    public function getQuery(): ActiveQuery
    {
        $tableName = Order::tableName();

        /**
         * @var Partner $partner
         */
        $partner = Yii::$app->getUser()->getIdentity();
        $query = Order::find()
            ->andWhere(['partner_id' => $partner->id])
            ->with(['customer', 'service'])
            ->orderBy("{$tableName}.id DESC");

        if (null != $this->customer) {
            $this->enableFilter();
            $query->innerJoinWith(['customer' => function (ActiveQuery $query) {
                $query->andWhere(
                    ['or',
                        ['like', 'email', $this->customer],
                        ['like', 'name', $this->customer],
                        ['like', 'phone', $this->customer]
                    ]
                );
            }]);
        }

        if (null != $this->id) {
            $query->andWhere(['id' => $this->id]);
        }

        if (null != $this->status) {
            $this->enableFilter();
            $query->andWhere(["{$tableName}.status" => $this->status]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['customer', 'safe'],

            ['id', 'safe'],

            ['status', 'safe'],
        ];
    }
}
<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * @property string $created_at
 * @property Customer $customer
 * @property int $customer_id
 * @property int $id
 * @property Offer $offer
 * @property Offer[] $offers
 * @property Partner $partner
 * @property int $partner_id
 * @property int $price
 * @property int|null $rating
 * @property string $security_code
 * @property Service $service
 * @property int $service_id
 * @property string $status
 * @property string $updated_at
 *
 * @mixin TimestampBehavior
 */
class Order extends ActiveRecord
{
    public const int SECURITY_CODE_LENGTH = 8;

    public const string STATUS_DONE = 'done';

    public const string STATUS_NEW = 'new';

    public const string STATUS_CALL = 'call';

    public const string STATUS_PROCESSING = 'processing';

    public const string STATUS_QUALITY_CHECK = 'quality-check';

    public function attributeLabels(): array
    {
        return [
            'customer_id' => 'Клиент',
            'partner_id' => 'Партнёр',
            'price' => 'Цена',
            'service_id' => 'Услуга',
            'status' => 'Статус',
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->getIsNewRecord()) {
            $this->security_code = Yii::$app->getSecurity()->generateRandomString(self::SECURITY_CODE_LENGTH);
        }

        return true;
    }

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    public function done(int $rating): void
    {
        $this->status = self::STATUS_DONE;
        $this->rating = $rating;
        $this->save();

        $this->partner->calculateRating();
    }

    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getLink(): string
    {
        return Url::to(['/order/' . $this->security_code], true);
    }

    public function getOffer(): ActiveQuery
    {
        return $this
            ->hasOne(Offer::class, [
                'order_id' => 'id',
                'partner_id' => 'partner_id',
            ]);
    }

    public function getOffers(): ActiveQuery
    {
        return $this->hasMany(Offer::class, ['order_id' => 'id']);
    }

    public function getPartner(): ActiveQuery
    {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public function getAdminLink(): string
    {
        return Url::to(['/admin/orders/index?Index[id]=' . $this->id], true);
    }

    public function getPartnerLink(): string
    {
        return Url::to(['/partner/orders/index?Index[id]=' . $this->id], true);
    }

    public function getService(): ActiveQuery
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    public function getStatusName(): string
    {
        return match($this->status) {
            self::STATUS_CALL => 'Согласование деталей',
            self::STATUS_DONE => 'Выполнен',
            self::STATUS_NEW => 'В ожидании выбора исполнителя',
            self::STATUS_PROCESSING => 'В работе',
            self::STATUS_QUALITY_CHECK => 'Проверка качества',
        };
    }

    public function getStatusNameForCustomer(): string
    {
        return match($this->status) {
            self::STATUS_CALL => 'Принят, представитель компании позвонит вам для согласования деталей',
            self::STATUS_DONE => 'Выполнен',
            self::STATUS_NEW => 'В ожидании выбора исполнителя',
            self::STATUS_PROCESSING => 'В работе',
            self::STATUS_QUALITY_CHECK => 'Выполнен',
        };
    }

    public function processing(): void
    {
        $this->status = self::STATUS_PROCESSING;
        $this->save();
    }

    public function rules(): array
    {
        return [
            ['customer_id', 'required'],
            ['customer_id', 'integer'],
            ['customer_id', 'exist',
                'targetClass' => Customer::class,
                'targetAttribute' => 'id',
            ],

            ['partner_id', 'integer'],
            ['partner_id', 'exist',
                'targetClass' => Partner::class,
                'targetAttribute' => 'id',
            ],
            ['partner_id', 'default', 'value' => null],

            ['price', 'required'],
            ['price', 'integer'],

            ['security_code', 'string', 'length' => self::SECURITY_CODE_LENGTH],
            ['security_code', 'unique'],

            ['service_id', 'required'],
            ['service_id', 'integer'],
            ['service_id', 'exist',
                'targetClass' => Service::class,
                'targetAttribute' => 'id',
            ],

            ['rating', 'integer', 'min' => 0, 'max' => 5],
            ['rating', 'default', 'value' => null],

            ['status', 'in', 'range' => [
                self::STATUS_CALL, self::STATUS_DONE, self::STATUS_NEW, self::STATUS_PROCESSING, self::STATUS_QUALITY_CHECK],
            ],
            ['status', 'default', 'value' => self::STATUS_NEW],
        ];
    }

    public static function tableName(): string
    {
        return 'orders';
    }

    public function qualityCheck(): void
    {
        $this->status = self::STATUS_QUALITY_CHECK;
        $this->save();

        Yii::$app->mailer
            ->compose('order/quality-check', [
                'order' => $this,
            ])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setSubject(
                sprintf(
                    '%s - необходимо провести контроль качества заказа №%d',
                    Yii::$app->params['siteName'],
                    $this->id
                )
            )
            ->setTo(Yii::$app->params['emailFrom'])
            ->send();
    }
}

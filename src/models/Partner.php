<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $contact_person
 * @property string $created_at
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $phone
 * @property float|null $rating
 * @property string $status
 * @property string $updated_at
 *
 * @mixin TimestampBehavior
 */
class Partner extends ActiveRecord implements IdentityInterface
{
    public const int CONTACT_PERSON_MAX_LENGTH = 100;

    public const int NAME_MAX_LENGTH = 150;

    public const int PASSWORD_MIN_LENGTH = 8;

    public const int PHONE_LENGTH = 10;

    public const string STATUS_ACTIVE = 'active';

    public const string STATUS_INACTIVE = 'inactive';

    public function activate(): void
    {
        $password = Yii::$app->security->generateRandomString(self::PASSWORD_MIN_LENGTH);

        $this->password = $password;
        $this->status = self::STATUS_ACTIVE;
        $this->save();

        Yii::$app->mailer
            ->compose('partner/activation', [
                'password' => $password,
                'partner' => $this,
            ])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setSubject(Yii::t('app', 'Partner account activation on {site}', ['site' => Yii::$app->params['siteName']]))
            ->setTo($this->email)
            ->send();
    }

    public function attributeLabels(): array
    {
        return [
            'contact_person' => Yii::t('app', 'Contact person'),
            'email' => 'Email',
            'name' => Yii::t('app', 'Company name'),
            'phone' => Yii::t('app', 'Phone'),
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert || $this->isAttributeChanged('password')) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
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

    public function calculateRating(): void
    {
        $orders = Order::find()
            ->andWhere([
                'partner_id' => $this->id,
                'status' => Order::STATUS_DONE,
            ])
            ->all();

        $total = 0;
        foreach ($orders as $order) {
            /**
             * @var Order $order
             */
            $total += $order->rating;
        }

        $this->rating = round($total / count($orders), 1);
        $this->save();
    }

    public function deactivate(): void
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    public static function findIdentity($id): ?self
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        return null;
    }

    public function getAuthKey(): ?string
    {
        return null;
    }

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
    }

    public static function register(string $contactPerson, string $email, string $name, string $phone): self
    {
        $partner = new self();
        $partner->contact_person = $contactPerson;
        $partner->email = $email;
        $partner->name = $name;
        $partner->phone = $phone;
        $partner->password = Yii::$app->security->generateRandomString(self::PASSWORD_MIN_LENGTH);
        $partner->save();

        Yii::$app->mailer
            ->compose('admin/partner-register', [
                'partner' => $partner,
            ])
            ->setFrom(Yii::$app->params['emailFrom'])
            ->setSubject(Yii::t('app', 'New partner {name}', ['name' => $partner->name]))
            ->setTo(Yii::$app->params['emailFrom'])
            ->send();

        return $partner;
    }

    public function rules(): array
    {
        return [
            ['contact_person', 'required'],
            ['contact_person', 'string', 'max' => self::CONTACT_PERSON_MAX_LENGTH],

            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],

            ['password', 'required',
                'when' => function (self $user) {
                    return $user->getIsNewRecord();
                },
            ],

            ['phone', 'required'],
            ['phone', 'string', 'length' => self::PHONE_LENGTH],
            ['phone', 'unique'],

            ['rating', 'number'],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    public static function tableName(): string
    {
        return 'partners';
    }

    public function validateAuthKey($authKey): ?bool
    {
        return null;
    }
}

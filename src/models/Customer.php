<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\TimestampBehavior;
use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $created_at
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $phone
 * @property string $status
 * @property string $updated_at
 *
 * @mixin TimestampBehavior
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public const NAME_MAX_LENGTH = 150;

    public const PASSWORD_MIN_LENGTH = 8;

    public const PHONE_LENGTH = 10;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    #[ArrayShape([
        'email' => 'string',
        'name' => 'string',
        'phone' => 'string',
    ])]
    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'name' => 'Имя',
            'phone' => 'Телефон',
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

    #[ArrayShape(['blameable' => 'array', 'timestamp' => 'array'])]
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
        ];
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

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public function isProfileFilled(): bool
    {
        return $this->name !== null && $this->phone !== null;
    }

    public static function register(string $email): self
    {
        $password = Yii::$app->security->generateRandomString(self::PASSWORD_MIN_LENGTH);//$password = 'customer';

        $customer = new self();
        $customer->email = $email;
        $customer->password = $password;
        $customer->save();

        return $customer;
    }

    public function rules(): array
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique'],

            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],
            ['name', 'default', 'value' => null],

            ['password', 'required',
                'when' => function (self $user) {
                    return $user->getIsNewRecord();
                },
            ],

            ['phone', 'string', 'length' => self::PHONE_LENGTH],
            ['phone', 'unique'],
            ['phone', 'default', 'value' => null],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    public static function tableName(): string
    {
        return 'customers';
    }

    public function validateAuthKey($authKey): ?bool
    {
        return null;
    }
}

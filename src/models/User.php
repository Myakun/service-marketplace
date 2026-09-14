<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\BlameableBehavior;
use app\components\behaviors\TimestampBehavior;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $created_at
 * @property int|null $created_by
 * @property User|null $createdBy
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $updated_at
 * @property int|null $updated_by
 *
 * @mixin BlameableBehavior
 * @mixin TimestampBehavior
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const int NAME_MAX_LENGTH = 100;

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Name'),
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert || $this->isAttributeChanged('password')) {
            try {
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            } catch (Exception $e) {
                Yii::error("Can't hash user password: {$e->getMessage()}");
                $this->addError('password', Yii::t('app', "Can't update user password"));

                return false;
            }
        }

        return true;
    }

    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
            ],
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

    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getId(): int
    {
        return $this->getPrimaryKey();
    }

    public function rules(): array
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'unique'],

            ['name', 'required'],
            ['name', 'string', 'max' => self::NAME_MAX_LENGTH],

            ['password', 'required',
                'when' => function (self $user) {
                    return $user->getIsNewRecord();
                }
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public function validateAuthKey($authKey): ?bool
    {
        return null;
    }
}

<?php

declare(strict_types=1);

namespace app\models;

use app\components\behaviors\BlameableBehavior;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * @mixin BlameableBehavior
 *
 * @property string $created_at
 * @property int|null $created_by
 * @property User|null $createdBy
 * @property string $email
 * @property int $id
 * @property string $name
 * @property string $password
 * @property string $updated_at
 * @property int|null $updated_by
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
                'updatedByAttribute' => false,
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()')
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

<?php

declare(strict_types=1);

namespace app\modules\admin\models\user;

use app\models\User;
use app\components\web\crud\Model;
use Yii;
use yii\db\ActiveRecord;

class Save extends Model
{
    public ?string $email = null;

    public ?string $name = null;

    private ?string $password = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        if ($entity->getIsNewRecord()) {
            return;
        }

        /**
         * @var User $entity
         */

        $this->email = $entity->email;
        $this->name = $entity->name;
    }

    public function attributeLabels(): array
    {
        $labels = (new User())->attributeLabels();

        return [
            'name' => $labels['name']
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if ($this->entity->getIsNewRecord()) {
          //  $this->password = Yii::$app->getSecurity()->generateRandomString(User::PASSWORD_MIN_LENGTH);
            $this->password = 'admin';
        }

        return true;
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'email' => $this->email,
            'name' => $this->name,
        ]);

        if ($this->entity->getIsNewRecord()) {
            $this->entity->password = $this->password;
        }
    }

    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'email'],
            ['email', 'required'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => User::NAME_MAX_LENGTH],
        ];
    }

    public function save(): void
    {
        parent::save();

        // TODO: Send the password to the user by email
    }
}
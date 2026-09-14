<?php

declare(strict_types=1);

namespace app\modules\admin\models\customer;

use app\components\web\crud\Model;
use app\models\Customer;
use app\models\Producer;
use app\models\Product;
use app\models\Series;
use app\models\StorageMode;
use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property Customer $entity
 */
class Save extends Model
{
    public ?string $email = null;

    public ?string $name = null;

    public ?string $phone = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Customer $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->email = $entity->email;
        $this->name = $entity->name;
        $this->phone = Yii::$app->formatter->formatPhone($entity->phone);
    }

    public function attributeLabels(): array
    {
        $labels = (new Customer())->attributeLabels();

        return [
            'email' => $labels['email'],
            'name' => $labels['name'],
            'phone' => $labels['phone'],
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
        ]);

        if ($this->entity->getIsNewRecord()) {
            $this->entity->password = Yii::$app->getSecurity()->generateRandomString(Customer::PASSWORD_MIN_LENGTH);
        }
    }

    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Customer::NAME_MAX_LENGTH],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => function() {
                return str_replace([' ', '+7', '-', '(', ')'], '', $this->phone);
            }],
            ['phone', 'required'],
            ['phone', 'string', 'length' => Customer::PHONE_LENGTH],
        ];
    }

    public function save(): void
    {
        if ($this->entity->getIsNewRecord()) {
            Customer::register($this->email, $this->name, $this->phone);
            return;
        }

        $this->entity->save();
    }
}
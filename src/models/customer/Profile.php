<?php

declare(strict_types=1);

namespace app\models\customer;

use app\models\Customer;
use app\components\web\crud\Model;
use yii\db\ActiveRecord;

class Profile extends Model
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

        $this->email = $entity->email;
        $this->name = $entity->name;
        $this->phone = $entity->phone;
    }

    public function attributeLabels(): array
    {
        $labels = (new Customer())->attributeLabels();

        return [
            'email' => $labels['email'],
            'name' => $labels['name'],
            'phone' => $labels['phone']
        ];
    }

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
        ]);
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
            ['name', 'string', 'max' => Customer::NAME_MAX_LENGTH],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => function() {
                return str_replace([' ', '+7', '-', '(', ')'], '', $this->phone);
            }],
            ['phone', 'required'],
            ['phone', 'string', 'length' => Customer::PHONE_LENGTH],
        ];
    }
}
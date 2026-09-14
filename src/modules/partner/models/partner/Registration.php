<?php

declare(strict_types=1);

namespace app\modules\partner\models\partner;

use app\models\Partner;
use Yii;
use yii\base\Model;

class Registration extends Model
{
    public ?string $contactPerson = null;

    public ?string $email = null;

    public ?string $name = null;

    public ?string $phone = null;

    public function attributeLabels(): array
    {
        $labels = (new Partner())->attributeLabels();

        return [
            'contactPerson' => $labels['contact_person'],
            'email' => $labels['email'],
            'name' => $labels['name'],
            'phone' => $labels['phone'],
        ];
    }

    public function rules(): array
    {
        return [
            ['contactPerson', 'filter', 'filter' => 'trim'],
            ['contactPerson', 'required'],
            ['contactPerson', 'string', 'max' => Partner::CONTACT_PERSON_MAX_LENGTH],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Partner::NAME_MAX_LENGTH],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => function() {
                return str_replace([' ', '+7', '-', '(', ')'], '', $this->phone);
            }],
            ['phone', 'required'],
            ['phone', 'string', 'length' => Partner::PHONE_LENGTH],
        ];
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        if (!parent::validate($attributeNames, $clearErrors)) {
            return false;
        }

        $partner = new Partner();
        $partner->setAttributes([
            'contact_person' => $this->contactPerson,
            'email' => $this->email,
            'name' => $this->name,
            'password' => Yii::$app->getSecurity()->generateRandomString(Partner::PASSWORD_MIN_LENGTH),
            'phone' => $this->phone,
        ]);

        return $partner->validate();
    }
}
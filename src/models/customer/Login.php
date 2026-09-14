<?php

declare(strict_types=1);

namespace app\models\customer;

use app\models\Customer;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use yii\base\Model;
use Yii;

class Login extends Model
{
    public ?string $email = null;

    public ?string $password = null;

    public ?string $reCaptcha = null;

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    public function loginRule(): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $customer = Customer::findOne([
            'email' => $this->email,
            'status' => Customer::STATUS_ACTIVE,
        ]);

        if (null == $customer || !Yii::$app->getSecurity()->validatePassword($this->password, $customer->password)) {
            $this->addError('password', 'Неправильные данные для входа');
        }
    }

    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter'=>'trim'],
            ['email', 'filter', 'filter'=>'strtolower'],
            ['email', 'email'],
            ['email', 'required'],

            ['password', 'filter', 'filter'=>'trim'],
            ['password', 'required'],
            ['password', 'loginRule'],

            ['reCaptcha', ReCaptchaValidator3::class],
        ];
    }
}
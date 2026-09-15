<?php

declare(strict_types=1);

namespace app\modules\partner\models\user;

use app\models\Partner;
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
            'password' => Yii::t('app', 'Password'),
        ];
    }

    public function loginRule(): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $partner = Partner::findOne([
            'email' => $this->email,
        ]);

        if (null == $partner || !Yii::$app->getSecurity()->validatePassword($this->password, $partner->password)) {
            $this->addError('password', Yii::t('app', 'Invalid email or password.'));
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
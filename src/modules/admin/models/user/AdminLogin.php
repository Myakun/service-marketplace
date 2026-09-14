<?php

declare(strict_types=1);

namespace app\modules\admin\models\user;

use app\models\User;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;
use yii\base\Model;
use Yii;

final class AdminLogin extends Model
{
    public string $email = '';

    public string $password = '';

    public string $reCaptcha = '';

    public function attributeLabels(): array
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    public function loginRule(): void
    {
        if ($this->hasErrors()) {
            return;
        }

        $user = User::findOne([
            'email' => $this->email,
        ]);

        if (null == $user || !Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
            $this->addError('password', Yii::t('app', 'Invalid email or password.'));
        }
    }

    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'filter', 'filter' => 'strtolower'],
            ['email', 'email'],
            ['email', 'required'],

            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'loginRule'],

            ['reCaptcha', ReCaptchaValidator3::class],
        ];
    }
}

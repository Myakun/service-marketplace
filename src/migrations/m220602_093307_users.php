<?php

declare(strict_types=1);

use app\models\User;
use yii\db\Migration;

class m220602_093307_users extends Migration
{
    public function safeDown(): bool
    {
        $this->dropTable(User::tableName());

        return true;
    }

    public function safeUp(): bool
    {
        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull()->unique(),
            'name' => $this->string(100)->notNull(),
            'password' => $this->string(100)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->defaultValue(null),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer()->defaultValue(null)
        ]);

        $user = new User();
        $user->setAttributes([
            'email' => 'admin@example.com',
            'name' => Yii::t('app', 'Administrator'),
            'password' => 'admin'
        ]);
        $user->save();

        return true;
    }
}

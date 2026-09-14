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
        ]);

        // Plain insert on purpose: the model's behaviors and rules evolve with later migrations.
        $this->insert(User::tableName(), [
            'email' => 'admin@example.com',
            'name' => 'Administrator',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'created_at' => gmdate('Y-m-d H:i:s'),
        ]);

        return true;
    }
}

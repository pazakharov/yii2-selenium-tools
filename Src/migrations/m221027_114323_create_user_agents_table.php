<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_agents}}`.
 */
class m221027_114323_create_user_agents_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_agents}}', [
            'id' => $this->primaryKey(),
            'ua' => $this->string(),
            'created_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_agents}}');
    }
}

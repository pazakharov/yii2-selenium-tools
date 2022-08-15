<?php

use yii\db\Migration;

class m000000_000001_create_step_browser_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%step_browser_profiles}}', [
            'id' => $this->primaryKey(),
            'uuid' => $this->string(),
            'title' => $this->string(),
            'user_agent' => $this->text(),
            'proxy' => $this->string(),
            'window_size' => $this->string(),
            'chrome_binary' => $this->text(),
            'webdriver_binary' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%step_browser_profiles}}');
    }
}

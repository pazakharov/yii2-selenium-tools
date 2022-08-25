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
            'type' => $this->string(),
            'title' => $this->string(),
            'os' => $this->string(),
            'user_agent' => $this->text(),
            'language' => $this->text(),
            'time_zone' => $this->text(),
            'geo' => $this->string(),
            'proxy' => $this->string(),
            'window_size' => $this->string(),
            'webgl_vendor' => $this->text(),
            'webgl_renderer' => $this->text(),
            'webrtc' => $this->string(),
            'canvas' => $this->string(),
            'chrome_binary' => $this->text(),
            'audio_context' => $this->string(),
            'fonts' => $this->text(),
            'media_hardware' => $this->text(),
            'platform' => $this->text(),
            'fix_hairline' => $this->boolean()->defaultValue(false),
            'local_storage' => $this->string(),
            'extentions_storage' => $this->string(),
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

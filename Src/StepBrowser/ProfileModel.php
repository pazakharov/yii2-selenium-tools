<?php

namespace Zakharov\Yii2SeleniumTools\StepBrowser;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * ProfileModel
 */
class ProfileModel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%step_browser_profiles}}';
    }

    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['uuid'], 'required'],

            [['title'], 'string'],
            [['title'], 'required'],

            [['user_agent'], 'string'],
            [['user_agent'], 'required'],

            [['language'], 'string'],
            [['language'], 'required'],

            [['webgl_vendor'], 'string'],
            [['webgl_vendor'], 'required'],

            [['webgl_renderer'], 'string'],
            [['webgl_renderer'], 'required'],

            [['platform'], 'string'],
            [['platform'], 'required'],

            [['fix_hairline'], 'boolean'],

            [['proxy'], 'string'],

            [['window_size'], 'string'],

            [['chrome_binary'], 'string'],
            [['webdriver_binary'], 'string'],

            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => date_create()->format('Y-m-d H:i:s'),
            ]
        ];
    }
}

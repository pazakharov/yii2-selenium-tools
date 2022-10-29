<?php

namespace Zakharov\Yii2SeleniumTools\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_agents".
 *
 * @property int $id
 * @property string|null $ua
 * @property string|null $created_at
 */
class UserAgent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_agents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['ua'], 'string', 'max' => 255],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                'value' => (new \DateTime())->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ua' => 'Ua',
            'created_at' => 'Created At',
        ];
    }
}

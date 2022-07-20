<?php

namespace Helper;

use yii\helpers\ArrayHelper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{

    public function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        return new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => dirname(__DIR__) . '/../../../vendor',
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost:3306;dbname=test',
                    'username' => 'root',
                    'password' => '',
                    'tablePrefix' => 'tb_'
                ],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                        ]
                    ]
                ],
                'settings' => [
                    'class' => 'pheme\settings\components\Settings'
                ],
                'cache'  => [
                    'class' => 'yii\caching\ArrayCache'
                ],
            ],
            'modules' => [
                'settings' => [
                    'class' => 'pheme\settings\Module',
                    'sourceLanguage' => 'en'
                ]
            ]
        ], $config));
    }
}

<?php

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../vendor/autoload.php';

// $alias = \Yii::getAlias('@app');

// \Yii::$app->setModule('seleniumTools', [
//     'class' => \Zakharov\Yii2SeleniumTools\SeleniumToolsModule::class,
//     'params' => [
//         'screenshotPath' => '@app/runtime'
//     ]
// ]);

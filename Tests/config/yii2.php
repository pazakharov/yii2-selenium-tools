<?php

use yii\console\Application;

return [
    'class' => Application::class,
    'id' => 'testapp',
    'name' => 'testapp',
    'bootstrap' => ['seleniumTools'],
    'basePath' => __DIR__ . '/../',
    'vendorPath' => dirname(__DIR__) . '/../../vendor',
    'aliases' => [
        '@app' => \yii\helpers\FileHelper::normalizePath(__DIR__ . '/../../'),
    ],
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => env('DB_DSN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
        ]
    ],
    'modules' => [
        'seleniumTools' => [
            'class' => \Zakharov\Yii2SeleniumTools\SeleniumToolsModule::class,
            'screenshotPath' => codecept_data_dir('screenshots'),
            'defaultChromeBinary' => env('CHROME_BINARY_PATH', null),
            'defaultWebdriverBinary' => env('CHROME_DRIVER_EXECUTABLE', null),
            'params' => [
                'headless' => true,
                'profilesDirectory' => '@app/Tests/_data/profiles',
                'chromeDriverPortMin' => env('CHROME_DRIVER_PORT_MIN', null),
                'chromeDriverPortMax' => env('CHROME_DRIVER_PORT_MAX', null),
                'executorConnectionTimeoutMs' => env('CHROME_EXECUTOR_CONNECTION_TIMEOUT_MS', 120000),
                'executorRequestTimeoutMs' => env('EXECUTOR_REQUEST_TIMEOUT_MS', 120000),
                'PageLoadTimeTimeoutS' => env('PAGE_LOAD_TIME_TIMEOUT_S', 120),

            ]
        ]
    ]
];

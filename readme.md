# Yii2 Selenium tools
## Install
- Add to composer.json repository
```json
"repositories": [
        ...// any other repositories
        {
            "type": "vcs",
            "url": "git@github.com:pazakharov/yii2-selenium-tools.git"
        }
        ...// any other repositories
    ]
```
 - install via composer or add record to composer.json
```bash
composer require pazakharov/yii2-selenium-tools
```
- configure module to yii2 application acording to example configuration
```php
[
    // ... other app config
'modules' => [
        'seleniumTools' => [
            'class' => \Zakharov\Yii2SeleniumTools\SeleniumToolsModule::class,
            'screenshotPath' => '@app/runtime/screenshots',
            'defaultChromeBinary' => env('CHROME_BINARY_PATH', null),
            'defaultWebdriverBinary' => env('CHROME_DRIVER_EXECUTABLE', null),
            'params' => [
                'headless' => false,
                'profilesDirectory' => '@app/runtime/profiles',
                'chromeDriverPortMin' => env('CHROME_DRIVER_PORT_MIN', null),
                'chromeDriverPortMax' => env('CHROME_DRIVER_PORT_MAX', null),
                'executorConnectionTimeoutMs' => env('CHROME_EXECUTOR_CONNECTION_TIMEOUT_MS', 120000),
                'executorRequestTimeoutMs' => env('EXECUTOR_REQUEST_TIMEOUT_MS', 120000),
                'PageLoadTimeTimeoutS' => env('PAGE_LOAD_TIME_TIMEOUT_S', 120),
            ]
        ]
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@vendor/pazakharov/yii2-selenium-tools/Src/migrations'
                ],
        ]
    ]
]
```

## Contributing and develop this project
- Code format rules based on PSR-12 ./phpcs.xml
- In order to run test you should run migrations
```bash
    .\vendor\bin\yii migrate/up --appconfig=Tests/config/yii2.php
```
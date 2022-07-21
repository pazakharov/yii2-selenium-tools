<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use yii\helpers\FileHelper;
use Codeception\Lib\ParamsLoader;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Zakharov\Yii2SeleniumTools\Tests\unit\BaseUnitTest;
use Zakharov\Yii2SeleniumTools\Tests\Moks\SeleniumAction;
use Zakharov\Yii2SeleniumTools\Tests\Moks\InfoSeleniumAction;
use Zakharov\Yii2SeleniumTools\Tests\Moks\ScreenshotSeleniumAction;

class BaseSeleniumActionTest extends BaseUnitTest
{
    protected function _before()
    {
        parent::_before();
    }

    protected function _after()
    {
        parent::_after();
    }

    // tests
    public function testActionCanRun()
    {
        $action = Yii::createObject([
            'class' => SeleniumAction::class,
            'driver' => $this->driver
        ]);
        $result = $action->run();
        $this->assertTrue($result);
    }

    public function testActionCanTakeScreenshots()
    {
        $action = Yii::createObject([
            'class' => ScreenshotSeleniumAction::class,
            'driver' => $this->driver
        ]);
        $result = $action->run();
        $this->assertTrue($result);

        $this->assertTrue(file_exists($action->file));
        try {
            FileHelper::removeDirectory(codecept_data_dir('screenshots'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function testActionCanCreateInfo()
    {
        $action = Yii::createObject([
            'class' => InfoSeleniumAction::class,
            'driver' => $this->driver
        ]);
        $result = $action->run();
        $this->assertTrue($result);
    }
}

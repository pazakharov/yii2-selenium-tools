<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use Codeception\Lib\ParamsLoader;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use yii\helpers\FileHelper;
use Zakharov\Yii2SeleniumTools\Tests\Moks\SeleniumAction;
use Zakharov\Yii2SeleniumTools\Tests\Moks\ScreenshotSeleniumAction;

class BaseSeleniumActionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $driver;

    protected function _before()
    {
        $this->tester->mockApplication([
            'aliases' => [
                '@app' => \yii\helpers\FileHelper::normalizePath(__DIR__ . '/../../'),
            ],
            'modules' => [
                'seleniumTools' => [
                    'class' => \Zakharov\Yii2SeleniumTools\SeleniumToolsModule::class,
                    'screenshotPath' => codecept_data_dir('screenshots'),
                ]
            ]
        ]);
        $load = new ParamsLoader();
        $load->load('.env');
        $chromeBinaryPath = env('CHROME_BINARY_PATH');
        $chromeOptions = (new ChromeOptions())
            ->addArguments(['--no-sandbox'])
            ->addArguments(['--window-size=1920,1080'])
            ->addArguments(['--start-maximized'])
            ->addArguments(['--disable-gpu'])
            ->addArguments(['--mute-audio'])
            ->addArguments(['--disable-dev-shm-usage'])
            ->setBinary($chromeBinaryPath);
        $chromeOptions->addArguments(['--headless']);
        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
        $this->driver = ChromeDriver::start($desiredCapabilities);
    }

    protected function _after()
    {
        $this->driver->quit();
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
}

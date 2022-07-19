<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use Codeception\Lib\ParamsLoader;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Zakharov\Yii2SeleniumTools\Tests\Moks\SeleniumAction;

class BaseSeleniumActionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $driver;

    protected function _before()
    {
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
        // $chromeOptions->addArguments(['--headless']);
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
    }
}

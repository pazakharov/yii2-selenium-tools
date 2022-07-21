<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use yii\mail\Composer;
use yii\helpers\FileHelper;
use Facebook\WebDriver\WebDriver;
use Codeception\Lib\Interfaces\Web;
use Zakharov\Yii2SeleniumTools\Tests\unit\BaseUnitTest;
use Zakharov\Yii2SeleniumTools\Tests\Moks\InfoSeleniumAction;
use Zakharov\Yii2SeleniumTools\Tests\Moks\ScreenshotSeleniumAction;
use Zakharov\Yii2SeleniumTools\SeleniumActions\CompositeSeleniumAction;

class CompositeSeleniumActionTest extends BaseUnitTest
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        parent::_before();
    }

    protected function _after()
    {
        parent::_after();
    }

    // tests
    public function testCompositeActionCanRun()
    {
        $counter = 0;
        $scenario = Yii::createObject(['class' => CompositeSeleniumAction::class, 'driver' => $this->driver]);
        $scenario
            ->addAction($action = new ScreenshotSeleniumAction())
            ->addAction($action2 = new ScreenshotSeleniumAction())
            ->addAction($action3 = new ScreenshotSeleniumAction())
            ->addAction(new InfoSeleniumAction())
            ->addMiddleware(function ($driver) use (&$counter) {
                if ($driver instanceof WebDriver) {
                    $counter++;
                }
            })
            ->run();
        $this->assertTrue(file_exists($action->file));
        $this->assertTrue(file_exists($action2->file));
        $this->assertTrue(file_exists($action3->file));
        $this->assertEquals($counter, 4);
        try {
            FileHelper::removeDirectory(codecept_data_dir('screenshots'));
        } catch (\Throwable $th) {
            throw $th;
        }
        $scenario->flushActions();
        $this->assertEmpty($scenario->getActions());
    }
}

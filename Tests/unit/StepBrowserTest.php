<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Facebook\WebDriver\WebDriver;
use Yii;
use yii\helpers\FileHelper;
use Zakharov\Yii2SeleniumTools\Tests\unit\BaseUnitTest;
use Zakharov\Yii2SeleniumTools\StepBrowser\ProfileModel;
use Zakharov\Yii2SeleniumTools\StepBrowser\StepBrowserComponent;

class StepBrowserTest extends BaseUnitTest
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        parent::_before();
        Yii::$app->runAction('migrate', ['migrationPath' => '@app/Src/migrations/', 'interactive' => 0]);
    }

    protected function _after()
    {
        parent::_after();
        $module = Yii::$app->getModule('seleniumTools');
        $path = FileHelper::normalizePath(Yii::getAlias("{$module->params['profilesDirectory']}"));
        FileHelper::removeDirectory($path);
    }

    // tests
    public function testCreateNewProfile()
    {
        $component = Yii::$container->get(StepBrowserComponent::class);
        $component = Yii::$container->get(StepBrowserComponent::class);
        $profile = $component->createNewProfile(
            [
                'title' => 'new profile',
                'proxy' => 'https://example.com:3128',
                'user_agent' => 'Test user agent',
                'window_size' => '1920,1080',
            ]
        );
        $this->assertInstanceOf(ProfileModel::class, $profile);
        $this->assertTrue($profile->save());
        $dbProfile = $this->tester->grabRecord(ProfileModel::class, ['id' => $profile->id]);
        $this->assertEquals($profile->toArray(), $dbProfile->toArray());
    }

    public function testOpenProfile()
    {
        $component = Yii::$container->get(StepBrowserComponent::class);
        $profile = $component->createNewProfile(
            [
                'title' => 'new profile',
                'proxy' => 'https://example.com:3128',
                'user_agent' => 'Test user agent',
                'window_size' => '1920,1080',
            ]
        );
        $this->assertInstanceOf(ProfileModel::class, $profile);
        $driver = $component->openProfile($profile);
        $this->assertInstanceOf(WebDriver::class, $driver);
        $driver->quit();
    }
}

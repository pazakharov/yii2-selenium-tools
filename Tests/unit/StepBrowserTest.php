<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Yii;
use yii\helpers\FileHelper;
use Zakharov\Yii2SeleniumTools\Tests\unit\BaseUnitTest;
use Zakharov\Yii2SeleniumTools\StepBrowser\ProfileModel;
use Zakharov\Yii2SeleniumTools\StepBrowser\StepBrowserComponent;
use Zakharov\Yii2SeleniumTools\StepBrowser\StepSeleniumStealth;

class StepBrowserTest extends BaseUnitTest
{
    /**
     * @var \UnitTester
     */
    protected $tester;

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
        $profile = $component->createNewProfile(
            [
                'title' => 'new profile',
                'proxy' => 'https://example.com:3128',
                'user_agent' => 'Test user agent',
                'platform' => 'Win64',
                'fix_hairline' => false,
                'type' => null,
                'os' => null,
                'time_zone' => null,
                'geo' => null,
                'webrtc' => null,
                'canvas' => null,
                'audio_context' => null,
                'fonts' => null,
                'media_hardware' => null,
                'local_storage' => null,
                'extentions_storage' => null,
                'language' => 'ru-RU',
                'webgl_vendor' => 'Google Inc. (NVIDIA)',
                'webgl_renderer' => 'ANGLE (NVIDIA, NVIDIA GeForce GTX 1650 Direct3D11 vs_5_0 ps_5_0, D3D11)',
                'window_size' => '1920,1080',
            ]
        );
        $this->assertInstanceOf(ProfileModel::class, $profile);
        $this->assertTrue($profile->save());
        $dbProfile = $this->tester->grabRecord(ProfileModel::class, ['id' => $profile->id]);
        $dbProfile->fix_hairline = (bool)$dbProfile->fix_hairline;
        $this->assertEquals($profile->toArray(), $dbProfile->toArray());
    }

    public function testOpenProfile()
    {
        $component = Yii::$container->get(StepBrowserComponent::class);
        $profile = $component->createNewProfile(
            [
                'title' => 'new profile',
                'proxy' => 'https://example.com:3128',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36',
                'language' => 'kok,ml-IN,en-US,en',
                'platform' => 'Win64',
                'fix_hairline' => true,
                'webgl_vendor' => 'Google Inc. (NVIDIA)',
                'webgl_renderer' => 'ANGLE (NVIDIA, NVIDIA GeForce GTX 1650 Direct3D11 vs_5_0 ps_5_0, D3D11)',
                'window_size' => '1600,800',
                'type' => null,
                'os' => null,
                'time_zone' => null,
                'geo' => null,
                'webrtc' => null,
                'canvas' => null,
                'audio_context' => null,
                'fonts' => null,
                'media_hardware' => null,
                'local_storage' => null,
                'extentions_storage' => null,
            ]
        );
        $this->assertInstanceOf(ProfileModel::class, $profile);
        $driver = $component->openProfile($profile);
        $stelth = StepSeleniumStealth::forProfile($driver, $profile);
        $stelth->makeStealth();
        $this->assertInstanceOf(WebDriver::class, $driver);
        $testPage = FileHelper::normalizePath(codecept_data_dir('Pages\antibot\antibot.html'));
        $driver->get($testPage);

        $ua = $driver->findElement(WebDriverBy::id('user-agent-result'));
        $this->assertEquals($ua->getText(), $profile->user_agent);
        $this->assertEquals($ua->getAttribute('class'), 'result passed');

        $wb = $driver->findElement(WebDriverBy::id('webdriver-result'));
        $this->assertEquals($wb->getAttribute('class'), 'result passed');

        $wba = $driver->findElement(WebDriverBy::id('advanced-webdriver-result'));
        $this->assertEquals($wba->getAttribute('class'), 'result passed');

        $chrome = $driver->findElement(WebDriverBy::id('chrome-result'));
        $this->assertEquals($chrome->getAttribute('class'), 'result passed');

        $permissions =  $driver->findElement(WebDriverBy::id('permissions-result'));
        $this->assertEquals($permissions->getAttribute('class'), 'result passed');

        $plugins =  $driver->findElement(WebDriverBy::id('plugins-length-result'));
        $this->assertEquals($plugins->getAttribute('class'), 'result passed');

        $pluginsType =  $driver->findElement(WebDriverBy::id('plugins-type-result'));
        $this->assertEquals($pluginsType->getAttribute('class'), 'result passed');

        $languages =  $driver->findElement(WebDriverBy::id('languages-result'));
        $this->assertEquals($languages->getAttribute('class'), 'result passed');
        $this->assertEquals($languages->getText(), $profile->language);

        $webGlVendor =  $driver->findElement(WebDriverBy::id('webgl-vendor'));
        $this->assertEquals($webGlVendor->getAttribute('class'), 'passed');
        $this->assertEquals($webGlVendor->getText(), $profile->webgl_vendor);

        $webGlRenderer =  $driver->findElement(WebDriverBy::id('webgl-renderer'));
        $this->assertEquals($webGlRenderer->getAttribute('class'), 'passed');
        $this->assertEquals($webGlRenderer->getText(), $profile->webgl_renderer);

        $hairline =  $driver->findElement(WebDriverBy::id('hairline-feature'));
        $this->assertEquals($hairline->getAttribute('class'), 'passed');

        $driver->quit();
    }
}

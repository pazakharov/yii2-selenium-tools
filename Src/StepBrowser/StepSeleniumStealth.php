<?php

namespace Zakharov\Yii2SeleniumTools\StepBrowser;

use Facebook\WebDriver\WebDriver;
use SapiStudio\SeleniumStealth\SeleniumStealth;
use Zakharov\Yii2SeleniumTools\StepBrowser\ProfileModel;

class StepSeleniumStealth extends SeleniumStealth
{
    /**
     * Static factory method to create a new StepSeleniumStealth instance from a driver object and a profile model
     *
     * @param  WebDriver $driver
     * @param  ProfileModel $profile
     * @return static
     */
    public static function forProfile(WebDriver $driver, ProfileModel $profile)
    {
        $instance = new static(
            $driver,
            "",                             //'user_agent'
            [],                             // 'languages'
            $profile->webgl_vendor,                  //'vendor'
            $profile->platform,                        //'platform'
            $profile->webgl_vendor,         //'webgl_vendor'
            $profile->webgl_renderer,       //'renderer'
            $profile->fix_hairline ?? false, //'fix_hairline'
            true                            //'run_on_insecure_origins'
        );
        $instance->usePhpWebriverClient();
        return $instance;
    }

    /**
     * SeleniumStealth::makeStealth()
     *
     * @return
     */
    public function makeStealth()
    {
        $this->with_utils();
        $this->chrome_app();
        $this->chrome_runtime();
        $this->iframe_content_window();
        $this->media_codecs();
        //$this->navigator_languages();
        $this->navigator_permissions();
        $this->navigator_plugins();
        // $this->navigator_vendor();
        $this->navigator_webdriver();
        //$this->user_agent_override();
        $this->webgl_vendor_override();
        $this->window_outerdimensions();
        $this->additionalEvades();
        if ($this->fix_hairline) {
            $this->evaluateOnNewDocument(self::loadFileData($this->jsPath . "hairline.fix.js"));
        }
        return $this->driver;
    }
}

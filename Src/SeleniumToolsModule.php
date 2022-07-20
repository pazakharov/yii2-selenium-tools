<?php

namespace Zakharov\Yii2SeleniumTools;

use yii\base\Module;

class SeleniumToolsModule extends Module
{
    /**
     * screenshotPath
     *
     * @var string
     */
    public $screenshotPath = '@app/runtime/screenshots';

    /**
     * screenShotCounter
     *
     * @var int
     */
    private $screenShotCounter = 0;

    /**
     * Get the value of screenShotCounter
     */
    public function getScreenShotCounter()
    {
        return $this->screenShotCounter++;
    }
}

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
     * started_at time
     *
     * @var int
     */
    private $startedAt;

    public function init()
    {
        $this->startedAt = time();
    }

    /**
     * getScreenShotCounter
     *
     * @return int
     */
    public function getScreenShotCounter()
    {
        return $this->screenShotCounter++;
    }

    /**
     * Get started_at time
     *
     * @return  int
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }
}

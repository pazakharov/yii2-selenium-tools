<?php

namespace Zakharov\Yii2SeleniumTools;

use yii\base\Module;

class SeleniumToolsModule extends Module
{
    public const DEFAULT_CHROME_BINARY = 'chrome';

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
     * defaultChromeBinary
     *
     * @var mixed
     */
    public $defaultChromeBinary;

    /**
     * defaultWebdriverBinary
     *
     * @var mixed
     */
    public $defaultWebdriverBinary;

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

    /**
     * getDefaultChromeBinary
     *
     * @return string
     */
    public function getDefaultChromeBinary()
    {
        return $this->defaultChromeBinary ?: self::DEFAULT_CHROME_BINARY;
    }

    /**
     * getDefaultWebdriverBinary
     *
     * @return string
     */
    public function getDefaultWebdriverBinary()
    {
        return $this->defaultWebdriverBinary ?: self::DEFAULT_CHROME_BINARY;
    }
}

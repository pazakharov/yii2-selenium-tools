<?php

namespace Zakharov\Yii2SeleniumTools;

use Yii;
use yii\base\Module;
use yii\base\BootstrapInterface;
use Zakharov\Yii2SeleniumTools\Console\SeleniumToolsController;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentsDotIoParser;

class SeleniumToolsModule extends Module implements BootstrapInterface
{
    public const DEFAULT_CHROME_BINARY = 'chrome';
    public $controllerNamespace = 'Zakharov\Yii2SeleniumTools\Console';
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
     * userAgentProvider config
     *
     * @var array
     */
    public $userAgentProvider = ['class' => UserAgentsDotIoParser::class];

    /**
     * started_at time
     *
     * @var int
     */
    private $startedAt;

    public function bootstrap($app)
    {
        $app->controllerMap[$this->id] = [
            'class' => SeleniumToolsController::class,
            'module' => $this,
        ];
    }

    public function init()
    {
        $this->startedAt = time();
        Yii::$container->setSingleton(\Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService::class, \Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService::class);
        Yii::$container->setSingleton(\Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider::class, $this->userAgentProvider);
        Yii::$container->setSingleton(\Spatie\Crawler\Crawler::class, \Spatie\Crawler\Crawler::class);
        Yii::$container->setSingleton(\Spatie\Crawler\CrawlQueues\CrawlQueue::class, \Spatie\Crawler\CrawlQueues\ArrayCrawlQueue::class);
        Yii::$container->setSingleton(\Spatie\Crawler\CrawlObservers\CrawlObserver::class, \Zakharov\Yii2SeleniumTools\Utils\UserAgent\BaseCrawlObserver::class);
        Yii::$container->setSingleton(\Spatie\Crawler\CrawlProfiles\CrawlProfile::class, \Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentIoCrawlProfile::class);
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

    /**
     * getUserAgentProvider instance
     *
     * @return UserAgentProvider
     */
    public function getUserAgentProvider()
    {
        return Yii::$container->get(UserAgentProvider::class);
    }

    /**
     * getUserAgentService
     *
     * @return UserAgentService
     */
    public function getUserAgentService()
    {
        return Yii::$container->get(UserAgentService::class);
    }
}

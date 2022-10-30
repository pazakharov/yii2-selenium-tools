<?php

namespace Zakharov\Yii2SeleniumTools\StepBrowser;

use Yii;
use Ramsey\Uuid\Uuid;
use yii\helpers\Json;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use Facebook\WebDriver\WebDriver;
use yii\base\InvalidConfigException;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Zakharov\Yii2SeleniumTools\SeleniumToolsModule;
use Zakharov\Yii2SeleniumTools\StepBrowser\ProfileModel;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService;

/**
 * StepBrowser it is a simple chromium profiles manager.
 * you can create profiles and use them like whith antidetect browser.
 */
class StepBrowserComponent extends Component
{
    /**
     * @var SeleniumToolsModule
     */
    protected $module;

    /**
     * chromeOptions
     *
     * @var array
     */
    protected $chromeOptions = [
        '--no-sandbox',
        '--start-maximized',
        '--disable-gpu',
        '--mute-audio',
        '--disable-dev-shm-usage',
    ];

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->module = SeleniumToolsModule::getInstance();
        if (is_null($this->module)) {
            throw new \RuntimeException('You must bootstrap SeleniumToolsModule before use SeleniumAction');
        }
    }

    /**
     * ProfileModel factory method, for example:
     *
     * $profile = $component->createNewProfile(
     *       [
     *           'title' => 'new profile',
     *           'proxy' => 'https://example.com:3128',
     *           'user_agent' => 'Test user agent',
     *           'window_size' => '1920,1080',
     *       ]
     *   );
     *
     * @param  array $profileConfig
     * @return ProfileModel
     */
    public function createNewProfile(array $profileConfig)
    {
        $config = ArrayHelper::merge($profileConfig, [
            'class' => ProfileModel::class,
            'uuid' => Uuid::uuid4()->toString(),
        ]);

        if (!isset($config['chrome_binary']) || !is_executable($config['chrome_binary'])) {
            $config['chrome_binary'] = $this->getDefaultChromeBinary();
        }

        if (!isset($config['webdriver_binary']) || !is_executable($config['webdriver_binary'])) {
            $config['webdriver_binary'] = $this->getDefaultWebdriverBinary();
        }

        if (!isset($config['user_agent'])) {
            $config['user_agent'] = $this->buildUserAgent();
        }


        $profile = Yii::createObject($config);
        if (!$profile->validate() || !$profile->save()) {
            throw new InvalidConfigException(Json::encode($profile->errors));
        }
        return $profile;
    }

    /**
     * openProfile and return the url for webDriver
     *
     * @param  ProfileModel $uuid
     * @return WebDriver
     */
    public function openProfile(ProfileModel $profile): WebDriver
    {
        $chromeBinaryPath = is_executable($profile->chrome_binary) ? $profile->chrome_binary : env('CHROME_BINARY_PATH');
        $chromeArguments = $this->getChromeArgsumentsForProfile($profile);
        $chromeOptions = (new ChromeOptions())
            ->addArguments($chromeArguments)
            ->setBinary($chromeBinaryPath);
        $desiredCapabilities = $this->buildDesiredCapabilities($chromeOptions);
        $chromeDriverService = $this->createChromeDriverService();
        $driver = ChromeDriver::start($desiredCapabilities, $chromeDriverService);
        $driver->getCommandExecutor()->setConnectionTimeout($this->module->params['executorConnectionTimeoutMs']);
        $driver->getCommandExecutor()->setRequestTimeout($this->module->params['executorRequestTimeoutMs']);
        $driver->manage()->timeouts()->pageLoadTimeout($this->module->params['PageLoadTimeTimeoutS']);
        return $driver;
    }

    /**
     * buildDesiredCapabilities
     *
     * @param  string $profileUUID
     * @return DesiredCapabilities
     */
    protected function buildDesiredCapabilities(ChromeOptions $chromeOptions): DesiredCapabilities
    {
        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
        return $desiredCapabilities;
    }

    /**
     * createChromeDriverService
     *
     * @return ChromeDriverService
     */
    protected function createChromeDriverService(): ChromeDriverService
    {
        $pathToExecutable = env(ChromeDriverService::CHROME_DRIVER_EXECUTABLE, '');
        if ($pathToExecutable === false || $pathToExecutable === '') {
            $pathToExecutable = ChromeDriverService::DEFAULT_EXECUTABLE;
        }

        $port = rand($this->module->params['chromeDriverPortMin'], $this->module->params['chromeDriverPortMax']);
        $args = ['--port=' . $port];

        return new ChromeDriverService($pathToExecutable, $port, $args);
    }

    /**
     * getChromeOptionsForProfile
     *
     * @param  ProfileModel $profile
     * @return array
     */
    protected function getChromeArgsumentsForProfile(ProfileModel $profile)
    {
        $chromeArguments = [];
        array_push($chromeArguments, "--window-size={$profile->window_size}");
        array_push($chromeArguments, "--user-agent={$profile->user_agent}");
        array_push($chromeArguments, "--lang={$profile->language}");
        array_push($chromeArguments, "--gpu-vendor-id={$profile->webgl_vendor}");
        array_push($chromeArguments, "--renderer={$profile->webgl_vendor}");
        array_push($chromeArguments, "--accept-lang={$profile->language}");
        if (!empty($profile->proxy)) {
            array_push($chromeArguments, "--proxy-server={$profile->proxy}");
        }
        $dataDir = FileHelper::normalizePath(Yii::getAlias("{$this->module->params['profilesDirectory']}/{$profile->uuid}"));
        if (!is_dir($dataDir)) {
            FileHelper::createDirectory($dataDir);
        }
        array_push($chromeArguments, "--user-data-dir=$dataDir");
        $useHeadlessMode = $this->module->params['headless'] ?? true;
        if ($useHeadlessMode) {
            array_push($chromeArguments, "--headless");
        }
        return ArrayHelper::merge($this->chromeOptions, $chromeArguments);
    }

    /**
     * getDefaultChromeBinary
     *
     * @return string
     */
    protected function getDefaultChromeBinary()
    {
        return $this->module->getDefaultChromeBinary();
    }

    /**
     * getDefaultWebdriverBinary
     *
     * @return string
     */
    protected function getDefaultWebdriverBinary()
    {
        return $this->module->getDefaultWebdriverBinary();
    }

    /**
     * buildUserAgent
     * @param array $keys Keys for find user agent strings in db
     * @return string
     */
    protected function buildUserAgent(array $keys = [])
    {
        $service = $this->module->getUserAgentService();
        $userAgent = $service->getUserAgent($keys);
        return $userAgent->__toString();
    }

    /**
     * Get chromeOptions
     *
     * @return  array
     */
    public function getChromeOptions()
    {
        return $this->chromeOptions;
    }

    /**
     * Set chromeOptions
     *
     * @param  array  $chromeOptions  chromeOptions
     *
     * @return  self
     */
    public function setChromeOptions(array $chromeOptions)
    {
        $this->chromeOptions = $chromeOptions;

        return $this;
    }
}

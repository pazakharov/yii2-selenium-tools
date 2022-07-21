<?php

namespace Zakharov\Yii2SeleniumTools\SeleniumActions;

use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use yii\base\InvalidConfigException;
use Zakharov\Yii2SeleniumTools\SeleniumToolsModule;
use Zakharov\Yii2SeleniumTools\Events\SeleniumActionEvent;
use Zakharov\Yii2SeleniumTools\Contracts\SeleniumActionInterface;

abstract class BaseSeleniumAction extends Component implements SeleniumActionInterface
{
    public const WAIT_CONDITION_ELEMENT_SECONDS = 120;
    public const WAIT_CONDITION_PERIOD_MILLISECONDS = 500;

    public const EVENT_BEFORE_ACTION = 'beforeAction';
    public const EVENT_AFTER_ACTION = 'afterAction';

    public const BASE_ACTION_TITLE = 'baseAction';
    public const DEFAULT_LOG_CATEGORY = 'selenium';

    /**
     * @var SeleniumToolsModule
     */
    protected $module;
    /**
     * @var null|RemoteWebDriver
     */
    protected ?WebDriver $driver;
    protected $title = self::BASE_ACTION_TITLE;
    protected ?WebDriverWait $waiter = null;

    final public function run(): bool
    {
        $this->beforeAction();
        $actionResult = $this->action();
        $this->afterAction();
        return $actionResult;
    }

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
     * Runs before main action
     *
     * @return void
     */
    final protected function beforeAction()
    {
        if (!$this->driver instanceof WebDriver) {
            throw new InvalidConfigException('webDriver must be an instance of WebDriver');
        }
        if (!$this->waiter instanceof WebDriverWait) {
            $this->waiter = $this->buildWaiter($this->driver);
        }
        if (env('SCREENSHOTS', false)) {
            $this->takeScreenshot();
        }
        if ($this->title !== self::BASE_ACTION_TITLE) {
            $this->info("{$this->title}");
        }
        $this->trigger(self::EVENT_BEFORE_ACTION, Yii::createObject(
            [
                'class' => SeleniumActionEvent::class,
                'payload' => null
            ]
        ));
    }

    /**
     * takeScreenshot
     *
     * @return string
     */
    public function takeScreenshot()
    {
        $screenshotPath = $this->module->screenshotPath;
        $count =  $this->module->screenShotCounter;
        $session =  $this->driver->getSessionID();
        $name =  "{$count}.png";
        $path = FileHelper::normalizePath(Yii::getAlias("$screenshotPath/$session"));
        FileHelper::createDirectory($path);
        $fileName = $path . DIRECTORY_SEPARATOR . $name;
        $this->driver->takeScreenshot($fileName);
        return $fileName;
    }

    /**
     * info в лог и stdout
     *
     * @param  mixed $text
     * @return self
     */
    protected function info(string $text = '')
    {
        $secondsFromBegin = time() - $this->module->startedAt;
        $message = "$text ($secondsFromBegin sec.)";
        \Yii::info($message, $this->module->params['logCategory'] ?? self::DEFAULT_LOG_CATEGORY);
        return $message;
    }

    /**
     * afterAction
     *
     * @return void
     */
    final protected function afterAction()
    {
        $this->trigger(self::EVENT_AFTER_ACTION, Yii::createObject(
            [
                'class' => SeleniumActionEvent::class,
                'payload' => null
            ]
        ));
    }

    abstract protected function action(): bool;

    /**
     * Запуск дочернего экшена
     *
     * @param  SeleniumActionInterface $action
     * @return bool
     */
    public function runAction(SeleniumActionInterface $action): bool
    {
        $action->setDriver($this->driver);
        $action->setWaiter($this->waiter);
        return $action->run();
    }

    public function setDriver(WebDriver $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    public function getDriver(): ?WebDriver
    {
        return $this->driver;
    }

    /**
     * Фабричный метод для вейтера
     *
     * @param  mixed $webDriver
     * @return WebDriverWait
     */
    protected function buildWaiter(WebDriver $driver): WebDriverWait
    {
        return new WebDriverWait(
            $driver,
            self::WAIT_CONDITION_ELEMENT_SECONDS,
            self::WAIT_CONDITION_PERIOD_MILLISECONDS,
        );
    }

    /**
     * Get the value of waiter
     */
    public function getWaiter(): ?WebDriverWait
    {
        return $this->waiter;
    }

    /**
     * Set the value of waiter
     *
     * @return  self
     */
    public function setWaiter(?WebDriverWait $waiter): self
    {
        $this->waiter = $waiter;
        return $this;
    }
}

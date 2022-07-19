<?php

namespace Zakharov\Yii2SeleniumTools\SeleniumActions;

use Yii;
use yii\base\BaseObject;
use yii\helpers\FileHelper;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Zakharov\Yii2SeleniumTools\Contracts\SeleniumActionInterface;

abstract class BaseSeleniumAction extends BaseObject implements SeleniumActionInterface
{
    public const WAIT_CONDITION_ELEMENT_SECONDS = 120;
    public const WAIT_CONDITION_PERIOD_MILLISECONDS = 500;

    public const EVENT_BEFORE_ACTION = 'beforeAction';
    public const EVENT_AFTER_ACTION = 'afterAction';

    public const BASE_ACTION_TITLE = 'baseAction';
    /**
     * @var null|RemoteWebDriver
     */
    protected ?WebDriver $driver;
    protected $title = self::BASE_ACTION_TITLE;
    protected ?WebDriverWait $waiter = null;

    final public function run(): bool
    {
        $this->beforeAction();
        $this->action();
        $this->afterAction();
        return true;
    }

    protected function beforeAction()
    {
        if (!$this->waiter && $this->driver) {
            $this->waiter = $this->buildWaiter($this->driver);
        }
        if (Yii::$app->params['SCREENSHOTS']) {
            $this->takeScreenshot();
        }
        if ($this->title !== self::BASE_ACTION_TITLE) {
            $this->info("{$this->title}");
        }
    }

    public function takeScreenshot()
    {
        $count =  Yii::$app->currentSite->counter++;
        $session =  Yii::$app->currentSite->chromeSession;
        $name =  $count . '.png';
        $path = Yii::getAlias("@app/runtime/screenshots/$session");
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
    public function info(string $text = '')
    {
        $secondsFromBegin = time() - \Yii::$app->currentSite->started_at;
        $message = "$text ($secondsFromBegin sec.)";
        \Yii::info($message, 'selenium');
        echo $message . PHP_EOL;
        return $this;
    }

    protected function afterAction()
    {
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

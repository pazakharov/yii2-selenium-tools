<?php

namespace Zakharov\Yii2SeleniumTools\SeleniumActions;

use Yii;
use InvalidArgumentException;
use Zakharov\Yii2SeleniumTools\Contracts\SeleniumActionInterface;
use Zakharov\Yii2SeleniumTools\SeleniumActions\BaseSeleniumAction;

class CompositeSeleniumAction extends BaseSeleniumAction
{
    protected $actions = [];
    protected $middleWares = [];

    protected function action(): bool
    {
        if (empty($this->actions)) {
            return true;
        }
        foreach ($this->actions as $action) {
            try {
                $this->runMiddlewares();
            } catch (\Throwable $th) {
                $this->info('Error in middlewares ' . $th->getMessage());
            }
            /** @var SeleniumActionInterface $action*/
            if ($action instanceof SeleniumActionInterface) {
                $this->runAction($action);
            } elseif (is_callable($action)) {
                call_user_func($action, $this->driver, $this->waiter);
            }
        }
        return true;
    }

    public function runMiddlewares()
    {
        $middleWareAction = Yii::createObject([
            'class' => self::class,
            'actions' => $this->middleWares,
            'driver' => $this->driver,
            'waiter' => $this->waiter,
        ]);
        $middleWareAction->run();
    }
    /**
     * @param mixed $action
     * @return self
     */
    public function addAction($action): self
    {
        if (!($action instanceof SeleniumActionInterface || is_callable($action))) {
            throw new InvalidArgumentException('Action must be a callable or implement SeleniumActionInterface');
        }
        array_push($this->actions, $action);
        return $this;
    }

    /**
     * addMiddleware
     *
     * @param  mixed $middleware
     * @return self
     */
    public function addMiddleware($middleware): self
    {
        if (!($middleware instanceof SeleniumActionInterface || is_callable($middleware))) {
            throw new InvalidArgumentException('Middleware must be a callable or implement SeleniumActionInterface');
        }
        array_push($this->middleWares, $middleware);
        return $this;
    }

    /**
     * @return self
     */
    public function flushActions(): self
    {
        $this->actions = [];
        return $this;
    }

    /**
     * @param array $actions
     * @return  self
     */
    public function setActions(array $actions)
    {
        $this->actions = $actions;
        return $this;
    }
}

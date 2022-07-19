<?php

namespace Zakharov\Yii2SeleniumTools\Contracts;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverWait;

interface SeleniumActionInterface
{
    public function run(): bool;
    public function setDriver(WebDriver $driver): SeleniumActionInterface;
    public function getDriver(): ?WebDriver;
    public function setWaiter(?WebDriverWait $waiter): SeleniumActionInterface;
    public function getWaiter(): ?WebDriverWait;
}

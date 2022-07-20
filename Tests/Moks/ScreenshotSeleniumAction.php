<?php

namespace Zakharov\Yii2SeleniumTools\Tests\Moks;

use Zakharov\Yii2SeleniumTools\SeleniumActions\BaseSeleniumAction;

class ScreenshotSeleniumAction extends BaseSeleniumAction
{
    /**
     * file
     *
     * @var null|string
     */
    public $file = null;

    protected function action(): bool
    {
        $this->driver->get('file://' . codecept_data_dir('Pages/1.html'));
        $this->file = $this->takeScreenshot();
        return (bool)$this->file;
    }
}

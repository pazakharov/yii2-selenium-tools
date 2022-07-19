<?php

namespace Zakharov\Yii2SeleniumTools\Tests\Moks;

use Zakharov\Yii2SeleniumTools\SeleniumActions\BaseSeleniumAction;

class SeleniumAction extends BaseSeleniumAction
{
    protected function action(): bool
    {
        $this->driver->get('file://' . codecept_data_dir('Pages/1.html'));
        if ('Test Page' === $this->driver->getTitle()) {
            return true;
        };
        return false;
    }
}

<?php

namespace Zakharov\Yii2SeleniumTools\Tests\Moks;

use Zakharov\Yii2SeleniumTools\SeleniumActions\BaseSeleniumAction;

class InfoSeleniumAction extends BaseSeleniumAction
{
    protected function action(): bool
    {
        return (bool)$this->info('Some test info');
    }
}

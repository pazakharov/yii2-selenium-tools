<?php

namespace Zakharov\Yii2SeleniumTools\Events;

use yii\base\Event;
use Zakharov\Yii2SeleniumTools\Contracts\PayloadedEventInterface;

class SeleniumActionEvent extends Event implements PayloadedEventInterface
{
    public $payload;

    /**
     * Get the value of payload
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}

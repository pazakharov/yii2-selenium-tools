<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

interface UserAgentProvider
{
    /**
     * Must return array if user agents strings
     *
     * @return string[]
     */
    public function getArrayOfUserAgents(): array;
}

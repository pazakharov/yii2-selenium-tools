<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use UnitTester;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentsDotIoParser;

class UserAgentsDotIoParserCest
{
    /**
     * provider
     *
     * @var UserAgentsDotIoParser
     */
    protected $provider;

    public function _before(UnitTester $I)
    {
        $this->provider = Yii::$container->get(UserAgentProvider::class);
    }

    // tests
    public function testParsing(UnitTester $I)
    {
        $testArray = $this->provider->getArrayOfUserAgents();
        $I->assertIsArray($testArray);
    }
}

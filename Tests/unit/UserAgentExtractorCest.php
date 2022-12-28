<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use UnitTester;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentExtractor;

class UserAgentExtractorCest
{
    // tests
    public function testExtractUserAgentsFromHtml(UnitTester $I)
    {
        $extractor = Yii::createObject(UserAgentExtractor::class);
        $html = file_get_contents(codecept_data_dir('useragent.io.example.html'));
        $userAgents = $extractor->extract($html);
        $I->assertIsArray($userAgents);
        $I->assertCount(50, $userAgents);
    }
}

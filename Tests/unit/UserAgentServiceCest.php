<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Codeception\Example;
use Yii;
use UnitTester;
use Zakharov\Yii2SeleniumTools\models\UserAgent;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;

class UserAgentServiceCest
{
    // tests
    public function testModuleCanCreateInstanceOfUserAgentsProvider(UnitTester $I)
    {
        $module = Yii::$app->getModule('seleniumTools');
        $userAgentProvider = $module->getUserAgentProvider();
        $I->assertInstanceOf(UserAgentProvider::class, $userAgentProvider);
    }

    public function testServiceCanFillBaseWithUserAgents(UnitTester $I)
    {
        $arrayOfTetsUserAgents = ['userAgent1', 'userAgent2', 'userAgent3'];
        $dummyUserAgentProvider = \Codeception\Util\Stub::makeEmpty(UserAgentProvider::class, ['getArrayOfUserAgents' => fn () => $arrayOfTetsUserAgents]);
        Yii::$container->set(UserAgentProvider::class, $dummyUserAgentProvider);
        /** @var UserAgentService $service */
        $service = Yii::createObject(UserAgentService::class);
        $result = $service->fillBaseWithUserAgents();
        $I->assertEquals($result, count($arrayOfTetsUserAgents));
        foreach ($arrayOfTetsUserAgents as $userAgent) {
            $I->seeRecord(UserAgent::class, ['ua' => $userAgent]);
        }
    }

    /**
     * testServiceCanProvideUserAgent
     * @dataProvider getUserAgentsCases
     * @param  UnitTester $I
     * @param  Example $case
     * @return void
     */
    public function testServiceCanProvideUserAgent(UnitTester $I, Example $case)
    {
        $I->haveFixtures([
            'userAgents' => [
                'class' => \tests\Fixtures\UserAgentFixture::class,
                'dataFile' => codecept_data_dir('user_agents.php'),
            ]
        ]);
        $service = Yii::createObject(UserAgentService::class);
        $userAgent = $service->getUserAgent($case['keys']);
        $I->assertNotNull($userAgent);
        foreach ($case['keys'] as $key) {
            $I->assertStringContainsStringIgnoringCase($key, $userAgent);
        }
        $userAgent2 = $service->getUserAgent($case['keys']);
        $I->assertNotEquals($userAgent, $userAgent2);
    }

    public function getUserAgentsCases()
    {
        return [
            ['keys' => ['chrome', 'Windows']],
        ];
    }
}

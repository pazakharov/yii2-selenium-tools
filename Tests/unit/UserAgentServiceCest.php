<?php

namespace Zakharov\Yii2SeleniumTools\Tests\unit;

use Yii;
use UnitTester;
use Zakharov\Yii2SeleniumTools\models\UserAgent;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;

class UserAgentServicesCest
{
    // tests
    public function testModuleCanCreateInstanceOfUserAgentsProvider(UnitTester $I)
    {
        $module = Yii::$app->getModule('seleniumTools');
        $userAgentProvider = $module->getUserAgentProvider();
        $I->assertInstanceOf(UserAgentProvider::class, $userAgentProvider);
    }

    public function testUserAgentServiceCanFillBaseWithUserAgents(UnitTester $I)
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
}

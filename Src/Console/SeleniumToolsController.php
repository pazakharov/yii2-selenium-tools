<?php

namespace Zakharov\Yii2SeleniumTools\Console;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentService;

/**
 * Selenium Tools and features
 */
class SeleniumToolsController extends Controller
{

    public function actionIndex()
    {
        echo "Selenium Tools and features";
        return (ExitCode::OK);
    }
    /**
     * Fill the base with data for the StepBrowser profiles e.g: UserAgents
     * You ca use params in your request for filter user agents:
     * `php yii seleniumTools/fill-base chrome win`
     * `php yii seleniumTools/fill-base chrome 102.0`
     * @return void
     */
    public function actionFillBase(...$filterKeys)
    {
        $userAgentService = Yii::$container->get(UserAgentService::class);
        $userAgentService->fillBaseWithUserAgents($filterKeys);
    }
}

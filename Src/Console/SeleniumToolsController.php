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
     *
     * @return void
     */
    public function actionFillBase()
    {
        $userAgentService = Yii::$container->get(UserAgentService::class);
        $userAgentService->fillBaseWithUserAgents();
    }
}

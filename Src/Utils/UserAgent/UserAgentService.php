<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use Yii;
use yii\helpers\ArrayHelper;
use Zakharov\Yii2SeleniumTools\models\UserAgent;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;

class UserAgentService
{

    /**
     * userAgentProvider
     *
     * @var UserAgentProvider
     */
    protected $userAgentProvider;

    public function __construct(UserAgentProvider $userAgentProvider)
    {
        $this->userAgentProvider = $userAgentProvider;
    }
    /**
     * fill base with user agents by specified in container userAgentProvider
     *
     * @return int number of inserts
     */
    public function fillBaseWithUserAgents()
    {
        $arrayOfUserAgents = array_values($this->userAgentProvider->getArrayOfUserAgents());
        if (empty($arrayOfUserAgents)) {
            return 0;
        }
        $createdAt = (new \DateTime())->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $updateArray = array_map(fn ($item) => [$item, $createdAt], $arrayOfUserAgents);
        return Yii::$app->db
            ->createCommand()
            ->batchInsert(UserAgent::tableName(), ['ua', 'created_at'], $updateArray)
            ->execute();
    }
}

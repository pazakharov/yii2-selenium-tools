<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use PDO;
use Yii;
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
     * @param string[] $filterKey
     * @return int number of inserts
     */
    public function fillBaseWithUserAgents($filterKeys = [])
    {
        $arrayOfUserAgents = array_values($this->userAgentProvider->getArrayOfUserAgents());
        if (empty($arrayOfUserAgents)) {
            return 0;
        }
        $arrayOfUserAgents = $this->filterArrayOfUserAgentsWithFilter($arrayOfUserAgents, $filterKeys);
        $createdAt = (new \DateTime())->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $updateArray = array_map(fn ($item) => [$item, $createdAt], $arrayOfUserAgents);

        return Yii::$app->db
            ->createCommand()
            ->batchInsert(UserAgent::tableName(), ['ua', 'created_at'], $updateArray)
            ->execute();
    }

    /**
     * filterArrayOfUserAgentsWithFilter
     *
     * @param  array $arrayOfUserAgents
     * @param  array $filterKeys
     * @return void
     */
    protected function filterArrayOfUserAgentsWithFilter(array $arrayOfUserAgents, array $filterKeys = [])
    {
        if (!empty($filterKeys)) {
            foreach ($filterKeys as $key) {
                $arrayOfUserAgents = array_filter($arrayOfUserAgents, fn ($item) => stripos($item, $key) !== false);
            }
        }
        return $arrayOfUserAgents;
    }
}

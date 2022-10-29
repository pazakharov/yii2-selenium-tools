<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use GuzzleHttp\Psr7\Uri;
use Spatie\Crawler\Crawler;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlQueues\CrawlQueue;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use yii\helpers\ArrayHelper;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;

class UserAgentsDotIoParser implements UserAgentProvider
{
    public const MAX_DEPTH_DOWNLOADING = 2;
    public const CLIENT_REQUESTS_CONCURENCY = 1;
    public const DELAY_BETWEEN_REQUESTS_MS = 500;
    public const START_URL = 'https://useragents.io/explore/browsers/types/browser/maker/google-inc-f3d';

    /**
     * url
     *
     * @var UriInterface
     */
    protected $url;
    /**
     * crawler
     *
     * @var Crawler
     */
    protected $crawler;
    /**
     * queue
     *
     * @var CrawlQueue
     */
    protected $queue;
    /**
     * observer
     *
     * @var CrawlObserver
     */
    protected $observer;
    /**
     * UserAgentIoCrawlProfile
     *
     * @var UserAgentIoCrawlProfile  $profile
     */
    protected $profile;

    protected $parsedUserAgents = [];

    public function __construct(Crawler $crawler, CrawlQueue $queue, CrawlObserver $observer, UserAgentIoCrawlProfile $profile)
    {
        $this->url = new Uri(self::START_URL);
        $this->crawler = $crawler;
        $this->queue = $queue;
        $this->observer = $observer;
        $this->profile = $profile;
        $this->profile->setBaseUrl($this->url);
        $this->prepareCrawler();
    }

    /**
     * getCrowler
     *
     * @return void
     */
    protected function prepareCrawler()
    {
        $this->crawler
            ->setCrawlObserver($this->observer)
            ->setCrawlProfile($this->profile)
            ->ignoreRobots()
            ->acceptNofollowLinks()
            ->setMaximumDepth(self::MAX_DEPTH_DOWNLOADING)
            ->setConcurrency(self::CLIENT_REQUESTS_CONCURENCY)
            ->setCrawlQueue($this->queue)
            ->setDelayBetweenRequests(self::DELAY_BETWEEN_REQUESTS_MS);
    }

    /**
     * @inheritDoc
     */
    public function getArrayOfUserAgents(): array
    {
        $this->crawler->startCrawling($this->url);

        return $this->parsedUserAgents;
    }

    /**
     * Add userAgent to parsedUserAgents
     *
     * @return  self
     */
    public function addUserAgent($userAgent)
    {
        if (is_array($userAgent)) {
            $this->parsedUserAgents = ArrayHelper::merge($this->parsedUserAgents, $userAgent);
            return $this;
        }

        $this->parsedUserAgents[] = $userAgent;
        return $this;
    }
}

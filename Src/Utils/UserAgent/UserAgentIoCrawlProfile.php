<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class UserAgentIoCrawlProfile extends CrawlProfile
{
    protected $baseUrl;

    public function shouldCrawl(UriInterface $url): bool
    {
        return ($this->baseUrl->getHost() === $url->getHost() &&
            strpos($url, 'google-inc-f3d') !== false &&
            strpos($url, 'uas') === false
        );
    }

    /**
     * Get the value of baseUrl
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set the value of baseUrl
     *
     * @return  self
     */
    public function setBaseUrl(UriInterface $baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }
}

<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use Yii;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Zakharov\Yii2SeleniumTools\Utils\UserAgent\UserAgentProvider;

class BaseCrawlObserver extends CrawlObserver
{
    /**
     * crawled
     *
     * @param  UriInterface $url
     * @param  ResponseInterface $response
     * @param  null|UriInterface $foundOnUrl
     * @return void
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $userAgentsExtractor = Yii::createObject(['class' => UserAgentExtractor::class]);
        $html = (string)$response->getBody();
        $userAgents = $userAgentsExtractor->extract($html);
        /** @var UserAgentsDotIoParser $provider*/
        $provider = Yii::$container->get(UserAgentProvider::class);
        $provider->addUserAgent($userAgents);
    }

    /**
     * crawlFailed
     *
     * @param  UriInterface $url
     * @param  RequestException $requestException
     * @param  UriInterface|null $foundOnUrl
     * @return void
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        $message = $requestException->getMessage();
        $traceItems = $requestException->getTrace();
        $last = current($traceItems);
        if (isset($last['class']) && $last['class'] === \Spatie\Crawler\Handlers\CrawlRequestFailed::class) {
            $guzzleException = current($last['args']);
            $message = 'ConnectException' . $guzzleException->getMessage();
        }
        Yii::error("$message | $url");
        return;
    }

    /**
     * finishedCrawling
     *
     * @return void
     */
    public function finishedCrawling(): void
    {
        return;
    }

    /**
     * willCrawl
     *
     * @param  mixed $url
     * @return void
     */
    public function willCrawl(UriInterface $url): void
    {
        return;
    }
}

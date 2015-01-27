<?php namespace WP\Crawler;

use WP\Crawler\Domain;
use WP\Crawler\Extractor\Extractor;
use WP\Crawler\Event\CrawlerEvents;
use WP\Crawler\Event\FilterDomainEvent;
use WP\Crawler\Event\FilterLinkEvent;
use WP\Crawler\Event\FilterPageResponseEvent;
use WP\Crawler\Event\FoundLinksEvent;
use WP\Crawler\Event\FilterCrawlerProcessEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class DomainCrawler {
    private $client;
    private $dispatcher;
    private $queue;
    private $finder;
    private $_domain;
    protected $wait_time;

    public function __construct(
        Queue\QueueManagerInterface $queue,
        LinkFinderInterface $finder
    )
    {
        $this->client = new \GuzzleHttp\Client();
        $this->dispatcher = new EventDispatcher();
        $this->wait_time = 10;
        $this->queue = $queue;
        $this->finder = $finder;
    }

    /*
     * Sets the secounds that the crawler
     * waits between each request
     *
     * @param int $sec
     * @return $this;
     */
    public function setWaitTime($sec)
    {
        $this->wait_time = $sec;
        return $this;
    }

    /*
     * Sets Client
     *
     * @param \GuzzleHttp\Client $client
     * @return $this;
     */
    public function setClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client; 
        return $this;
    }

    /*
     * Gets Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client; 
    }

    /**
     * Sets EventDispatcher
     *
     * @param Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @return $this;
     */
    public function setEventDispatcher(Symfony\Component\EventDispatcher\EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher; 
        return $this;
    }

    /**
     * Returns EventDispatcher.
     *
     * @return Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher; 
    }

    /**
     * Crawls the given domain.
     *
     * @param  string $domain
     * @param  string $startPoint
     * @return void
     */
    public function crawl($domain, $startPoint='/')
    {
        $process = new CrawlerProcess();
        $process->start();

        $domain = new Domain($domain);
        $this->dispatcher->dispatch(CrawlerEvents::onStart, new FilterCrawlerProcessEvent($process));
        $this->_domain = $domain;

        // create seed 
        $link = new Link($startPoint);
        $link->setOriginDomain($this->_domain->getDomain());
        $this->pushLinkToQueue($link);

        // Start queue
        while ($process->isRunning() && ($link = $this->queue->pop()) !== false) {
            $this->dispatcher->dispatch(CrawlerEvents::onPopLinkFromQueue, new FilterLinkEvent($link, $process));

            // download web page
            $response = $this->downloadPage($link, $process);

            // extract links from response and add them to queue
            $this->findLinksAndAddToQueue($response, $link, $process);

            // fill the link with the data we get from the response
            $this->extractDataFromResponse($link, $response);

            $this->dispatcher->dispatch(CrawlerEvents::onLinkProcessed, new FilterLinkEvent($link, $process));

            // so we don't dos the server
            sleep($this->wait_time);
        }

        $process->done();
        $this->dispatcher->dispatch(CrawlerEvents::onFinish, new FilterCrawlerProcessEvent($process));
    }

    private function extractDataFromResponse(Link $link, $response)
    {
        $html = $response->getBody()->__toString();

        $link->setPageTitle($this->finder->getTitle($html));
        $link->setStatusCode($response->getStatusCode());
    }

    private function pushLinkToQueue(Link $link)
    {
        $this->dispatcher->dispatch(CrawlerEvents::onPushLinkToQueue, new FilterLinkEvent($link));
        $this->queue->push($link);
    }

    private function downloadPage(Link $link, &$process)
    {
        $_url = $link->getFullUrl();
        $response = $this->client->get($_url, ['exceptions' => false]);

        $this->dispatcher->dispatch(CrawlerEvents::onPageDownload, 
            new FilterPageResponseEvent($link, $response, $process));

        return $response;
    }

    private function findLinksAndAddToQueue($response, Link $origin, &$process)
    {
        $html = $response->getBody()->__toString();
        $links = $this->finder->getLinks($html);

        foreach ($links as &$link)
        {
            $link->setOriginDomain($this->_domain->getDomain())
                 ->setOrigin($origin);
            $this->pushLinkToQueue($link);
        }

        $this->dispatcher->dispatch(CrawlerEvents::onFoundLinks, new FoundLinksEvent($links, $process));
    }
}

class BrokenLinkException extends \Exception {}

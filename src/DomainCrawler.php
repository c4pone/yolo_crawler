<?php namespace WP\Crawler;

use WP\Crawler\Domain;
use WP\Crawler\Event\CrawlerEvents;
use WP\Crawler\Event\FilterLinkEvent;
use WP\Crawler\Event\FilterPageResponseEvent;
use WP\Crawler\Event\FoundLinksEvent;
use WP\Crawler\Event\FilterCrawlerProcessEvent;
use WP\Crawler\Downloader\Downloader;
use WP\Crawler\Downloader\PageDownloader;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DomainCrawler {
    private $dispatcher;
    private $queue;
    private $finder;
    private $downloader;
    private $linkFactory;

    /*
     * Keeps the domain that we crawl
     *
     * @var string 
     */
    private $_domain;

    /*
     * Time to wait between each request
     *
     * @var int 
     */
    private $wait_time = 10;

    /*
     * Maximum attempts to download a page
     *
     * @var int 
     */
    private $download_tries = 3;

    public function __construct(
        Queue\QueueManagerInterface $queue,
        LinkFinderInterface $finder
    )
    {
        $this->downloader = new PageDownloader();
        $this->dispatcher = new EventDispatcher();
        $this->linkFactory = new LinkFactory();
        $this->queue = $queue;
        $this->finder = $finder;
    }

    /*
     * Set maximum attempts to download a page
     *
     * @param int $tries 
     * @return $this;
     */
    public function setDownloadTries($tries)
    {
         $this->download_tries = $tries;
         return $this;
    }

    /*
     * Set the time to wait between each request
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
     * Sets Downloader
     *
     * @param WP\Crawler\Downloader\Downloader $downloader
     * @return $this;
     */
    public function setDownloader(Downloader $downloader)
    {
        $this->downloader = $downloader; 
        return $this;
    }

    /*
     * Gets Downlaoder
     *
     * @return WP\Crawler\Downloader\Downloader 
     */
    public function getDownloader()
    {
        return $this->downloader; 
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

        $domain = new Domain($domain, $startPoint);
        $this->_domain = $domain;

        $this->dispatcher->dispatch(CrawlerEvents::onStart, new FilterCrawlerProcessEvent($process));

        // create seed 
        $link = new Link($domain->getUrl());
        $link->setOriginDomain($domain);

        $this->pushLinkToQueue($link);

        // Start queue
        while ($process->isRunning() && ($link = $this->queue->pop()) !== false) {
            $this->dispatcher->dispatch(CrawlerEvents::onPopLinkFromQueue, new FilterLinkEvent($link, $process));

            // download web page
            $response = $this->downloadPage($link, $process);
            $link->setResponse($response);

            if ($response !== null) {
                // fill the link with the data we get from the response
                $link->setStatusCode($response->getStatusCode());
                $link->setPageTitle($this->finder->getTitle($link->getHtml()));

                // extract links from response and add them to queue
                $this->findLinksAndAddToQueue($link, $process);
            }

            $this->dispatcher->dispatch(CrawlerEvents::onLinkProcessed, new FilterLinkEvent($link, $process));

            // so we don't dos the server
            sleep($this->wait_time);
        }

        $process->done();
        $this->dispatcher->dispatch(CrawlerEvents::onFinish, new FilterCrawlerProcessEvent($process));
    }

    private function pushLinkToQueue(Link $link)
    {
        $this->dispatcher->dispatch(CrawlerEvents::onPushLinkToQueue, new FilterLinkEvent($link));
        $this->queue->push($link);
    }

    private function downloadPage(Link $link, &$process)
    {
        $response = null;

        try {
            $response = $this->downloader->download($link->getLinkHref(), $this->download_tries);
        } catch (DownloadException $e) { 
            $link->setStatusCode(69);
        }

        $this->dispatcher->dispatch(CrawlerEvents::onPageDownload, 
            new FilterPageResponseEvent($link, $response, $process));

        return $response;
    }

    private function findLinksAndAddToQueue(Link $origin, &$process)
    {
        $html = $origin->getHtml();
        $crawled_links = $this->finder->getLinks($html);
        $links = array();

        foreach ($crawled_links as $link_data)
        {
            $link = $this->linkFactory->getLink($link_data, $this->_domain, $origin);
            $links[] = $link;
            $this->pushLinkToQueue($link);
        }

        $this->dispatcher->dispatch(CrawlerEvents::onFoundLinks, new FoundLinksEvent($links, $process));
    }
}

class BrokenLinkException extends \Exception {}

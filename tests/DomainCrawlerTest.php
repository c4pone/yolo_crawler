<?php

use WP\Crawler\Queue\QueueManager;
use WP\Crawler\Queue\ArrayQueue;
use WP\Crawler\LinkFinder;
use WP\Crawler\DomainCrawler;
use WP\Crawler\Queue\Validator\Validator;
use WP\Crawler\Queue\Store\ArrayStore;
use WP\Crawler\Link;
use WP\Crawler\Extractor\Result\ArrayResultKeeper;

use WP\Crawler\Downloader\DownloadException;
use WP\Crawler\Downloader\Downloader;
use WP\Crawler\Downloader\PageDownloader;

use WP\Crawler\Event\CrawlerEvents;
use WP\Crawler\Event\FilterCrawlerProcessEvent;
use WP\Crawler\Event\FoundLinksEvent;
use Symfony\Component\EventDispatcher\Event;

class DomainCrawlerTest extends PHPUnit_Framework_TestCase {

    public function testCrawl()
    {
        $test = $this;
        $events = array(
            CrawlerEvents::onStart => 0,
            CrawlerEvents::onFinish => 0,
            CrawlerEvents::onPushLinkToQueue => 0,
            CrawlerEvents::onPopLinkFromQueue => 0,
            CrawlerEvents::onPageDownload => 0,
            CrawlerEvents::onFoundLinks => 0,
            CrawlerEvents::onLinkProcessed => 0,
        );

        $html = <<<'HTML'
        <!DOCTYPE html>
        <html>
            <body>
                <a href="http://www.google.com">test</a>
                <a href="https://www.google.com">test</a>
                <a href="/link1">test</a>
                <a href="another1">test</a>
                <a href="one">test</a>
            </body>
        </html>
HTML;

        $body = new Body($html);

        $crawler = $this->createCrawler($body);
        $crawler->setWaitTime(0);

        // Setup dispatcher
        $dispatcher = $crawler->getEventDispatcher();
        $dispatcher->addListener(CrawlerEvents::onStart, function (FilterCrawlerProcessEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onStart]++;
            $test->assertTrue($event->getProcess()->isRunning());
        });
        $dispatcher->addListener(CrawlerEvents::onFinish, function (FilterCrawlerProcessEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onFinish]++;
            $test->assertFalse($event->getProcess()->isRunning());
        });
        $dispatcher->addListener(CrawlerEvents::onPushLinkToQueue, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPushLinkToQueue]++;
        });
        $dispatcher->addListener(CrawlerEvents::onPopLinkFromQueue, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPopLinkFromQueue]++;
        });
        $dispatcher->addListener(CrawlerEvents::onPageDownload, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPageDownload]++;
        });
        $dispatcher->addListener(CrawlerEvents::onLinkProcessed, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onLinkProcessed]++;
        });
        $dispatcher->addListener(CrawlerEvents::onFoundLinks, function (FoundLinksEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onFoundLinks]++;

            $links = $event->getLinks();

            $test->assertEquals(5, count($links));
            $test->assertEquals('http://www.google.com', $links[0]->getLinkHref());
            $test->assertEquals('https://www.google.com', $links[1]->getLinkHref());
            $test->assertEquals('http://dmoz.com/link1', $links[2]->getLinkHref());
            $test->assertEquals('http://dmoz.com/another1', $links[3]->getLinkHref());
            $test->assertEquals('http://dmoz.com/one', $links[4]->getLinkHref());
        });

        $links = $crawler->crawl('dmoz.com');

        $this->assertEquals(1, $events[CrawlerEvents::onStart]);
        $this->assertEquals(1, $events[CrawlerEvents::onFinish]);
        $this->assertEquals(6, $events[CrawlerEvents::onPushLinkToQueue]);
        $this->assertEquals(1, $events[CrawlerEvents::onPopLinkFromQueue]);
        $this->assertEquals(1, $events[CrawlerEvents::onLinkProcessed]);
        $this->assertEquals(1, $events[CrawlerEvents::onPageDownload]);
        $this->assertEquals(1, $events[CrawlerEvents::onFoundLinks]);
    }

    public function testStopCrawlOverEvent()
    {
        $test = $this;
        $events = array(
            CrawlerEvents::onStart => 0,
            CrawlerEvents::onFinish => 0,
            CrawlerEvents::onPushLinkToQueue => 0,
            CrawlerEvents::onPopLinkFromQueue => 0,
            CrawlerEvents::onPageDownload => 0,
            CrawlerEvents::onFoundLinks => 0,
        );

        $html = <<<'HTML'
        <!DOCTYPE html>
        <html>
            <body>
                <a href="http://www.google.com">test</a>
                <a href="https://www.google.com">test</a>
                <a href="/link1">test</a>
                <a href="another1">test</a>
                <a href="one">test</a>
            </body>
        </html>
HTML;

        $body = new Body($html);

        $crawler = $this->createCrawler($body);
        $crawler->setWaitTime(0);

        // Setup dispatcher
        $dispatcher = $crawler->getEventDispatcher();
        $dispatcher->addListener(CrawlerEvents::onStart, function (FilterCrawlerProcessEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onStart]++;
            $process = $event->getProcess()->stop();
            $event->setProcess($process);
        });
        $dispatcher->addListener(CrawlerEvents::onFinish, function (FilterCrawlerProcessEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onFinish]++;
        });
        $dispatcher->addListener(CrawlerEvents::onPushLinkToQueue, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPushLinkToQueue]++;
        });
        $dispatcher->addListener(CrawlerEvents::onPopLinkFromQueue, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPopLinkFromQueue]++;
        });
        $dispatcher->addListener(CrawlerEvents::onPageDownload, function (Event $event) use ($test, &$events){
            $events[CrawlerEvents::onPageDownload]++;
        });
        $dispatcher->addListener(CrawlerEvents::onFoundLinks, function (FoundLinksEvent $event) use ($test, &$events){
            $events[CrawlerEvents::onFoundLinks]++;
        });

        $links = $crawler->crawl('dmoz.com');

        $this->assertEquals(1, $events[CrawlerEvents::onStart]);
        $this->assertEquals(1, $events[CrawlerEvents::onFinish]);
        $this->assertEquals(1, $events[CrawlerEvents::onPushLinkToQueue]);
        $this->assertEquals(0, $events[CrawlerEvents::onPopLinkFromQueue]);
        $this->assertEquals(0, $events[CrawlerEvents::onPageDownload]);
        $this->assertEquals(0, $events[CrawlerEvents::onFoundLinks]);
    }

    private function createCrawler(Body $body)
    {
        $response = \Mockery::mock('\GuzzleHttp\Message\FutureResponse');
        $response->shouldReceive('getBody')->andReturn($body)
            ->shouldReceive('getStatusCode')->andReturn(200);

        $client = \Mockery::mock('\GuzzleHttp\Client');
        $client->shouldReceive('get')->andReturn($response);

        $downloader = new PageDownloader();
        $downloader->setClient($client);

        $manager = new QueueManager(new ArrayQueue(), new ArrayStore());
        $manager->addValidator(new FuckThatLink());

        $crawler = new DomainCrawler(
            $manager,
            new LinkFinder()
        );

        $crawler->setDownloader($downloader);

        return $crawler;
    }
}

class Body {
    private $body;
    public function __construct($body)
    {
        $this->body = $body;
    }

    public function __toString()
    {
        return $this->body;
    }
}

class FuckThatLink implements Validator
{
    public function isValid(Link $link)
    {
        return ($link->getLinkHref() === 'http://dmoz.com/');
    }
}

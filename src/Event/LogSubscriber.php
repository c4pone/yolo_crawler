<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\Event;

class LogSubscriber implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            CrawlerEvents::onStart => array('onStart', 0),
            CrawlerEvents::onFinish => array('onFinish', 0),
            CrawlerEvents::onPopLinkFromQueue => array('onPop', 0),
        );
    }

    public function onStart(Event $event)
    {
        echo "Crawler started \n";
    }  

    public function onFinish(Event $event)
    {
        echo "Crawler finished \n";
    }  

    public function onPop(FilterLinkEvent $event)
    {
        echo('crawl -> '.$event->getLink()->getFullUrl(). "\n");
    }
}

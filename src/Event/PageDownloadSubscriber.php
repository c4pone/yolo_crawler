<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class PushLinkSubscriber implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            CrawlerEvents::onPageDownload => array('onPageDownload', 0),
        );
    }

    public function onPageDownload(FilterPageResponseEvent $event)
    {
        $link = $event->getLink();
        $response = $event->getResponse();
        $link->setStatusCode($response->getStatusCode());
        $event->setLink($link);
    }  
}

<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class PushLinkSubscriber implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            CrawlerEvents::onPushLinkToQueue => array('onPushLinkToQueue', 0),
        );
    }

    public function onPushLinkToQueue(FilterLinkEvent $event)
    {
    }  
}

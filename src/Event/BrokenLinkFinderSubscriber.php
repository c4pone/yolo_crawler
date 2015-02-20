<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use  WP\Crawler\Queue\Validator\NoJavascriptUrl;

class BrokenLinkFinderSubscriber implements EventSubscriberInterface
{
    private $links = array();
    private $brokenLinks = array();

    static public function getSubscribedEvents()
    {
        return array(
            CrawlerEvents::onFoundLinks => array('onFoundLinks', 0),
            CrawlerEvents::onFinish => array('onFinish', 0),
        );
    }

    public function onFoundLinks(FoundLinksEvent $event)
    {
        $this->links = array_merge($event->getLinks(), $this->links);
    }

    public function onFinish(FilterCrawlerProcessEvent $event)
    {
        $rollingCurl = new \RollingCurl\RollingCurl();
        $rollingCurl->setSimultaneousLimit(50);

        // kicks out javascript:void(0) and # urls
        $validator = new NoJavascriptUrl();

        // loop through all the links and add them to rollingcurl
        foreach ($this->links as &$link)
        {
            if ($validator->isValid($link)) {
                // add get request to curl
                $rollingCurl->get($link->getLinkHref(), null, array(&$link));
            }
        }

        $brokenLinks = array();
        $rollingCurl->setCallback(function(
            \RollingCurl\Request $request, 
            \RollingCurl\RollingCurl $rollingCurl) use (&$brokenLinks) {

                $link = $request->getOptions()[0];
                echo ("checking -> ". $link->getLinkHref() ."\n");
                $link->setStatusCode($request->getResponseInfo()['http_code']);
                if ($link->getStatusCode() != 200)
                    $brokenLinks[] = $link;

            });

        $rollingCurl->execute();
        $this->brokenLinks = $brokenLinks;
    }

    public function getBrokenLinks()
    {
        return $this->brokenLinks; 
    }
}

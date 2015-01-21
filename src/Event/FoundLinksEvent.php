<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\Event;

class FoundLinksEvent extends FilterCrawlerProcessEvent
{
    protected $links = array();

    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks($links)
    {
        $this->links = $links;
    }
}

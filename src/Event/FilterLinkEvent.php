<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\Event;
use WP\Crawler\Link;

class FilterLinkEvent extends FilterCrawlerProcessEvent
{
    protected $link;
    public function __construct(Link $link)
    {
        $this->link = $link;
    }
    public function getLink()
    {
        return $this->link;
    }
    public function setLink($link)
    {
        $this->link = $link;
    }

}

<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\Event;
use WP\Crawler\CrawlerProcess;

class FilterCrawlerProcessEvent extends Event
{
    protected $process;
    public function __construct(CrawlerProcess $process)
    {
        $this->process = $process;
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function setProcess($process)
    {
        $this->process = $process;
    }

}

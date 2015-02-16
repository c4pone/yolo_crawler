<?php namespace WP\Crawler\Event;

use Symfony\Component\EventDispatcher\Event;
use WP\Crawler\Link;

class FilterPageResponseEvent extends FilterLinkEvent {

    protected $response;

    public function __construct(Link $link, $response)
    {
        parent::__construct($link);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }
}

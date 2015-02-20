<?php namespace WP\Crawler;

class Domain {
    protected $domain;
    private $url;

    public function __construct($url, $startPoint = '/') 
    {
        $this->domain = $this->convertDomain($url, $startPoint);
    }

    public function getUrl()
    {
        return (string) $this->url; 
    }

    public function getParser() 
    {
        return $this->url;
    }

    private function convertDomain($url, $startPoint)
    {
        $this->url = new \webignition\Url\Url($url);
        if ($this->url->getHost() === null) {
            $this->url->setScheme('http');
            $this->url->setHost($url);
        }

        $this->url->setPath($startPoint);

        return (string) $this->url;
    }
}

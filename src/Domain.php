<?php namespace WP\Crawler;

class Domain {
    protected $domain;
    public function __construct($url) 
    {
        $this->domain = $this->convertDomain($url);
    }

    public function getDomain()
    {
        return $this->domain; 
    }

    private function convertDomain($url)
    {
        $domain = '';
        $url_parts = parse_url($url);
        if ( ! isset($url_parts['scheme']))
            $domain .= 'http';
        else 
            $domain .= $url_parts['scheme'];

        $domain .= '://';

        if ( ! isset($url_parts['host']))
            $domain .= $url;
        else
            $domain .= $url_parts['host'];

        return $domain;
    }
}

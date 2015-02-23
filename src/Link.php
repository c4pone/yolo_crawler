<?php namespace WP\Crawler;

class Link {


    private $link_href;
    private $link_text;
    private $link_origin;
    private $page_title;
    private $origin_domain;
    private $status_code;

    private $response;

    public function __construct($link_href)
    {
        $this->link_href = $link_href;
    }

    public function getLinkText()
    {
        return $this->link_text;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Checks if the given href is not part 
     * of the domain we are crawling
     *
     * @return boolean
     */
    public function isLeavingOriginDomain()
    {
        if ($this->origin_domain === null) 
            throw new \Exception('origin is null');


        $domain = $this->origin_domain->getParser();
        $link = new \webignition\Url\Url($this->link_href);
        $domain_host = $domain->getHost();
        $link_host = $link->getHost();

        if ($link_host === null) {
            return false; 
        }

        return  ! $domain_host->equals($link_host);
    }

    public function isValid()
    {
        return (filter_var($this->link_href, FILTER_VALIDATE_URL) !== FALSE);
    }

    public function getPageTitle()
    {
        return $this->page_title; 
    }

    public function getOriginDomain()
    {
        return $this->origin_domain;
    }

    /**
     * Returns the FullUrl 
     *
     * @return string
     */
    public function getLinkHref()
    {
        return $this->link_href;
    }

    /**
     * Returns the hash of the full url 
     * 
     * @return string
     */
    public function getHash()
    {
        return sha1($this->getLinkHref());
    }

    public function setLinkHref($href)
    {
        $this->link_href = $href; 
        return $this;
    }

    public function setLinkText($link_text)
    {
        $this->link_text = $link_text;
        return $this;
    }

    public function setOriginDomain(Domain $domain)
    {
        $this->origin_domain = $domain; 
        return $this;
    }

    public function setOrigin($link)
    {
        $this->link_origin = $link->getLinkHref(); 
        return $this;
    }

    public function setPageTitle($title)
    {
        $this->page_title = $title; 
        return $this;
    }

    public function setStatusCode($code)
    {
        $this->status_code = $code; 
        return $this;
    }

    public function setResponse($response)
    {
        $this->response = $response; 
    }

    public function getResponse()
    {
        return $this->response; 
    }

    public function getHtml()
    {
        if ($this->response === null)
            return '';

        return $this->response->getBody()->__toString();
    }
}


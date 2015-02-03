<?php namespace WP\Crawler;

class Link {


    private $link_href;
    private $link_text;
    private $link_is_external;
    private $link_origin;
    private $page_title;
    private $origin_domain;
    private $status_code;

    private $response;

    public function __construct($href)
    {
        $this->link_href = $href;
    }

    public function getLinkHref()
    {
        return $this->link_href;
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
        if ($this->isExternalLink() && empty($this->origin_domain))
            return true;

        elseif ($this->isExternalLink())
            return (preg_match("|^".$this->origin_domain."|", $this->link_href)) ? false : true;

        return false;
    }

    /**
     * Checks if the given href is starting
     * with http/https 
     *
     * @return boolean
     */
    public function isExternalLink()
    {
        if ($this->link_is_external === null) 
            $this->link_is_external = (preg_match("|^https?://|", $this->link_href)) ? true : false;

        return $this->link_is_external;
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
     * Combines the given domain and href if the 
     * href is not already a full url
     *
     * @return string
     */
    public function getFullUrl()
    {
        if ($this->isExternalLink()) 
            return $this->link_href;

        $url = $this->origin_domain;

        if (substr($this->link_href, 0, 1) !== '/') {
            $url .= '/';
        }

        return $url . $this->link_href;
    }

    /**
     * Returns the hash of the full url 
     * 
     * @return string
     */
    public function getHash()
    {
        return sha1($this->getFullUrl());
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

    public function setOriginDomain($domain)
    {
        $this->origin_domain = $domain; 
        return $this;
    }

    public function setOrigin($link)
    {
        $this->link_origin = $link; 
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


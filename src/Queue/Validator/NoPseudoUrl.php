<?php namespace WP\Crawler\Queue\Validator;

use WP\Crawler\Link;

class NoPseudoUrl implements Validator {
    public function isValid(Link $link)
    {
        $link = new \webignition\Url\Url($link->getLinkHref());
        return ($link->getScheme() === 'http' 
            || $link->getScheme() === 'https'
            || $link->getHost() !== null
        );
    }
}

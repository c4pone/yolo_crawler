<?php namespace WP\Crawler\Queue\Validator;

use WP\Crawler\Link;

class NoJavascriptUrl implements Validator {
    public function isValid(Link $link)
    {
        $isValid = true;

        if (strtolower($link->getLinkHref()) === 'javascript:void(0)')
            $isValid = false;

        else if ($link->getLinkHref() === '#')
            $isValid = false;

        return $isValid; 
    }
}

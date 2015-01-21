<?php namespace WP\Crawler\Queue\Validator;

use WP\Crawler\Link;

class ValidFileExtension implements Validator {
    private $evil_pattern = "/.*(\.(css|js|bmp|gif|jpe?g|png|tiff?|mid|mp2|mp3|mp4|wav|avi|mov|mpeg|ram|m4v|pdf|rm|smil|wmv|swf|wma|zip|rar|gz))$/";

    public function isValid(Link $link)
    {
        return (preg_match($this->evil_pattern, $link->getLinkHref()) === 0);
    }
}

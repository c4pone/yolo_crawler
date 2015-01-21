<?php namespace WP\Crawler\Queue\Validator;

use WP\Crawler\Link;

interface Validator {

    /**
     * Checks if the given link is valid
     *
     * @param  \WP\Crawler\Link $link
     * @return boolean
     */
    public function isValid(Link $link);
}

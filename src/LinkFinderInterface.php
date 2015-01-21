<?php namespace WP\Crawler;

interface LinkFinderInterface {

    /**
     * Extracts the links from the html
     *
     * @param  string $html
     * @return array
     */
    public function getLinks($html);
}


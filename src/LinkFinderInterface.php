<?php namespace WP\Crawler;

interface LinkFinderInterface {

    /**
     * Extracts the links from the html
     *
     * @param  string $html
     * @return array
     */
    public function getLinks($html);

    /**
     * Extracts the page title from the html
     *
     * @param  string $html
     * @return string
     */
    public function getTitle($html);
}


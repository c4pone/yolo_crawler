<?php namespace WP\Crawler\Downloader;

interface Downloader
{
    /*
     * Downloads the given path 
     *
     * If it fails it tries again until it reach the max
     * amount of $max_tries. When maximum of tries are reached
     * a DownloadException is thrown
     *
     * @param string $path
     * @param int $max_tries
     * @return $response;
     * @throws WP\Crawler\Downloader\DownloadException
     * @throws \InvalidArgumentException
     * 
     */
    public function download($path, $max_tries = 3);
}



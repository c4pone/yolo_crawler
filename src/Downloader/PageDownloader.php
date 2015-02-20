<?php namespace WP\Crawler\Downloader;

class PageDownloader implements Downloader
{
    private $client;
    private $userAgent;

    /*
     * Time to wait between each try
     *
     * @var int 
     */
    private $wait_time = 10;
    
    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
        $this->userAgent = new UserAgent();
    }

    /*
     * Set the time to wait between each request
     *
     * @param int $sec 
     * @return $this;
     */
    public function setWaitTime($sec)
    {
         $this->wait_time = $sec;
         return $this;
    }

    /*
     * Sets Client
     *
     * @param \GuzzleHttp\Client $client
     * @return $this;
     */
    public function setClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client; 
        return $this;
    }

    /*
     * Gets Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client; 
    }

    /**
     * Download web page and returns  response
     *
    * @return \GuzzleHttp\Message\ResponseInterface
    * 
    * */
    public function download($path, $max_tries = 3)
    {
        if ( ! is_int($max_tries))
            throw new \InvalidArgumentException('max_tries has to be a integer');

        return $this->downloadPage($path, 1, $max_tries);
    }

    /*
     * Downloads the given page 
     *
     * @param string $path
     * @param int $current_try
     * @param int $max_tries
     *
     * @return $response;
     * @throws  WP\Crawler\Downloader\DownloadException
     * 
     */
    private function downloadPage($path, $current_try, $max_tries)
    {
        $userAgent = $this->userAgent->getRandomOne();

        if ($current_try > $max_tries) {
            throw new DownloadException($max_tries . ' attempts failed to download this page');
        }

        try {
            $response = $this->client->get($path, [ 'exceptions' => false, 'headers' => [
                'User-Agent' => $userAgent
            ]]);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            sleep($this->wait_time);
            $this->downloadPage($path, ++$current_try, $max_tries);
        }

        return $response;
    }
}

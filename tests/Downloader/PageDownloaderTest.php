<?php

use WP\Crawler\Downloader\DownloadException;
use WP\Crawler\Downloader\Downloader;
use WP\Crawler\Downloader\PageDownloader;

class PageDownloaderTest extends PHPUnit_Framework_TestCase {
    public function testDownload ()
    {
        $max_tries = 3;
        $client = new FakeClient();
        $client->tries = $max_tries;

        $downloader = new PageDownloader();
        $downloader->setClient($client);
        $downloader->setWaitTime(0);

        try {
            $downloader->download('test', $max_tries);
            $this->fails();
        } catch (DownloadException $e) {
            $this->assertEquals($max_tries . ' attempts failed to download this page', $e->getMessage());
        }

        $response_2 = $downloader->download('test', $max_tries + 1);
        $this->assertEquals('foo', $response_2);
    }
}

class FakeClient extends \GuzzleHttp\Client {
    public $tries = 3;
    private $_tries = 0;

    public function get($url = NULL, $options = array()) {
        $this->_tries++;
        if ($this->_tries <= $this->tries) {
            throw new \GuzzleHttp\Exception\ConnectException(null, 
                new \GuzzleHttp\Message\Request('get', $url));
        }

        return 'foo';
    }
}

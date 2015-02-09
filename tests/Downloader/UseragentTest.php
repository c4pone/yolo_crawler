<?php

use WP\Crawler\Downloader\UserAgent;

class UserAgentTest extends PHPUnit_Framework_TestCase {
    public function testGetRandomOne()
    {
        try {
            $userAgent = new UserAgent();
            for ($i=0;$i<50;$i++) {
                $this->assertNotNull($userAgent->getRandomOne());
            }
        } catch (Exception $e) {
            $this->fails(); 
        }
    }
}

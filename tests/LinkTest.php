<?php

use WP\Crawler\Link;

class LinkTest extends PHPUnit_Framework_TestCase {

    public function testIsExternalLink()
    {
        $link = new Link('/test/bla');
        $this->assertFalse($link->isExternalLink());

        $link = new Link('http://google.com');
        $this->assertTrue($link->isExternalLink());
    }

    public function  testGetFullUrl()
    {
        $this->assertEquals('/link', (new Link('/link'))->getFullUrl());
        $this->assertEquals('/link', (new Link('link'))->getFullUrl());
        $this->assertEquals('http://google.com', (new Link('http://google.com'))->getFullUrl());
        $this->assertEquals('https://google.com', (new Link('https://google.com'))->getFullUrl());
    }

    public function testGetFullUrlWithSetDomain()
    {
        $link = new Link('link');
        $link->setOriginDomain('http://www.google.com');
        $this->assertEquals('http://www.google.com/link', $link->getFullUrl());
    }

    public function testIsLeavingOriginDomain()
    {
        $link = new Link('/link');
        $link->setOriginDomain('codebuster.de');
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('link');
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('http://codebuster.de/test/bla');
        $link->setOriginDomain('http://codebuster.de');
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('http://google.com.au/codebuster.de');
        $link->setOriginDomain('http://codebuster.de');
        $this->assertTrue($link->isLeavingOriginDomain());

        $link = new Link('http://google.com.au/codebuster.de');
        $this->assertTrue($link->isLeavingOriginDomain());
    }

    public function testGetHash()
    {
        $link = new Link('yolo');
        $link->setOriginDomain('codebuster.de');
        $this->assertEquals(sha1('codebuster.de/yolo'), $link->getHash());
    }
}

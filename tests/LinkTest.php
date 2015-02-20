<?php

use WP\Crawler\Link;
use WP\Crawler\Domain;

class LinkTest extends PHPUnit_Framework_TestCase {

    public function testIsLeavingOriginDomain()
    {
        $link = new Link('http://codebuster.de/test');
        $link->setOriginDomain(new Domain('codebuster.de'));
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('http://codebuster.de/test');
        $link->setOriginDomain(new Domain('http://codebuster.de'));
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('http://codebuster.de/test/bla');
        $link->setOriginDomain(new Domain('http://codebuster.de'));
        $this->assertFalse($link->isLeavingOriginDomain());

        $link = new Link('http://google.com.au/codebuster.de');
        $link->setOriginDomain(new Domain('codebuster.de'));
        $this->assertTrue($link->isLeavingOriginDomain());

        $link = new Link('http://google.com.au/codebuster.de');
        $link->setOriginDomain(new Domain('http://codebuster.de'));
        $this->assertTrue($link->isLeavingOriginDomain());
    }

    public function testGetHash()
    {
        $link = new Link('yolo');
        $this->assertEquals(sha1('yolo'), $link->getHash());
    }
}

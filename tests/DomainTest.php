<?php

use WP\Crawler\Domain;

class DomainTest extends PHPUnit_Framework_TestCase {

    public function testGetUrl()
    {
        $domain = new Domain('codebuster.de');
        $this->assertEquals('http://codebuster.de/', $domain->getUrl());

        $domain = new Domain('http://codebuster.de');
        $this->assertEquals('http://codebuster.de/', $domain->getUrl());
    }
}

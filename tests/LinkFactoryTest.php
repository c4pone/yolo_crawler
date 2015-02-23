<?php

use WP\Crawler\Link;
use WP\Crawler\Domain;
use WP\Crawler\LinkFactory;

class LinkFactoryTest extends PHPUnit_Framework_TestCase {

    private $factory;

    public function setUp()
    {
        $this->factory = new LinkFactory();
    }

    public function testGetLink()
    {
        $result = $this->factory->getLink(
            array('href'=>'/test/blub','text'=>'whatever'),
            new Domain('codebuster.de'),
            new Link('http://codebuster.de/foo')
        );

        $this->assertEquals('http://codebuster.de/test/blub', $result->getLinkHref());
        $this->assertEquals('whatever', $result->getLinkText());
        $this->assertNotNull($result->getOriginDomain());
    }

    public function testGetLink_Anchor()
    {
        $result = $this->factory->getLink(
            array('href'=>'#test','text'=>'whatever'),
            new Domain('codebuster.de'),
            new Link('http://codebuster.de/faq')
        );


        $this->assertEquals('http://codebuster.de/faq#test', $result->getLinkHref());
    }

    public function testGetLink_PseudoUrl()
    {
        $result = $this->factory->getLink(
            array('href'=>'callto:124312312','text'=>'whatever'),
            new Domain('codebuster.de'),
            new Link('http://codebuster.de/contact')
        );

        $link = new \webignition\Url\Url($result->getLinkHref());;
        $this->assertEquals('callto:124312312', $result->getLinkHref());
    }
}


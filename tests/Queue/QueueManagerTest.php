<?php

use WP\Crawler\Queue\ArrayQueue;
use WP\Crawler\Queue\QueueManager;
use WP\Crawler\Queue\Store\ArrayStore;
use WP\Crawler\Link;
use WP\Crawler\Domain;
use WP\Crawler\Queue\Validator\Validator;

class QueueManagerTest extends PHPUnit_Framework_TestCase {

    public function testPushAndPop()
    {
        $manager = new QueueManager(new ArrayQueue(), new ArrayStore());
        $manager->push($this->newLink('http://codebuster.de/blub/test','codebuster.de'));
        $manager->push($this->newLink('http://codebuster.de/blub/test','codebuster.de'));
        $manager->push($this->newLink('http://codebuster.de/test','codebuster.de'));

        $this->assertEquals('http://codebuster.de/test', $manager->pop()->getLinkHref());
        $this->assertEquals('http://codebuster.de/blub/test', $manager->pop()->getLinkHref());
        $this->assertFalse($manager->pop());
    }

    public function testPushAndPopWithAValidator()
    {
        $queue = new ArrayQueue();
        $manager = new QueueManager($queue, new ArrayStore());
        $manager->addValidator(new YoloValidator());
        $manager->push($this->newLink('http://codebuster.de/blub/test','codebuster.de'));
        $manager->push($this->newLink('http://codebuster.de/yolo','codebuster.de'));
        $manager->push($this->newLink('http://codebuster.de/test','codebuster.de'));

        $this->assertEquals('http://codebuster.de/yolo', $manager->pop()->getLinkHref());
        $this->assertFalse($manager->pop());
    }

    public function newLink($url, $domain)
    {
        $domain = new Domain($domain);
        $link = new Link($url);
        $link->setOriginDomain($domain);
        return $link;
    }
}

class YoloValidator implements Validator {
    public function isValid(Link $link)
    {
        return (strpos($link->getLinkHref(), 'yolo') !== false);
    }
}


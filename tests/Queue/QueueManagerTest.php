<?php

use WP\Crawler\Queue\ArrayQueue;
use WP\Crawler\Queue\QueueManager;
use WP\Crawler\Queue\Store\ArrayStore;
use WP\Crawler\Link;
use WP\Crawler\Queue\Validator\Validator;

class QueueManagerTest extends PHPUnit_Framework_TestCase {

    public function testPushAndPopOneItem()
    {
        $manager = new QueueManager(new ArrayQueue(), new ArrayStore());
        $manager->push(new Link('/blub/test'));
        $manager->push(new Link('/blub/test'));
        $manager->push(new Link('/test'));

        $this->assertEquals('/test', $manager->pop()->getLinkHref());
        $this->assertEquals('/blub/test', $manager->pop()->getLinkHref());
        $this->assertFalse($manager->pop());
    }

    public function testPushAndPopWithAValidator()
    {
        $manager = new QueueManager(new ArrayQueue(), new ArrayStore());
        $manager->addValidator(new YoloValidator());
        $manager->push(new Link('/blub/test'));
        $manager->push(new Link('yolo'));
        $manager->push(new Link('/test'));

        $this->assertEquals('yolo', $manager->pop()->getLinkHref());
        $this->assertFalse($manager->pop());
    }

    public function testPushAndPopWithDuplicates()
    {
        $manager = new QueueManager(new ArrayQueue(), new ArrayStore());

        $link_a = new Link('yolo');
        $link_a->setOriginDomain('http://google.com.au');
        $link_b = new Link('http://google.com.au/yolo');

        $manager->push($link_a);
        $manager->push($link_b);

        $this->assertEquals('yolo', $manager->pop()->getLinkHref());
        $this->assertFalse($manager->pop());
    }
}

class YoloValidator implements Validator {
    public function isValid(Link $link)
    {
        return ($link->getLinkHref() === 'yolo');
    }
}


<?php

use WP\Crawler\Queue\ArrayQueue;
use WP\Crawler\Link;

class ArrayQueueTest extends PHPUnit_Framework_TestCase {
    public function testPushAndPopOneItem()
    {
        $queue = new ArrayQueue();
        $queue->push('test');
        $this->assertEquals('test', $queue->pop());
    }

    public function testPopInLoop()
    {
        $data = array( 'test','blub','bla');

        $queue = new ArrayQueue();
        foreach ($data as $item)
        {
            $queue->push($item);
        }

        $id = 2;
        while ( ($item = $queue->pop()) != null)
        {
           $this->assertEquals($data[$id], $item);
           $id--;
        }
    }

    public function testPopLink()
    {
        $link = new Link('test');
        $queue = new ArrayQueue();
        $queue->push($link);
        $result = $queue->pop();

        $this->assertEquals('test', $result->getLinkHref());
    }

}

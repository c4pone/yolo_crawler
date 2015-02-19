<?php

use WP\Crawler\Queue\Store\RedisStore;

class RedisStoreTest extends PHPUnit_Framework_TestCase {

    private $store;
    private $n;

    protected function setUp()
    {
        $this->n = 'test-namespace';
        $this->store = new RedisStore('127.0.0.1');
        $this->store->reset($this->n);
    }

    public function testToSetAndGetValues()
    {
        $this->store->set($this->n, 'key', 'value');
        $this->store->set($this->n, 'key', 'value2');

        $this->assertTrue($this->store->has($this->n, 'key'));
        $this->assertEquals('value2', $this->store->get($this->n,'key'));
    }

    public function testReset()
    {
        $this->store->set($this->n, 'key', 'value');
        $this->store->reset($this->n);

        $this->assertFalse($this->store->has($this->n, 'key'));
    }
}

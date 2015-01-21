<?php

use WP\Crawler\Queue\Store\ArrayStore;

class StoreTest extends PHPUnit_Framework_TestCase {
    public function testToSetAndGetValues()
    {
        $ns = 'test';
        $store = new ArrayStore();
        $store->set($ns, 'key', 'value');
        $store->set($ns, 'key', 'value2');

        $this->assertTrue($store->has($ns, 'key'));
        $this->assertEquals('value2', $store->get($ns,'key'));
    }
}

<?php

use WP\Crawler\Link;
use WP\Crawler\Queue\Validator\NoPseudoUrl;

class NoPseudoUrlTest extends PHPUnit_Framework_TestCase {

    public function testIsValid()
    {
        $validator = new NoPseudoUrl();

        $this->assertTrue($validator->isValid(new Link('http://www.google.com/js#test')));
        $this->assertTrue($validator->isValid(new Link('https://www.google.com/js#test')));
        $this->assertFalse($validator->isValid(new Link('javascript:void(0)')));
        $this->assertFalse($validator->isValid(new Link('callto:213129')));
    }
}

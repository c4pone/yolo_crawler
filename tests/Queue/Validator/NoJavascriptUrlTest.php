<?php

use WP\Crawler\Link;
use WP\Crawler\Queue\Validator\NoJavascriptUrl;

class NoJavascriptUrlTest extends PHPUnit_Framework_TestCase {

    public function testIsValid()
    {
        $validator = new NoJavascriptUrl();

        $this->assertTrue($validator->isValid(new Link('www.google.com/js')));
        $this->assertFalse($validator->isValid(new Link('#')));
        $this->assertFalse($validator->isValid(new Link('javascript:void(0)')));
    }
}

<?php

use WP\Crawler\Link;
use WP\Crawler\Queue\Validator\ValidFileExtension;

class ValidFileExtensionTest extends PHPUnit_Framework_TestCase {

    public function testIsValid()
    {
        $validator = new ValidFileExtension();

        $this->assertTrue($validator->isValid(new Link('www.google.com/js')));
        $this->assertFalse($validator->isValid(new Link('www.google.com/test.js')));
        $this->assertFalse($validator->isValid(new Link('www.google.com/test.css')));
    }
}

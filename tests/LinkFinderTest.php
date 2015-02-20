<?php

use WP\Crawler\LinkFinder;

class LinkFinderTest extends PHPUnit_Framework_TestCase {

    public function testGetLinksFromHTML()
    {
        $html = <<<'HTML'
        <!DOCTYPE html>
        <html>
            <head><title>this is the title</title></head>
            <body>
                <a href="link1">test</a>
                <a href="link2">test</a>
            </body>
        </html>
HTML;

        $extractor = new LinkFinder();
        $links = $extractor->getLinks($html);
        $this->assertEquals('link1', $links[0]['href']);
        $this->assertEquals('link2', $links[1]['href']);

        $this->assertEquals('test', $links[0]['text']);
        $this->assertEquals('test', $links[1]['text']);
    }

    public function testDmoz()
    {
        $html = file_get_contents(__DIR__.'/data/dmoz.html');
        $extractor = new LinkFinder();
        $links = $extractor->getLinks($html);
        $title = $extractor->getTitle($html);

        $this->assertEquals(103, count($links));
        $this->assertEquals('DMOZ - the Open Directory Project', $title);
    }
}

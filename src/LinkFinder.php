<?php namespace WP\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use WP\Crawler\Link;

class LinkFinder implements LinkFinderInterface {

    public function getLinks($html)
    {
        $crawler = new Crawler($html);

        $links = $crawler->filterXPath('//a[@href]')->each(function (Crawler $node, $i) {
            $link = new Link($node->attr('href'));
            $link->setLinkText($node->text());
            return $link;
        });

        return $links;
    }

    public function getTitle($html)
    {
        $crawler = new Crawler($html);

        $title = $crawler->filterXPath('//title');
        if ($title->count()) 
            $title = $title->text();
        else
            $title = 'unknown';

        return $title;
    }
}


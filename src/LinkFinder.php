<?php namespace WP\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use WP\Crawler\Link;

class LinkFinder implements LinkFinderInterface {

    public function getLinks($html)
    {
        $crawler = new Crawler($html);

        $title = $crawler->filterXPath('//title');
        if ($title->count()) 
            $title = $title->text();
        else
            $title = 'unknown';

        $links = $crawler->filterXPath('//a[@href]')->each(function (Crawler $node, $i) use ($title){
            $link = new Link($node->attr('href'));
            $link->setPageTitle($title);
            $link->setLinkText($node->text());
            return $link;
        });

        return $links;
    }
}


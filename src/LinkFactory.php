<?php namespace WP\Crawler;

use WP\Crawler\Link;

class LinkFactory {

    public function getLink(array $linkData, Domain $domain, Link $origin)
    {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            $linkData['href'],
            $origin->getLinkHref()
        );

        $link = new Link((string) $deriver->getAbsoluteUrl());
        $link->setOriginDomain($domain);
        $link->setLinkText($linkData['text']);
        $link->setOrigin($origin);

        return $link;
    }
}


<?php namespace WP\Crawler\Event;

final class CrawlerEvents
{
    /**
     * The onStart event is thrown once when the crawler starts
     *
     * The event listener receives an
     * WP\Crawler\Event\FilterDomainEvent instance.
     *
     * @var string
     */
    const onStart = 'crawler.start';

    /**
     * The onFinish event is thrown once when the crawler
     * finish
     *
     * The event listener receives an
     * WP\Crawler\Event\FilterDomainEvent instance.
     *
     * @var string
     */
    const onFinish = 'crawler.finish';

    /**
     * The onPushLinkToQueue event is thrown each time
     * before a link is pushed to the queue
     *
     * The event listener receives an
     * WP\Crawler\Event\FilterLinkEvent instance.
     *
     * @var string
     */
    const onPushLinkToQueue = 'crawler.link_push_to_queue';

    /**
     * The onPopLinkFromQueue event is thrown each time
     * a link got popped from the queue
     *
     * The event listener receives an
     * WP\Crawler\Event\FilterLinkEvent instance.
     *
     * @var string
     */
    const onPopLinkFromQueue = 'crawler.link_pop_from_queue';

    /**
     * The onPageDownload event is thrown each time
     * a page web page got tried to download 
     *
     * The event listener receives an
     * WP\Crawler\Event\FilterPageResponseEvent instance.
     *
     * @var string
     */
    const onPageDownload = 'crawler.page_downloaded';

    /**
     * The onFoundLinks event is thrown each time
     * links got extract from the downloaded web page
     *
     * The event listener receives an
     * WP\Crawler\Event\FoundLinksEvent instance.
     *
     * @var string
     */
    const onFoundLinks = 'crawler.found_links';

    /**
     * The onLinkProcessed event is thrown each time
     * a Link got processed
     *
     * The event listener receives an
     * WP\Crawler\Event\FoundLinksEvent instance.
     *
     * @var string
     */
    const onLinkProcessed = 'crawler.link_processed';
}

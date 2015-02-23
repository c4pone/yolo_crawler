#yolo crawler


Status Label  | Status Value
--------------|-------------
Build | [![Build Status](https://travis-ci.org/c4pone/yolo_crawler.svg?branch=master)](https://travis-ci.org/c4pone/yolo_crawler)
Code Quality | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/c4pone/yolo_crawler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/c4pone/yolo_crawler/?branch=master)


## find broken links example
```php

require 'bootstrap/autoload.php';

use WP\Crawler\LinkFinder;
use WP\Crawler\DomainCrawler;
use WP\Crawler\Queue\QueueManager;
use WP\Crawler\Queue\ArrayQueue;
use WP\Crawler\Queue\Store\ArrayStore;
use WP\Crawler\Queue\Validator\ValidFileExtension;
use WP\Crawler\Queue\Validator\NoPseudoUrl;
use WP\Crawler\Event\LogSubscriber;
use WP\Crawler\Event\BrokenLinkFinderSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

if (isset($argv[1])) {
    $domain = $argv[1];

    $manager = new QueueManager(new ArrayQueue(), new ArrayStore());
    $manager->addValidator(new NoPseudoUrl())
        ->addValidator(new ValidFileExtension());

    $crawler = new DomainCrawler(
        $manager,
        new LinkFinder()
    );

    if (isset($argv[2]))
        $crawler->setWaitTime($argv[2]);

    $dispatcher = $crawler->getEventDispatcher();
    $dispatcher->addSubscriber(new LogSubscriber);
    $dispatcher->addSubscriber(new BrokenLinkFinderSubscriber);

    $crawler->crawl($domain);

} else {
    echo "\n";
    echo ("Usage " . $argv[0] . ' {domain} {time to wait}' . "\n");
}
```

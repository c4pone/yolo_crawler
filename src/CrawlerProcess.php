<?php namespace WP\Crawler;

class CrawlerProcess {

    const NOT_STARTED = 1;
    const RUNNING = 2;
    const STOPPED = 3;
    const DONE = 4;

    protected $state;

    public function __construct()
    {
        $this->state = self::NOT_STARTED;
    }

    public function start()
    {
        $this->state = self::RUNNING;
    }

    public function done()
    {
        $this->state = self::DONE;
    }

    public function stop()
    {
        $this->state = self::STOPPED;
    }

    public function isRunning()
    {
        return $this->state === self::RUNNING;
    }

    public function isStopped()
    {
        return $this->state === self::STOPPED;
    }

    public function isDone()
    {
        return $this->state === self::DONE;
    }
}

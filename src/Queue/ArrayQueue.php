<?php namespace WP\Crawler\Queue;

class ArrayQueue implements Queue
{
    private $queue = array();

    public function push($item)
    {
        array_push($this->queue,$item);
    }

    public function pop()
    {
        return array_pop($this->queue);
    }

    public function itemCount()
    {
        return count($this->queue);
    }
}

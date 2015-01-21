<?php namespace WP\Crawler\Queue;

interface Queue
{
    /**
     * Push item to the queue
     *
     * @param  mixed $item
     * @return void
     */
    public function push($item);

    /**
     * Pop item from the queue
     *
     * @return mixed
     */
    public function pop();

    /**
     * Counts item in queue
     *
     * @return int
     */
    public function itemCount();
}

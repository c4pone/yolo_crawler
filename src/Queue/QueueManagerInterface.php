<?php namespace WP\Crawler\Queue;

use WP\Crawler\Queue\Store\Store;
use WP\Crawler\Link;

interface QueueManagerInterface 
{
    /**
     * Adds Validator
     *
     * Adds an validator to the list.The validators are used
     * to check if link should be pushed to the queue or not
     * 
     * @param  \WP\Crawler\Queue\Validator\Validator $validator
     * @return void
     */
    public function addValidator(Validator\Validator $validator);

    /**
     * Checks if link is valid
     *
     * Checks if a link is valid with the validators that got 
     * added over the addValidator method
     * 
     * @param  \WP\Crawler\Link $item
     * @return boolean
     */
    public function isValid(Link $item);

    /**
     * Pushs a link to the Queue
     * 
     * @param  \WP\Crawler\Link $item
     * @return void
     */
    public function push(Link $item);

    /**
     * Pops a from the Queue
     * 
     * @return mixed
     */
    public function pop();
}

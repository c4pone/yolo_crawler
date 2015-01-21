<?php namespace WP\Crawler\Queue;

use WP\Crawler\Queue\Store\Store;
use WP\Crawler\Link;

class QueueManager implements QueueManagerInterface
{
    private $queue;
    private $store;
    private $ns;
    private $validatorList;

    public function __construct(Queue $queue, Store $store, $namespace='queue-seen')
    {
        $this->queue = $queue;
        $this->store = $store;
        $this->ns = $namespace;
        $this->validatorList = array();
    }

    public function addValidator(Validator\Validator $validator)
    {
        $this->validatorList[] = $validator; 
        return $this;
    }

    public function isValid(Link $item)
    {
        foreach ($this->validatorList as $validator) 
        {
            if ( ! $validator->isValid($item))
                return false;
        }

        return true;
    }

    public function push(Link $item)
    {
        if ($this->isValid($item)) {
            $key = $item->getHash();
            if ( ! $this->store->has($this->ns, $key)) {
                if ( ! $item->isLeavingOriginDomain())
                    $this->queue->push($item);

                $this->store->set($this->ns, $key, $item);
            }
        }

        return $this;
    }

    public function pop()
    {
        $item = $this->queue->pop();
        if ($item === null)
            return false;

        return $item;
    }
}

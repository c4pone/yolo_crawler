<?php namespace WP\Crawler\Queue\Store;

class ArrayStore implements Store
{
    protected $store = array();

    public function set($namespace, $key, $value)
    {
        if ( ! isset($this->store[$namespace]))
            $this->store[$namespace] = array();
        $this->store[$namespace][$key] = $value;
    }

    public function get($namespace, $key)
    {
        return $this->store[$namespace][$key];
    }

    public function has($namespace, $key)
    {
        if ( ! isset($this->store[$namespace]))
            return false;

        return array_key_exists($key, $this->store[$namespace]);
    }

    public function reset($namespace)
    {
        $this->store[$namespace] = array();
    }
}

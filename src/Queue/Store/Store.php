<?php namespace WP\Crawler\Queue\Store;

interface Store
{
    /**
     * Sets a value in the store 
     *
     * @param  string   $namespace
     * @param  string   $key
     * @param  string   $value
     * @return void
     */
    public function set($namespace, $key, $value);

    /**
     * Gets a value from the store
     *
     * @param  string   $namespace
     * @param  string   $key
     * @return string
     */
    public function get($namespace, $key);

    /**
     * Checks if key exists in store
     *
     * @param  string   $namespace
     * @param  string   $key
     * @return boolean
     */
    public function has($namespace, $key);
}

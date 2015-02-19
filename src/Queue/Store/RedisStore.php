<?php namespace WP\Crawler\Queue\Store;

/**
 * Makes use of https://github.com/nrk/predis
 */
use \Predis\Client;

class RedisStore implements Store
{
    private $redis;

    public function __construct($host, $port=6379, $scheme='tcp')
    {
        $this->redis = new Client(array(
            "scheme" => $scheme,
            "host" => $host,
            "port" => $port));
    }

    public function set($namespace, $key, $value)
    {
        $this->redis->hset($namespace, $key, $value);
    }

    public function get($namespace, $key)
    {
        return $this->redis->hget($namespace, $key);
    }

    public function has($namespace, $key)
    {
        return $this->redis->hexists($namespace, $key);
    }

    public function reset($namespace)
    {
        $this->redis->del($namespace);
    }
}

class RedisConnectionException extends \Exception {}

<?php

namespace App\Service;

use Predis\Client;
use Redis;
use RedisCluster;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class RedisService
 * @package App\Service
 */
class RedisService
{
    /**
     * @var Redis|RedisCluster|Client
     */
    private $cache;

    /**
     * RedisService constructor.
     */
    public function __construct()
    {
        $this->cache = RedisAdapter::createConnection(
            getenv('REDIS_DSN'),
            [
                'lazy' => true,
            ]
        );
    }

    /**
     * @return Client|Redis|RedisCluster
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->cache->set($key, $value);
    }

    /**
     * @param $key
     * @param $ttl
     * @param $value
     * @return bool
     */
    public function setex($key, $ttl, $value)
    {
        return $this->cache->setex($key, $ttl, $value);
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param $keys
     * @return int
     */
    public function del($keys)
    {
        return $this->cache->del($keys);
    }
}

<?php

namespace App\Socket;

use Cmfcmf\OpenWeatherMap\AbstractCache;
use Predis\Client as Redis;

class Cache extends AbstractCache
{
    /**
     * @var null
     */
    static $client = null;

    /**
     * @var
     */
    private $namespace;
    private $redis;

    /**
     * Cache constructor.
     */
    public function __construct()
    {
        $this->redis = static::getClient();
    }

    /**
     * @param $namespace
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
     * @param $url
     * @return bool
     */
    public function isCached($url)
    {
        $cached = $this->redis->get($this->namespace.$url);

        if ( !$cached )
        {
            return false;
        }

        if ( $cached
            && ( $parsed = json_decode($cached) )
            && ( $parsed[0] + $this->seconds < time() ) )
        {
            return false;
        }

        return true;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function getCached($url)
    {
        $cached  = json_decode($this->redis->get($this->namespace.$url));
        return $cached[1];
    }

    /**
     * @param string $url
     * @param string $content
     * @return void
     */
    public function setCached($url, $content)
    {
        $this->redis->set($this->namespace.$url, json_encode([time(), $content]));
    }

    /**
     * Singleton for redis client
     * @return null
     */
    private static function getClient() {
        if ( !Cache::$client )
        {
            Cache::$client = new Redis(config('redis'));
        }


        return Cache::$client;
    }
}
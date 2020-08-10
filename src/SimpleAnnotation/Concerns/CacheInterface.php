<?php

namespace SimpleAnnotation\Concerns;

/**
 * Cache abstraction interface.
 *
 * @package SimpleAnnotation\Concerns
 */
interface CacheInterface
{
    /**
     * Save a value in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return CacheInterface
     */
    public function set(string $key, $value) : CacheInterface;

    /**
     * Retrieves a value from the cache.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Verifies if a cache entry exists in the cache.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key);

    /**
     * Deletes an entry from the cache.
     *
     * @param string $key
     * @return CacheInterface
     */
    public function delete(string $key) : CacheInterface;

    /**
     * Empty the cache.
     *
     * @return CacheInterface
     */
    public function clear() : CacheInterface;
}

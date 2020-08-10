<?php

namespace SimpleAnnotation\Concerns\Cache;

use SimpleAnnotation\Concerns\CacheInterface;

/**
 * Cache abstraction concrete implementation.
 * Implements a file handling approach.
 *
 * @package SimpleAnnotation\Concerns\Cache
 */
final class FileCache implements CacheInterface
{
    /** @var string */
    private string $path;

    /** @var array */
    private array $values = [];

    /**
     * FileCache constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        clearstatcache();
        if (! file_exists($path)) {
            touch($path);
        } else {
            $this->values = (array)json_decode(file_get_contents($path));
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, $value) : CacheInterface
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->values[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return isset($this->values[$key]);
    }

    /**
     * @param string $key
     * @return $this
     */
    public function delete(string $key) : CacheInterface
    {
        unset($this->values[$key]);

        return $this;
    }

    /**
     * @return $this
     */
    public function clear() : CacheInterface
    {
        $this->values = [];

        return $this;
    }

    /**
     * Persists cache data to the file.
     */
    public function persist()
    {
        file_put_contents($this->path, json_encode($this->values));
    }

    /**
     * Destruct magic method.
     * Here is where the class persists the information in the file.
     */
    public function __destruct()
    {
        $this->persist();
    }
}

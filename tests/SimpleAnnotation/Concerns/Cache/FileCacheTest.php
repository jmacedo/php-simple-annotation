<?php

namespace Tests\SimpleAnnotation\Concerns\Cache;

use PHPUnit\Framework\TestCase;
use SimpleAnnotation\Concerns\Cache\FileCache;

class FileCacheTest extends TestCase
{
    private string $file;

    private FileCache $cache;

    public function setUp(): void
    {
        $this->file = __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'cache' . DIRECTORY_SEPARATOR
            . 'file_cache_test.cache';

        $this->cache = new FileCache($this->file);
    }

    public function tearDown(): void
    {
        unlink($this->file);
    }

    public function testPersistValuesWithSuccess()
    {
        $this->cache->set('foo', 'bar')
            ->set('name', 'John Doe')
            ->set('list', [1, 2, 3, 4])
            ->persist();

        $object = json_decode(file_get_contents($this->file));

        $this->assertEquals('bar', $object->foo);
        $this->assertEquals('John Doe', $object->name);
        $this->assertCount(4, $object->list);

        // Have to unset to destruct the object and persist the cache, to unlink in tearDown().
        unset($this->cache);
    }

    /**
     * @depends testPersistValuesWithSuccess
     */
    public function testDeleteValue()
    {
        $this->cache = new FileCache($this->file);

        $this->cache->set('foo', 'bar')->set('name', 'John Doe')->persist();
        $object = json_decode(file_get_contents($this->file));

        $this->assertEquals('bar', $this->cache->get('foo'));
        $this->assertEquals('bar', $object->foo);

        $this->cache->delete('foo')->persist();
        $object = json_decode(file_get_contents($this->file));

        $this->assertNull($this->cache->get('foo'));
        $this->assertObjectNotHasAttribute('foo', $object);

        unset($this->cache);
    }

    /**
     * @depends testDeleteValue
     */
    public function testClearCache()
    {
        $this->cache->set('foo', 'bar');
        
        $this->assertEquals('bar', $this->cache->get('foo'));

        $this->cache->clear()->persist();
        $object = json_decode(file_get_contents($this->file));

        $this->assertNull($this->cache->get('foo'));
        $this->assertIsNotObject($object);

        unset($this->cache);
    }
}

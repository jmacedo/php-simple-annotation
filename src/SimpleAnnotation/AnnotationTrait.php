<?php

namespace SimpleAnnotation;

use ReflectionClass;
use SimpleAnnotation\Concerns\CacheInterface;
use SimpleAnnotation\Concerns\ParsedAnnotation;
use SimpleAnnotation\Concerns\Parser;

/**
 * Trait AnnotationTrait
 *
 * @package SimpleAnnotation
 */
trait AnnotationTrait
{
    /** @var ReflectionClass */
    private ReflectionClass $reflectionClass;

    /** @var Parser */
    private Parser $annotationParser;

    /** @var ParsedAnnotation[] */
    private array $methodsAnnotations = [];

    /** @var ParsedAnnotation[] */
    private array $propertiesAnnotations = [];

    /** @var CacheInterface */
    private $cache;

    /**
     * Annotation parser setter.
     *
     * @param Parser $annotationParser
     * @return $this
     */
    public function setAnnotationParser(Parser $annotationParser)
    {
        $this->annotationParser = $annotationParser;

        return $this;
    }

    /**
     * Cache handler setter.
     *
     * @param CacheInterface $cache
     * @return $this
     */
    public function setCacheHandler(CacheInterface $cache = null)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Returns an array with the annotations of all properties of the class or returns the annotations of the property
     * given by the $key parameter.
     *
     * @param string|null $key
     * @return array|ParsedAnnotation
     * @throws \ReflectionException
     */
    public final function getPropertiesAnnotations($key = null)
    {
        if ($key === null) {
            if ($this->cache !== null && $this->cache->has('properties')) {
                return $this->cache->get('properties');
            }

            foreach ($this->reflectionClass->getProperties() as $property) {
                $this->propertiesAnnotations[$property->name] = $this->annotationParser->parse($property->getDocComment());
            }
        } else {
            if ($this->cache !== null && $this->cache->has('properties') && isset(((array)$this->cache->get('properties'))[$key])) {
                return ((array)$this->cache->get('properties'))[$key];
            }

            $this->propertiesAnnotations[$key] = $this->annotationParser->parse($this->reflectionClass->getProperty($key)->getDocComment());
        }

        if ($this->cache !== null) {
            $this->cache->set('properties', $this->propertiesAnnotations);
        }

        return $key === null
            ? $this->propertiesAnnotations
            : $this->propertiesAnnotations[$key];
    }

    /**
     * Returns an array with the annotations of all methods of the class or returns the annotations of the method given
     * by the $key parameter.
     *
     * @param string|null $key
     * @return array|ParsedAnnotation
     * @throws \ReflectionException
     */
    public final function getMethodsAnnotations($key = null)
    {
        if ($key === null) {
            if ($this->cache !== null && $this->cache->has('methods')) {
                return $this->cache->get('methods');
            }

            foreach ($this->reflectionClass->getMethods() as $method) {
                $this->methodsAnnotations[$method->name] = $this->annotationParser->parse($method->getDocComment());
            }
        } else {
            if ($this->cache !== null && $this->cache->has('methods') && isset(((array)$this->cache->get('methods'))[$key])) {
                return ((array)$this->cache->get('methods'))[$key];
            }

            $this->methodsAnnotations[$key] = $this->annotationParser->parse($this->reflectionClass->getMethod($key)->getDocComment());
        }

        if ($this->cache !== null) {
            $this->cache->set('methods', $this->methodsAnnotations);
        }

        return $key === null
            ? $this->methodsAnnotations
            : $this->methodsAnnotations[$key];
    }

    /**
     * Return the annotations of the given class.
     *
     * @return array|ParsedAnnotation
     */
    public final function getClassAnnotations()
    {
        if ($this->cache !== null && $this->cache->has('class')) {
            return $this->cache->get('class');
        }

        $classAnnotation = $this->annotationParser->parse($this->reflectionClass->getDocComment());

        if ($this->cache !== null) {
            $this->cache->set('class', $classAnnotation);
        }

        return $classAnnotation;
    }
}

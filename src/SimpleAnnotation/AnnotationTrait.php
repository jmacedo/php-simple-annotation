<?php

namespace SimpleAnnotation;

use ReflectionClass;
use ReflectionException;
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
     * @param ?CacheInterface $cache
     * @return $this
     */
    public function setCacheHandler(?CacheInterface $cache = null)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Returns an array with the annotations of all properties of the class.
     *
     * @return ParsedAnnotation[]
     */
    public final function getPropertiesAnnotations() : array
    {
        if ($this->cache !== null && $this->cache->has('properties')) {
            return $this->cache->get('properties');
        }

        foreach ($this->reflectionClass->getProperties() as $property) {
            $docBlock = $property->getDocComment() ? $property->getDocComment() : '';
            $this->propertiesAnnotations[$property->name] = $this->annotationParser->parse($docBlock);
        }

        $this->cache !== null && $this->cache->set('properties', $this->propertiesAnnotations);

        return $this->propertiesAnnotations;
    }

    /**
     * Returns the annotations of the property given by the $key parameter.
     *
     * @param string $key
     * @return ParsedAnnotation
     * @throws ReflectionException
     */
    public final function getPropertyAnnotations(string $key)
    {
        if ($this->cache !== null && $this->cache->has('properties')) {
            return ((array)$this->cache->get('properties'))[$key];
        }

        if ($this->cache !== null) {
            $this->getPropertiesAnnotations();
            $this->cache->set('properties', $this->propertiesAnnotations);
        } else {
            $docBlock = $this->reflectionClass->getProperty($key)->getDocComment()
                ? $this->reflectionClass->getProperty($key)->getDocComment()
                : '';
            $this->propertiesAnnotations[$key] = $this->annotationParser->parse($docBlock);
        }

        return $this->propertiesAnnotations[$key];
    }

    /**
     * Returns an array with the annotations of all methods of the class.
     *
     * @return ParsedAnnotation[]
     */
    public final function getMethodsAnnotations() : array
    {
        if ($this->cache !== null && $this->cache->has('methods')) {
            return $this->cache->get('methods');
        }

        foreach ($this->reflectionClass->getMethods() as $method) {
            $docBlock = $method->getDocComment() ? $method->getDocComment() : '';
            $this->methodsAnnotations[$method->name] = $this->annotationParser->parse($docBlock);
        }

        $this->cache !== null && $this->cache->set('methods', $this->methodsAnnotations);

        return $this->methodsAnnotations;
    }

    /**
     * Returns the annotations of the method given by the $key parameter.
     *
     * @param string $key
     * @return ParsedAnnotation
     * @throws ReflectionException
     */
    public final function getMethodAnnotations(string $key)
    {
        if ($this->cache !== null && $this->cache->has('methods')) {
            return ((array)$this->cache->get('methods'))[$key];
        }

        if ($this->cache !== null) {
            $this->getMethodsAnnotations();
            $this->cache->set('methods', $this->methodsAnnotations);
        } else {
            $docBlock = $this->reflectionClass->getMethod($key)->getDocComment()
                ? $this->reflectionClass->getMethod($key)->getDocComment()
                : '';
            $this->methodsAnnotations[$key] = $this->annotationParser->parse($docBlock);
        }

        return $this->methodsAnnotations[$key];
    }

    /**
     * Return the annotations of the given class.
     *
     * @return ParsedAnnotation
     */
    public final function getClassAnnotations()
    {
        if ($this->cache !== null && $this->cache->has('class')) {
            return $this->cache->get('class');
        }

        $docBlock = $this->reflectionClass->getDocComment() ? $this->reflectionClass->getDocComment() : '';
        $classAnnotation = $this->annotationParser->parse($docBlock);

        if ($this->cache !== null) {
            $this->cache->set('class', $classAnnotation);
        }

        return $classAnnotation;
    }
}

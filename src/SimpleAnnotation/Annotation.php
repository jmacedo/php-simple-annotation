<?php

namespace SimpleAnnotation;

use ReflectionException;
use SimpleAnnotation\Concerns\CacheInterface;
use SimpleAnnotation\Exceptions\EmptyClassException;

/**
 * Class Annotation.
 *
 * @package SimpleAnnotation
 */
final class Annotation
{
    use AnnotationTrait;

    /**
     * Annotation constructor.
     *
     * @param object|string $class
     * @param CacheInterface|null $cache
     * @throws EmptyClassException
     * @throws ReflectionException
     */
    public function __construct($class, CacheInterface $cache = null)
    {
        if (is_object($class)) {
            $declaredClass = get_class($class);
        } else {
            $declaredClass = $class;
        }

        if (empty($declaredClass)) {
            throw new EmptyClassException();
        }

        $this->reflectionClass = new \ReflectionClass($declaredClass);
        $this->annotationParser = new AnnotationParser();
        $this->cache = $cache;
    }
}

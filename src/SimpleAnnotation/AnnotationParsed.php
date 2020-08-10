<?php

namespace SimpleAnnotation;

use phpDocumentor\Reflection\Types\This;
use SimpleAnnotation\Concerns\ParsedAnnotation;

/**
 * A model class to manipulate the annotations parsed values.
 * The abstraction of \JsonSerializable is necessary to the \SimpleAnnotation\Concerns\Cache\FileCache implementation.
 *
 * @package SimpleAnnotation
 */
final class AnnotationParsed implements ParsedAnnotation, \JsonSerializable
{
    /** @var array */
    private array $properties = [];

    /**
     * Magic method.
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name) : bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        if (! isset($this->properties[$name])) {
            $this->properties[$name] = $value;
        } else {
            if (is_array($this->properties[$name])) {
                $this->properties[$name][] = $value;
            } else {
                $this->properties[$name] = [$this->properties[$name], $value];
            }
        }
    }

    /**
     * Magic method.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->properties[$name];
    }

    /**
     * Stub method of \JsonSerializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function isEmpty(): bool
    {
        return empty($this->properties);
    }
}

<?php

namespace SimpleAnnotation\Concerns;

/**
 * Abstraction to represent the parsed annotations.
 * It uses the PHP magic methods __get and __set to define and retrieve the values.
 *
 * @package SimpleAnnotation\Concerns
 */
interface ParsedAnnotation
{
    /**
     * Magic method.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name);

    /**
     * Magic method.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value);

    /**
     * Magic method.
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name) : bool;

    /**
     * Verifies if there is any annotation parsed or not.
     *
     * @return bool
     */
    public function isEmpty() : bool;
}

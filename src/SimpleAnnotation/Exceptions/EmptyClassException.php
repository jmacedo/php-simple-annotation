<?php

namespace SimpleAnnotation\Exceptions;

/**
 * Class EmptyClassException
 *
 * @package SimpleAnnotation\Exceptions
 */
class EmptyClassException extends \Exception
{
    /**
     * EmptyClassException constructor.
     */
    public function __construct()
    {
        parent::__construct('The given class must not be empty. It must be an instance of the class or the full qualified name.');
    }
}

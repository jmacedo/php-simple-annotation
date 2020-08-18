<?php

namespace SimpleAnnotation\Concerns;

/**
 * The annotation parser abstraction.
 *
 * @package SimpleAnnotation\Concerns
 */
interface ParserInterface
{
    /**
     * The method that orchestrates the parsing of the annotations.
     *
     * @param string $docBlock
     * @return ParsedAnnotationInterface
     */
    public function parse(string $docBlock) : ParsedAnnotationInterface;
}
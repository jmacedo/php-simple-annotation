<?php

namespace SimpleAnnotation\Concerns;

/**
 * The annotation parser abstraction.
 *
 * @package SimpleAnnotation\Concerns
 */
interface Parser
{
    /**
     * The method that orchestrates the parsing of the annotations.
     *
     * @param string $docBlock
     * @return ParsedAnnotation
     */
    public function parse(string $docBlock) : ParsedAnnotation;
}
<?php

namespace Tests\SimpleAnnotation;

use PHPUnit\Framework\TestCase;
use SimpleAnnotation\Annotation;
use SimpleAnnotation\Exceptions\EmptyClassException;

class AnnotationTest extends TestCase
{
    public function testEmptyClassException()
    {
        $this->expectException(EmptyClassException::class);
        $annotation = new Annotation('');
    }
}

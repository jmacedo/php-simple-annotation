<?php

namespace Tests\SimpleAnnotation;

use PHPUnit\Framework\TestCase;
use SimpleAnnotation\AnnotationParser;
use SimpleAnnotation\Concerns\ParsedAnnotation;
use SimpleAnnotation\Concerns\Parser;

class AnnotationParserTest extends TestCase
{
    /** @var AnnotationParser */
    private AnnotationParser $annotationParser;

    /** @var ParsedAnnotation */
    private ParsedAnnotation $parsedAnnotation;

    public function setUp(): void
    {
        $this->annotationParser = new AnnotationParser();

        $exampleAnnotation = '/**
                                    * Class MyAnnotatedClass
                                    *
                                    * @package Examples
                                    * @metadata {"PI": 3.14, "name": "The name", "genre": ["f", "m"], "age": 34}
                                    * @PI 3.14
                                    * @list [\'item 1\', \'item 2\', "item 3"]
                                    * @uselessClass true
                                    * @anotherStringValue "my string"
                                    * @age 34
                                    */';
        $this->parsedAnnotation = $this->annotationParser->parse($exampleAnnotation);
    }

    public function testParsedAnnotationIsInstanceOfParsedAnnotationInterface()
    {
        $this->assertInstanceOf(ParsedAnnotation::class, $this->parsedAnnotation);
    }

    public function testParseStringAnnotationWithSuccess()
    {
        $this->assertIsString($this->parsedAnnotation->package);
        $this->assertEquals('my string', $this->parsedAnnotation->anotherStringValue);
    }

    public function testParseFloatAnnotationWithSuccess()
    {
        $this->assertIsFloat($this->parsedAnnotation->PI);
        $this->assertEquals(3.14, $this->parsedAnnotation->PI);
    }

    public function testParseIntAnnotationWithSuccess()
    {
        $this->assertIsInt($this->parsedAnnotation->age);
        $this->assertEquals(34, $this->parsedAnnotation->age);
    }

    public function testParseBoolAnnotationWithSuccess()
    {
        $this->assertIsBool($this->parsedAnnotation->uselessClass);
        $this->assertEquals(true, $this->parsedAnnotation->uselessClass);
    }

    public function testParseArrayAnnotationWithSuccess()
    {
        $this->assertEquals("item 3", $this->parsedAnnotation->list[2]);
        $this->assertCount(3, $this->parsedAnnotation->list);
    }

    public function testParseJsonAnnotationWithSuccess()
    {
        $this->assertEquals(3.14, $this->parsedAnnotation->metadata->PI);
        $this->assertIsInt($this->parsedAnnotation->metadata->age);
        $this->assertCount(2, $this->parsedAnnotation->metadata->genre);
        $this->assertEquals('The name', $this->parsedAnnotation->metadata->name);
    }

    public function testAnnotationParserImplementsParserInterface()
    {
        $this->assertInstanceOf(Parser::class, $this->annotationParser);
    }
}
<?php

namespace Tests\SimpleAnnotation;

use PHPUnit\Framework\TestCase;
use SimpleAnnotation\Annotation;
use SimpleAnnotation\AnnotationParser;
use SimpleAnnotation\Concerns\Cache\FileCache;
use SimpleAnnotation\Concerns\ParsedAnnotation;
use Tests\TestSources\AnnotatedClass;
use Tests\TestSources\EmptyClass;
use Tests\TestSources\NotAnnotatedClass;

/**
 * Class AnnotationTraitTest
 * @package Tests\SimpleAnnotation
 */
class AnnotationTraitTest extends TestCase
{
    /** @var Annotation */
    private Annotation $emptyClass;

    /** @var Annotation */
    private Annotation $annotatedClass;

    /** @var Annotation */
    private Annotation $notAnnotatedClass;

    /** @var string */
    private string $cacheBasePath;

    public function setUp(): void
    {
        $this->emptyClass = new Annotation(new EmptyClass());
        $this->annotatedClass = new Annotation(new AnnotatedClass());
        $this->notAnnotatedClass = new Annotation(new NotAnnotatedClass());

        $this->cacheBasePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
    }

    public function tearDown(): void
    {
        $directory = dir($this->cacheBasePath);

        while($file = $directory->read()) {
            if (strstr($file, '.cache')) {
                unlink($this->cacheBasePath . $file);
            }
        }

        $directory->close();
    }

    // region FLUENT INTERFACE

    public function testSetAnnotationParserFluentInterface()
    {
        $this->assertInstanceOf(Annotation::class, $this->emptyClass->setAnnotationParser(new AnnotationParser()));
    }

    public function testSetCacheHandlerFluentInterface()
    {
        $path = $this->cacheBasePath . 'empty_class.cache';
        $cache = new FileCache($path);
        $this->assertInstanceOf(Annotation::class, $this->emptyClass->setCacheHandler($cache));

        // Have to unset the cache to destruct the object to persist data and unlink in tearDown().
        $this->emptyClass->setCacheHandler(null);
    }

    // endregion

    // region SPECIFIC PROPERTIES ANNOTATIONS

    public function testGetPropertiesAnnotationsWithScpecificPropertyInstanceOfParsedAnnotation()
    {
        $propertyOneAnnotation = $this->annotatedClass->getPropertiesAnnotations('propertyOne');
        $this->assertInstanceOf(ParsedAnnotation::class, $propertyOneAnnotation);
    }

    public function testGetPropertiesAnnotationsWithUnexistentPropertyException()
    {
        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessageMatches('/Property\s+[a-z0-9]+\s+does not exist/i');
        $unexistentPropertyAnnotation = $this->annotatedClass->getPropertiesAnnotations('unexistentProperty');
    }

    public function testGetPropertiesAnnotationsWithSpecificPropertyWithSuccess()
    {
        $propertyOneAnnotation = $this->annotatedClass->getPropertiesAnnotations('propertyOne');
        $this->assertEquals('string', $propertyOneAnnotation->var);
    }

    // endregion

    // region PROPERTIES ANNOTATIONS

    public function testGetPropertiesAnnotationsIsArray()
    {
        $propAnnotations = $this->annotatedClass->getPropertiesAnnotations();
        $this->assertIsArray($propAnnotations);
    }

    /**
     * @depends testGetPropertiesAnnotationsIsArray
     */
    public function testGetPropertiesAnnotationsIsArrayOfParsedAnnotation()
    {
        $propAnnotations = $this->annotatedClass->getPropertiesAnnotations();

        foreach ($propAnnotations as $propAnnotation) {
            $this->assertInstanceOf(ParsedAnnotation::class, $propAnnotation);
        }
    }

    public function testGetPropertiesAnnotationsWhenThereIsNoAnnotations()
    {
        $propAnnotations = $this->notAnnotatedClass->getPropertiesAnnotations();

        foreach ($propAnnotations as $propAnnotation) {
            $this->assertTrue($propAnnotation->isEmpty());
        }
    }

    public function testGetPropertiesAnnotationsWhenThereIsNoProperties()
    {
        $propAnnotations = $this->emptyClass->getPropertiesAnnotations();

        $this->assertIsArray($propAnnotations);
        $this->assertCount(0, $propAnnotations);
    }

    public function testGetPropertiesAnnotationsWithSuccess()
    {
        $propAnnotations = $this->annotatedClass->getPropertiesAnnotations();

        foreach ($propAnnotations as $propAnnotation) {
            $this->assertFalse($propAnnotation->isEmpty());
        }
    }

    // endregion

    // region SPECIFIC METHODS ANNOTATIONS

    public function testGetMethodsAnnotationsWithScpecificMethodInstanceOfParsedAnnotation()
    {
        $methodOneAnnotation = $this->annotatedClass->getMethodsAnnotations('methodOne');
        $this->assertInstanceOf(ParsedAnnotation::class, $methodOneAnnotation);
    }

    public function testGetMethodsAnnotationsWithUnexistentMethodException()
    {
        $this->expectException(\ReflectionException::class);
        $this->expectExceptionMessageMatches('/Method\s+[a-z0-9]+\s+does not exist/i');
        $unexistentMethodAnnotation = $this->annotatedClass->getMethodsAnnotations('unexistentMethod');
    }

    public function testGetMethodsAnnotationsWithSpecificMethodWithSuccess()
    {
        $methodOneAnnotation = $this->annotatedClass->getMethodsAnnotations('methodOne');
        $this->assertCount(3, $methodOneAnnotation->param);
    }

    // endregion

    // region METHODS ANNOTATIONS

    public function testGetMethodsAnnotationsIsArray()
    {
        $metAnnotations = $this->annotatedClass->getMethodsAnnotations();
        $this->assertIsArray($metAnnotations);
    }

    /**
     * @depends testGetMethodsAnnotationsIsArray
     */
    public function testGetMethodsAnnotationsIsArrayOfParsedAnnotation()
    {
        $metAnnotations = $this->annotatedClass->getMethodsAnnotations();

        foreach ($metAnnotations as $metAnnotation) {
            $this->assertInstanceOf(ParsedAnnotation::class, $metAnnotation);
        }
    }

    public function testGetMethodsAnnotationsWhenThereIsNoAnnotations()
    {
        $metAnnotations = $this->notAnnotatedClass->getMethodsAnnotations();

        foreach ($metAnnotations as $metAnnotation) {
            $this->assertTrue($metAnnotation->isEmpty());
        }
    }

    public function testGetMethodsAnnotationsWhenThereIsNoMethods()
    {
        $metAnnotations = $this->emptyClass->getMethodsAnnotations();
        $this->assertIsArray($metAnnotations);
        $this->assertCount(0, $metAnnotations);
    }

    public function testGetMethodsAnnotationsWithSuccess()
    {
        $metAnnotations = $this->annotatedClass->getMethodsAnnotations();

        foreach ($metAnnotations as $metAnnotation) {
            $this->assertFalse($metAnnotation->isEmpty());
        }
    }

    // endregion

    // region CLASS ANNOTATIONS

    public function testGetClassAnnotationIsInstanceOfParsedAnnotation()
    {
        $classAnnotations = $this->annotatedClass->getClassAnnotations();
        $this->assertInstanceOf(ParsedAnnotation::class, $classAnnotations);
    }

    public function testGetClassAnnotationWithNoAnnotations()
    {
        $classAnnotations = $this->notAnnotatedClass->getClassAnnotations();
        $this->assertTrue($classAnnotations->isEmpty());
    }

    public function testGetClassAnnotationWithSuccess()
    {
        $classAnnotations = $this->annotatedClass->getClassAnnotations();

        $this->assertTrue(isset($classAnnotations->package));
        $this->assertTrue($classAnnotations->thisClassIsAnnotated);
        $this->assertFalse($classAnnotations->invalidClass);
        $this->assertNull($classAnnotations->anotherValue);
    }

    // endregion

    // region CACHED ANNOTATIONS

    public function testGetAnnotationsWithCache()
    {
        $path = $this->cacheBasePath . 'annotated_class.cache';
        $cache = new FileCache($path);
        $this->annotatedClass->setCacheHandler($cache);

        $classAnnotations = $this->annotatedClass->getClassAnnotations();
        $methodsAnnotations = $this->annotatedClass->getMethodsAnnotations();
        $propertiesAnnotations = $this->annotatedClass->getPropertiesAnnotations();

        $cache->persist();

        $classAnnotations = $this->annotatedClass->getClassAnnotations();
        $methodsAnnotations = $this->annotatedClass->getMethodsAnnotations();
        $propertiesAnnotations = $this->annotatedClass->getPropertiesAnnotations();
        $methodOneAnnotations = $this->annotatedClass->getMethodsAnnotations('methodOne');
        $propertyOneAnnotations = $this->annotatedClass->getPropertiesAnnotations('propertyOne');

        $this->assertFileExists($path);
        $this->assertJson(file_get_contents($path));

        // Have to unset the cache to destruct the object to persist data and unlink in tearDown().
        $this->annotatedClass->setCacheHandler(null);
    }

    // endregion
}
